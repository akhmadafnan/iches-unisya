<?php

namespace Tests\Database;

use App\Models\RoleAssignment;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Support\FoundationFixtures as Fixtures;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    private ?Connection $connection = null;

    private array $fixtures;

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
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        $this->connection->beginTransaction();
        $this->fixtures = Fixtures::create();
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

    private function switchTo(RoleAssignment $grant): void
    {
        $this->post('/me/workspace', ['workspace' => 'assignment:'.$grant->id])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->fixtures['user']);
    }

    public function test_switches_keep_identity_and_isolate_active_roles_events_and_institutions(): void
    {
        ['user' => $user, 'event' => $event, 'otherEvent' => $otherEvent, 'institution' => $institution, 'otherInstitution' => $otherInstitution] = $this->fixtures;
        $first = Fixtures::assign($user, $event, $institution, 'cohost_pic');
        $second = Fixtures::assign($user, $otherEvent, null, 'reviewer');
        $third = Fixtures::assign($user, $event, null, 'presenter');
        $before = $user->roleAssignments()->get()->toArray();
        $this->actingAs($user)->get('/me/workspaces')->assertOk()->assertJsonCount(4, 'workspaces')->assertJsonPath('activeWorkspace', 'personal');
        $this->get("/workspace/events/$event->id")->assertForbidden();
        $this->switchTo($first);
        $this->get("/workspace/events/$event->id")->assertOk()->assertExactJson(['id' => $event->id, 'name' => $event->name]);
        $this->get("/workspace/events/$otherEvent->id")->assertForbidden();
        $this->get("/workspace/events/$event->id/institutions/$institution->id")->assertOk();
        $this->get("/workspace/events/$event->id/institutions/$otherInstitution->id")->assertForbidden();
        $this->get("/workspace/events/$otherEvent->id/institutions/$institution->id")->assertForbidden();
        $this->switchTo($second);
        $this->get("/workspace/events/$otherEvent->id")->assertOk();
        $this->get("/workspace/events/$event->id")->assertForbidden();
        $this->switchTo($third);
        $this->get("/workspace/events/$event->id/institutions/$institution->id")->assertForbidden();
        $this->get('/dashboard')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->where('account.email', $user->email)->missing('account.password')->missing('account.id')
            ->where('activeWorkspace', 'assignment:'.$third->id)->has('workspaces', 4));
        $this->post('/me/workspace', ['workspace' => 'personal'])->assertRedirect('/dashboard');
        $this->get("/workspace/events/$event->id")->assertForbidden();
        $this->assertSame($before, $user->roleAssignments()->get()->toArray());
        $this->assertTrue(Gate::forUser($user)->allows('view', $user));
        $this->assertFalse(Gate::forUser($user)->allows('view', $this->fixtures['otherUser']));
    }

    public function test_forgery_unknown_roles_other_users_and_revoked_grants_fail_closed(): void
    {
        ['user' => $user, 'otherUser' => $otherUser, 'event' => $event] = $this->fixtures;
        $grant = Fixtures::assign($user, $event);
        $foreign = Fixtures::assign($otherUser, $event, null, 'superadmin');
        $unknown = Fixtures::assign($user, $event, null, 'unrecognized');
        $demo = Fixtures::assign($user, $event, null, 'admin');
        $this->actingAs($user)->get('/me/workspaces')->assertJsonCount(2, 'workspaces')->assertDontSee($otherUser->email);
        foreach (['assignment:'.$foreign->id, 'assignment:'.$unknown->id, 'assignment:'.$demo->id, 'superadmin', 'assignment:999999999999', 'assignment:'.$grant->id.':superadmin'] as $key) {
            $this->post('/me/workspace', ['workspace' => $key])->assertForbidden();
        }
        $this->post('/me/workspace', ['workspace' => []])->assertSessionHasErrors('workspace');
        $this->switchTo($grant);
        $user->load('roleAssignments');
        $grant->forceFill(['revoked_at' => now()])->save();
        $this->get("/workspace/events/$event->id")->assertForbidden()->assertSessionHas('workspace', 'personal');
        $this->post('/me/workspace', ['workspace' => 'assignment:'.$grant->id])->assertForbidden();
        foreach (['assignment:'.$foreign->id, ['role' => 'superadmin']] as $forged) {
            $this->withSession(['workspace' => $forged])->get('/me/workspaces')->assertJsonPath('activeWorkspace', 'personal');
        }
        $this->actingAs($otherUser)->withSession(['workspace' => 'assignment:'.$grant->id])->get('/me/workspaces')->assertJsonPath('activeWorkspace', 'personal');
        $this->assertFalse(Gate::forUser($user)->allows('view', $event));
    }

    public function test_bank_permission_stub_requires_explicit_active_eventwide_superadmin(): void
    {
        ['user' => $user, 'event' => $event, 'otherEvent' => $otherEvent, 'institution' => $institution] = $this->fixtures;
        foreach (['participant', 'presenter', 'reviewer', 'cohost_pic', 'admin', 'finance', 'unknown'] as $role) {
            $grant = Fixtures::assign($user, $event, null, $role);
            $this->assertFalse(Gate::forUser($user)->allows('manageBankSettings', [$event, 'assignment:'.$grant->id]));
        }
        $scoped = Fixtures::assign($user, $event, $institution, 'superadmin');
        $this->assertFalse(Gate::forUser($user)->allows('manageBankSettings', [$event, 'assignment:'.$scoped->id]));
        $grant = Fixtures::assign($user, $event, null, 'superadmin');
        $key = 'assignment:'.$grant->id;
        $this->assertTrue(Gate::forUser($user)->allows('manageBankSettings', [$event, $key]));
        $this->assertFalse(Gate::forUser($user)->allows('manageBankSettings', [$otherEvent, $key]));
        $this->assertFalse(Gate::forUser($user)->allows('manageBankSettings', [$event, 'personal']));
        $grant->forceFill(['revoked_at' => now()])->save();
        $this->assertFalse(Gate::forUser($user)->allows('manageBankSettings', [$event, $key]));
    }
}
