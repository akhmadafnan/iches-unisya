<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Event;
use App\Models\Institution;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class WorkspaceShellTest extends TestCase
{
    private Collection $grants;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        DB::shouldReceive('connection')->never();
        $this->withoutVite();
        $this->grants = new Collection;
    }

    private function account(): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->forceFill(['id' => '019a0000-0000-7000-8000-000000000001', 'name' => 'Synthetic account', 'email' => 'synthetic@iches.test', 'password' => 'never-shared']);
        $relation = Mockery::mock(HasMany::class);
        $user->shouldReceive('roleAssignments')->andReturn($relation);
        $relation->shouldReceive('active')->andReturnSelf();
        $relation->shouldReceive('whereIn')->with('role_key', ['participant', 'presenter', 'reviewer', 'cohost_pic', 'superadmin'])->andReturnSelf();
        // Only the query boundary is replaced; Workspaces, routes, middleware and controllers run.
        $relation->shouldReceive('getQuery')->andReturnUsing(function () {
            $key = null;
            $invalid = false;
            $query = Mockery::mock(Builder::class);
            $query->shouldReceive('with')->with(['event', 'institution'])->andReturnSelf();
            $query->shouldReceive('orderBy')->with('id')->andReturnSelf();
            $query->shouldReceive('get')->andReturnUsing(fn () => $this->grants);
            $query->shouldReceive('whereKey')->andReturnUsing(function ($value) use ($query, &$key) {
                $key = $value;

                return $query;
            });
            $query->shouldReceive('whereRaw')->with('1 = 0')->andReturnUsing(function () use ($query, &$invalid) {
                $invalid = true;

                return $query;
            });
            $query->shouldReceive('exists')->andReturnUsing(function () use (&$invalid, &$key) {
                return ! $invalid && $this->grants->contains(fn ($grant) => (string) $grant->id === $key);
            });

            return $query;
        });

        return $user;
    }

    private function grant(string $role, int $id): void
    {
        $grant = (new RoleAssignment)->forceFill(['id' => $id, 'role_key' => $role, 'assigned_by' => 'never-shared']);
        $grant->setRelation('event', (new Event)->forceFill(['name' => 'Synthetic event']));
        $grant->setRelation('institution', $role === 'cohost_pic' ? (new Institution)->forceFill(['official_name' => 'Synthetic institution']) : null);
        $this->grants->push($grant);
    }

    public function test_guest_locale_round_trip_updates_initial_html_and_inertia_without_database(): void
    {
        $this->get('/login')->assertOk()->assertSee('<html lang="en" dir="ltr">', false);
        foreach (['ar' => 'rtl', 'id' => 'ltr', 'en' => 'ltr'] as $locale => $direction) {
            $this->post('/locale', ['locale' => $locale, 'redirect' => 'https://untrusted.invalid'])
                ->assertRedirect('/login')->assertSessionHas('locale', $locale);
            $this->get('/login')->assertOk()->assertSee('<html lang="'.$locale.'" dir="'.$direction.'">', false)
                ->assertInertia(fn (Assert $page) => $page->component('auth/Login')->where('locale', $locale)->where('direction', $direction)
                    ->where('supportedLocales', ['en', 'id', 'ar'])->missing('account')->missing('workspaces'));
            $headers = ['X-Inertia' => 'true', 'X-Requested-With' => 'XMLHttpRequest'];
            $version = app(HandleInertiaRequests::class)->version(Request::create('/'));
            if ($version !== null) {
                $headers['X-Inertia-Version'] = $version;
            }
            $this->get('/auth/magic', $headers)
                ->assertOk()->assertHeader('X-Inertia', 'true')->assertJsonPath('props.locale', $locale)->assertJsonPath('props.direction', $direction);
        }
    }

    public function test_locale_rejects_non_allowlisted_values_and_preserves_the_last_valid_session_value(): void
    {
        $this->withSession(['locale' => 'ar']);
        foreach (['fr', 'AR', 'ar-SA', '../ar', '', null, ['ar'], 1, true] as $invalid) {
            $this->postJson('/locale', ['locale' => $invalid])->assertUnprocessable()->assertJsonValidationErrors('locale');
            $this->assertSame('ar', session('locale'));
        }
        $this->postJson('/locale', [])->assertUnprocessable()->assertJsonValidationErrors('locale');
        $this->get('/login')->assertSee('<html lang="ar" dir="rtl">', false);
    }

    public function test_invalid_session_locale_falls_back_to_english_on_each_request(): void
    {
        $this->withSession(['locale' => 'ar'])->get('/login')->assertSee('<html lang="ar" dir="rtl">', false);
        foreach (['fr', ['ar'], null] as $invalid) {
            $this->withSession(['locale' => $invalid])->get('/login')->assertOk()
                ->assertSee('<html lang="en" dir="ltr">', false)
                ->assertInertia(fn (Assert $page) => $page->where('locale', 'en')->where('direction', 'ltr'));
        }
    }

    public function test_locale_mutation_requires_csrf_and_ignores_external_redirects(): void
    {
        $this->app->instance('env', 'local');
        $this->withSession(['locale' => 'en'])->post('/locale', ['locale' => 'ar'])->assertStatus(419);
        $this->assertSame('en', session('locale'));
        $this->withSession(['_token' => 'synthetic-csrf'])->post('/locale', ['locale' => 'ar', '_token' => 'synthetic-csrf'], ['Referer' => 'https://untrusted.invalid'])
            ->assertRedirect('/login')->assertSessionHas('locale', 'ar')->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_authenticated_locale_and_personal_shell_keep_identity_and_safe_props(): void
    {
        $user = $this->account();
        // The demo admin email is an identity, not an implicit role grant.
        $user->forceFill(['email' => 'admin@iches.test']);
        $this->actingAs($user);
        foreach (['en' => 'ltr', 'id' => 'ltr', 'ar' => 'rtl'] as $locale => $direction) {
            $this->post('/locale', ['locale' => $locale])->assertRedirect('/dashboard')->assertSessionHas('locale', $locale);
            $this->get('/dashboard')->assertOk()->assertSee('<html lang="'.$locale.'" dir="'.$direction.'">', false)
                ->assertInertia(fn (Assert $page) => $page->component('dashboard/Index')->where('locale', $locale)->where('direction', $direction)
                    ->where('account', ['name' => $user->name, 'email' => $user->email])->where('activeWorkspace', 'personal')
                    ->has('workspaces', 1)->where('workspaces.0', ['key' => 'personal', 'label' => 'Personal', 'role' => null, 'event' => null, 'institution' => null]));
            $this->assertAuthenticatedAs($user);
        }
    }

    public function test_locale_switch_preserves_active_workspace_and_cannot_change_grants_or_identity(): void
    {
        $user = $this->account();
        $this->grant('reviewer', 1);
        $this->actingAs($user)->post('/me/workspace', ['workspace' => 'assignment:1'])->assertRedirect('/dashboard');
        $this->post('/locale', ['locale' => 'ar', 'workspace' => 'assignment:999', 'role' => 'superadmin', 'user_id' => 'foreign'])
            ->assertRedirect('/dashboard')->assertSessionHas('workspace', 'assignment:1')->assertSessionHas('locale', 'ar');
        $this->get('/dashboard')->assertOk()->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertInertia(fn (Assert $page) => $page->where('activeWorkspace', 'assignment:1')->where('workspaces.1.role', 'reviewer')->has('workspaces', 2));
        $this->assertAuthenticatedAs($user);
    }

    public function test_each_server_granted_workspace_has_real_context_and_switches_without_changing_user(): void
    {
        $user = $this->account();
        $this->actingAs($user);
        foreach (['participant', 'presenter', 'reviewer', 'cohost_pic', 'superadmin'] as $index => $role) {
            $this->grant($role, $index + 1);
            $key = 'assignment:'.($index + 1);
            $this->post('/me/workspace', ['workspace' => $key, 'role' => 'forged', 'user_id' => 'foreign'])->assertRedirect('/dashboard')->assertSessionHas('workspace', $key);
            $this->get('/dashboard')->assertOk()->assertInertia(fn (Assert $page) => $page->where('activeWorkspace', $key)
                ->where('account.email', $user->email)->has('workspaces', $index + 2)
                ->where('workspaces.'.($index + 1).'.role', $role)->where('workspaces.'.($index + 1).'.event', 'Synthetic event')
                ->where('workspaces.'.($index + 1).'.institution', $role === 'cohost_pic' ? 'Synthetic institution' : null)
                ->missing('account.password')->missing('workspaces.'.($index + 1).'.assigned_by'));
            $this->assertAuthenticatedAs($user);
        }
    }

    public function test_foreign_and_forged_keys_are_denied_and_stale_grant_falls_back_to_personal(): void
    {
        $user = $this->account();
        $this->grant('presenter', 1);
        $this->actingAs($user)->post('/me/workspace', ['workspace' => 'assignment:1'])->assertRedirect('/dashboard');
        foreach (['assignment:999', 'assignment:01', 'assignment:1:superadmin', "assignment:1\nsuperadmin", 'superadmin'] as $forged) {
            $this->post('/me/workspace', ['workspace' => $forged])->assertForbidden()->assertSessionHas('workspace', 'assignment:1');
        }
        $this->post('/me/workspace', ['workspace' => ['role' => 'superadmin']])->assertSessionHasErrors('workspace');
        // Simulate the persistence boundary returning no remaining active grants.
        $this->grants = new Collection;
        $this->get('/dashboard')->assertOk()->assertSessionHas('workspace', 'personal')
            ->assertInertia(fn (Assert $page) => $page->where('activeWorkspace', 'personal')->has('workspaces', 1));
        $this->post('/me/workspace', ['workspace' => 'assignment:1'])->assertForbidden();
        $this->withSession(['workspace' => ['role' => 'superadmin']])->get('/dashboard')->assertOk()->assertSessionHas('workspace', 'personal');
        $this->assertAuthenticatedAs($user);
        $this->post('/me/workspace', ['workspace' => 'personal'])->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
