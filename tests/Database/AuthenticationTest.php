<?php

namespace Tests\Database;

use App\Models\AuthChallenge;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Services\Auth\PasswordlessAuth;
use Database\Seeders\IchesLocalDemoSeeder;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    private ?Connection $connection = null;

    private string $email;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('ICHES_DB_TESTS') !== '1') {
            $this->markTestSkipped('Explicit local database opt-in required.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $this->assertSame('iches_unisya', $mysql['database']);
        config(['database.default' => 'mysql', 'app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        $this->connection = DB::connection();
        $selected = DB::selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        $this->connection->beginTransaction();
        $this->email = 'auth-'.Str::uuid7().'@iches.test';
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        try {
            while ($this->connection && $this->connection->transactionLevel() > 0) {
                $this->connection->rollBack();
            }
        } finally {
            parent::tearDown();
        }
    }

    private function issue(): array
    {
        app(PasswordlessAuth::class)->issue($this->email, 'Synthetic Auth');
        $message = Mail::mailer('array')->getSymfonyTransport()->messages()->last()->getOriginalMessage();
        $body = $message->getTextBody();
        preg_match('/code: ([0-9]{6})/', $body, $code);
        preg_match('~/auth/magic/([a-f0-9]{64})~', $body, $token);

        return [$code[1], $token[1]];
    }

    private function forgetLogin(): void
    {
        $this->post('/logout', ['_token' => session()->token()])->assertRedirect('/login');
        Auth::forgetGuards();
    }

    public function test_otp_creates_verified_uuid_user_once_regenerates_session_and_logout(): void
    {
        [$code, $token] = $this->issue();
        $this->assertFalse(User::where('email', $this->email)->exists());
        $challenge = AuthChallenge::where('email', $this->email)->sole();
        $this->assertNotSame($code, $challenge->code_hash);
        $this->assertSame(hash('sha256', $token), $challenge->token_hash);
        $this->get('/login');
        $oldId = session()->getId();
        $this->post('/auth/verify-code', ['email' => strtoupper($this->email), 'code' => $code])->assertRedirect('/dashboard');
        $this->assertNotSame($oldId, session()->getId());
        $user = User::where('email', $this->email)->sole();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('7', $user->id[14]);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->password);
        $this->assertSame(0, $user->roleAssignments()->count());
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->get('/dashboard')->assertOk()->assertInertia(fn (Assert $page) => $page->component('dashboard/Index')->where('account.email', $this->email)->missing('account.password')->missing('account.id'));
        $sessionId = session()->getId();
        $csrf = session()->token();
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->assertNotSame($sessionId, session()->getId());
        $this->assertNotSame($csrf, session()->token());
        Auth::forgetGuards();
        $this->get('/dashboard')->assertRedirect('/login');
        $this->post('/auth/verify-code', ['email' => $this->email, 'code' => $code])->assertSessionHasErrors('code');
        $this->assertSame(1, User::where('email', $this->email)->count());
    }

    public function test_wrong_codes_lock_both_methods_after_five_attempts(): void
    {
        [$code, $token] = $this->issue();
        $wrong = $code === '111111' ? '222222' : '111111';
        for ($i = 0; $i < 5; $i++) {
            $this->post('/auth/verify-code', ['email' => $this->email, 'code' => $wrong])->assertSessionHasErrors('code');
        }
        $this->assertSame(5, (int) AuthChallenge::where('email', $this->email)->sole()->attempts);
        $this->post('/auth/verify-code', ['email' => $this->email, 'code' => $code])->assertSessionHasErrors('code');
        $this->assertNull(app(PasswordlessAuth::class)->redeem(null, hash('sha256', $token), true));
        $this->assertGuest();
    }

    public function test_expiry_and_tampered_magic_are_rejected(): void
    {
        [$code, $token] = $this->issue();
        $this->assertNull(app(PasswordlessAuth::class)->redeem(null, hash('sha256', 'tampered'), true));
        $this->travel(10)->minutes();
        $this->post('/auth/verify-code', ['email' => $this->email, 'code' => $code])->assertSessionHasErrors('code');
        $this->get('/auth/magic/'.$token)->assertRedirect('/auth/magic');
        $this->post('/auth/magic')->assertSessionHasErrors('link');
        $this->assertFalse(User::where('email', $this->email)->exists());
    }

    public function test_magic_get_is_non_consuming_post_consumes_once_and_does_not_leak_token(): void
    {
        [$code, $token] = $this->issue();
        $this->get('/auth/magic/'.$token)->assertRedirect('/auth/magic')->assertHeader('Referrer-Policy', 'no-referrer');
        $this->assertNull(AuthChallenge::where('email', $this->email)->sole()->consumed_at);
        $response = $this->get('/auth/magic')->assertOk();
        $this->assertStringNotContainsString($token, $response->getContent());
        $this->post('/auth/magic')->assertRedirect('/dashboard');
        $this->forgetLogin();
        $this->get('/auth/magic/'.$token);
        $this->post('/auth/magic')->assertSessionHasErrors('link');
        $this->post('/auth/verify-code', ['email' => $this->email, 'code' => $code])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_resend_cooldown_and_invalidation_of_previous_challenge(): void
    {
        [$oldCode, $oldToken] = $this->issue();
        $hash = AuthChallenge::where('email', $this->email)->sole()->token_hash;
        $this->issue();
        $this->assertSame($hash, AuthChallenge::where('email', $this->email)->sole()->token_hash);
        $this->travel(61)->seconds();
        [$newCode, $newToken] = $this->issue();
        $this->assertNotSame($oldToken, $newToken);
        $this->assertNull(app(PasswordlessAuth::class)->redeem(null, hash('sha256', $oldToken), true));
        $this->assertNotNull(app(PasswordlessAuth::class)->redeem($this->email, $newCode));
    }

    public function test_generic_responses_for_existing_and_new_users_and_email_throttle(): void
    {
        User::create(['name' => 'Synthetic Existing', 'email' => $this->email]);
        $this->from('/login')->post('/auth/request', ['email' => strtoupper($this->email)])->assertRedirect('/login');
        $existing = session('status');
        $this->post('/auth/request', ['email' => 'new-'.$this->email])->assertSessionHas('status', $existing);
        for ($i = 0; $i < 4; $i++) {
            $this->travel(61)->seconds();
            $this->post('/auth/request', ['email' => $this->email]);
        }
        $hash = AuthChallenge::where('email', $this->email)->sole()->token_hash;
        $this->travel(61)->seconds();
        $this->post('/auth/request', ['email' => $this->email])->assertSessionHas('status', $existing);
        $this->assertSame($hash, AuthChallenge::where('email', $this->email)->sole()->token_hash);
        for ($i = 0; $i < 3; $i++) {
            $this->post('/auth/request', ['email' => $this->email]);
        }
        $this->post('/auth/request', ['email' => $this->email])->assertStatus(429);
    }

    public function test_existing_user_name_and_roles_are_preserved(): void
    {
        $user = User::create(['name' => 'Synthetic Original', 'email' => $this->email]);
        [$code] = $this->issue();
        $verified = app(PasswordlessAuth::class)->redeem($this->email, $code);
        $this->assertSame($user->id, $verified->id);
        $this->assertSame('Synthetic Original', $verified->name);
        $this->assertSame(0, $verified->roleAssignments()->count());
    }

    public function test_challenge_row_lock_rejects_a_competing_connection(): void
    {
        $this->issue();
        AuthChallenge::where('email', $this->email)->lockForUpdate()->sole();
        config(['database.connections.auth_contender' => config('database.connections.mysql')]);
        $contender = DB::connection('auth_contender');
        $contender->beginTransaction();
        try {
            $contender->select('SELECT id FROM auth_challenges WHERE email = ? FOR UPDATE NOWAIT', [$this->email]);
            $this->fail('Competing redemption must not acquire the locked challenge.');
        } catch (QueryException $exception) {
            $this->assertSame(3572, (int) $exception->errorInfo[1]);
        } finally {
            $contender->rollBack();
            DB::purge('auth_contender');
        }
    }

    public function test_consumption_and_user_creation_roll_back_together_on_error(): void
    {
        [$code] = $this->issue();
        User::creating(function (): void {
            throw new \RuntimeException('Synthetic failure');
        });
        try {
            app(PasswordlessAuth::class)->redeem($this->email, $code);
            $this->fail('Expected synthetic failure.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Synthetic failure', $exception->getMessage());
            $this->assertNull(AuthChallenge::where('email', $this->email)->sole()->consumed_at);
            $this->assertFalse(User::where('email', $this->email)->exists());
        } finally {
            User::flushEventListeners();
        }
    }

    public function test_demo_seed_is_idempotent_preserves_revoked_grants_and_demo_login_is_gated(): void
    {
        $this->app->instance('env', 'local');
        config(['iches-auth.demo_mode' => true]);
        $seeder = new IchesLocalDemoSeeder;
        $seeder->run();
        $counts = [User::count(), Event::count(), RoleAssignment::count()];
        $grant = RoleAssignment::where('event_id', Event::where('slug', 'iches-demo-local')->sole()->id)->firstOrFail();
        $grant->revoked_at = now();
        $grant->save();
        $seeder->run();
        $this->assertSame($counts, [User::count(), Event::count(), RoleAssignment::count()]);
        $this->assertNotNull($grant->fresh()->revoked_at);
        foreach (['admin', 'reviewer', 'presenter', 'participant'] as $label) {
            $user = User::where('email', $label.'@iches.test')->sole();
            $this->assertTrue(Hash::check('password', $user->password));
            $this->assertNotSame('password', $user->password);
        }
        // Keep Laravel's CSRF test bypass for this HTTP test; gate still observes local.
        $csrf = Str::random(40);
        $this->withSession(['_token' => $csrf])->post('/dev/login', ['email' => 'participant@iches.test', 'password' => 'password', '_token' => $csrf])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'participant@iches.test')->sole());
        $this->forgetLogin();
        $this->withSession(['_token' => $csrf])->post('/dev/login', ['email' => 'participant@iches.test', 'password' => 'wrong', '_token' => $csrf])->assertRedirect()->assertSessionHasErrors('email');
        for ($i = 0; $i < 3; $i++) {
            $this->withSession(['_token' => $csrf])->post('/dev/login', ['email' => 'participant@iches.test', 'password' => 'wrong', '_token' => $csrf])->assertSessionHasErrors('email');
        }
        $this->withSession(['_token' => $csrf])->post('/dev/login', ['email' => 'participant@iches.test', 'password' => 'password', '_token' => $csrf])->assertStatus(429);
        config(['iches-auth.demo_mode' => false]);
        $this->get('/dev/login')->assertNotFound();
        $this->expectException(\LogicException::class);
        $seeder->run();
    }
}
