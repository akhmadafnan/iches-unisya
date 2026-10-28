<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FoundationSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ephemeral test key; never use local credentials or connect to a database.
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
    }

    public function test_home_returns_the_inertia_foundation_page(): void
    {
        $this->withoutVite()->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Foundation')
                ->where('task', 'ICH-S0-01')
                ->missing('auth'));
    }

    public function test_inertia_navigation_returns_the_page_payload(): void
    {
        $headers = ['X-Inertia' => 'true', 'X-Requested-With' => 'XMLHttpRequest'];
        $version = app(HandleInertiaRequests::class)->version(Request::create('/'));

        if ($version !== null) {
            $headers['X-Inertia-Version'] = $version;
        }

        $this->get('/', $headers)
            ->assertOk()
            ->assertHeader('X-Inertia', 'true')
            ->assertJsonPath('component', 'Foundation')
            ->assertJsonPath('props.task', 'ICH-S0-01');
    }

    public function test_unknown_route_returns_not_found(): void
    {
        $this->get('/foundation-route-that-does-not-exist')->assertNotFound();
    }
}
