<?php

namespace Tests\Feature;

use App\Services\Auth\PasswordlessAuth;
use Database\Seeders\IchesLocalDemoSeeder;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthenticationSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        DB::shouldReceive('connection')->never();
        $this->withoutVite();
    }

    public function test_login_and_confirmation_are_public_but_dashboard_is_protected(): void
    {
        $this->get('/login')->assertOk()->assertInertia(fn (Assert $page) => $page->component('auth/Login')->missing('auth')->missing('token'));
        $this->get('/auth/magic')->assertOk()->assertInertia(fn (Assert $page) => $page->component('auth/Magic')->missing('token'));
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_demo_is_denied_by_default_and_outside_local_even_with_flag(): void
    {
        $this->get('/dev/login')->assertNotFound();
        $this->post('/dev/login')->assertNotFound();
        config(['iches-auth.demo_mode' => true]);
        foreach (['testing', 'staging', 'production'] as $environment) {
            $this->app->instance('env', $environment);
            $this->get('/dev/login')->assertNotFound();
            $this->withSession(['_token' => 'synthetic-csrf'])->post('/dev/login', ['_token' => 'synthetic-csrf'])->assertNotFound();
        }
        $this->app->instance('env', 'local');
        config(['iches-auth.demo_mode' => false]);
        $this->get('/dev/login')->assertNotFound();
        config(['iches-auth.demo_mode' => true]);
        $this->get('/dev/login')->assertOk();
        $this->get('http://untrusted.invalid/dev/login')->assertNotFound();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.1'])->get('http://localhost/dev/login')->assertNotFound();
    }

    public function test_csrf_rejects_all_auth_mutations_without_token(): void
    {
        // Laravel normally bypasses CSRF in tests; enable its real environment check.
        $this->app->instance('env', 'local');
        foreach (['/auth/request', '/auth/verify-code', '/auth/magic', '/dev/login', '/logout'] as $path) {
            $this->post($path)->assertStatus(419);
        }
    }

    public function test_invalid_email_and_input_types_do_not_touch_database(): void
    {
        $this->post('/auth/request', ['email' => ['bad']])->assertSessionHasErrors('email');
        $this->post('/auth/request', ['email' => 'invalid'])->assertSessionHasErrors('email');
        $this->post('/auth/verify-code', ['email' => 'test@iches.test', 'code' => '123'])->assertSessionHasErrors('code');
    }

    public function test_production_cannot_seed_demo_or_log_auth_secrets(): void
    {
        $this->app->instance('env', 'production');
        config(['iches-auth.demo_mode' => true, 'mail.default' => 'log']);
        try {
            (new IchesLocalDemoSeeder)->run();
            $this->fail('Production demo seeder must fail closed.');
        } catch (\LogicException $exception) {
            $this->assertStringContainsString('opt-in local', $exception->getMessage());
        }
        $this->expectException(\LogicException::class);
        app(PasswordlessAuth::class)->issue('synthetic@iches.test', 'Synthetic');
    }

    public function test_endpoint_limits_are_independent_and_inertia_receives_a_readable_error(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.77']);

        for ($i = 0; $i < 10; $i++) {
            $this->from('/login')->post('/auth/request', ['email' => 'invalid'])->assertSessionHasErrors('email');
        }
        $this->post('/auth/request', ['email' => 'invalid'])->assertStatus(429);
        $this->post('/auth/request', ['email' => 'invalid'], ['X-Inertia' => 'true'])->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many attempts', session('errors')->first('email'));
        $this->post('/auth/verify-code', ['email' => 'synthetic@iches.test', 'code' => '123'])->assertSessionHasErrors('code');
    }
}
