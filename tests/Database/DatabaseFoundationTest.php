<?php

namespace Tests\Database;

use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tests\Support\FoundationFixtures;
use Tests\TestCase;

// Explicit local invocation only; phpunit.xml keeps the CI Feature suite database-free.
class DatabaseFoundationTest extends TestCase
{
    private ?Connection $connection = null;

    private array $fixtures;

    protected function setUp(): void
    {
        parent::setUp();

        if (getenv('ICHES_DB_TESTS') !== '1') {
            $this->markTestSkipped('Requires explicit ICHES_DB_TESTS=1 and the migrated local development schema.');
        }

        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $this->assertSame('iches_unisya', $mysql['database']);
        config(['database.default' => 'mysql']);
        $this->connection = DB::connection('mysql');
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya', $selected->db);
        $this->assertSame(1, (int) $selected->fk);

        $this->connection->beginTransaction();
        $this->fixtures = FoundationFixtures::create();
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

    public function test_main_entity_ids_are_uuid_v7_and_relationship_ids_match(): void
    {
        foreach ($this->fixtures as $entity) {
            $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $entity->id);
        }

        $role = FoundationFixtures::assign($this->fixtures['user'], $this->fixtures['event'], $this->fixtures['institution']);
        $this->assertIsInt($role->id);
        $this->assertSame($this->fixtures['user']->id, $role->user->id);
        $this->assertSame($this->fixtures['event']->id, $role->event->id);
        $this->assertSame($this->fixtures['institution']->id, $role->institution->id);
        $this->assertSame($this->fixtures['user']->id, $role->assignedBy->id);
        $this->assertNull($this->fixtures['event']->fresh()->starts_at);
        $this->assertNull($this->fixtures['event']->fresh()->ends_at);

        $columns = $this->connection->select("SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND ((TABLE_NAME IN ('users', 'institutions', 'events') AND COLUMN_NAME = 'id') OR (TABLE_NAME = 'role_assignments' AND COLUMN_NAME IN ('user_id', 'event_id', 'institution_id', 'assigned_by')))");
        $this->assertCount(7, $columns);
        foreach ($columns as $column) {
            $this->assertSame('char(36)', $column->COLUMN_TYPE);
        }

        $tables = $this->connection->select('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()');
        foreach ($tables as $table) {
            $this->assertSame('InnoDB', $table->ENGINE);
        }
    }

    public function test_normalized_email_is_unique_including_direct_sql_writes(): void
    {
        $user = $this->fixtures['user'];
        $this->assertSame(mb_strtolower(trim($user->email)), $user->fresh()->email);
        $this->assertSame($user->email, $user->fresh()->normalized_email);

        $this->assertMysqlConstraintViolation(fn () => DB::table('users')->insert([
            'id' => (string) Str::uuid7(),
            'name' => 'Synthetic Duplicate',
            'email' => '  '.strtoupper($user->email).'  ',
        ]), 1062);
    }

    public function test_event_slug_and_eventwide_or_institution_role_scopes_are_unique(): void
    {
        $user = $this->fixtures['user'];
        $event = $this->fixtures['event'];
        $institution = $this->fixtures['institution'];

        $this->assertMysqlConstraintViolation(fn () => Event::create([
            'slug' => $event->slug, 'name' => 'Synthetic Duplicate Event', 'year' => 2099,
        ]), 1062);

        FoundationFixtures::assign($user, $event);
        $this->assertMysqlConstraintViolation(fn () => FoundationFixtures::assign($user, $event), 1062);
        FoundationFixtures::assign($user, $event, $institution);
        $this->assertMysqlConstraintViolation(fn () => FoundationFixtures::assign($user, $event, $institution), 1062);
        FoundationFixtures::assign($user, $event, $this->fixtures['otherInstitution']);
        FoundationFixtures::assign($user, $this->fixtures['otherEvent'], $institution);
        FoundationFixtures::assign($user, $event, $institution, 'presenter');
        $this->assertSame(5, $user->roleAssignments()->active()->count());
    }

    public function test_foreign_keys_reject_missing_references_and_parent_deletion(): void
    {
        $attributes = [
            'user_id' => $this->fixtures['user']->id,
            'event_id' => $this->fixtures['event']->id,
            'institution_id' => $this->fixtures['institution']->id,
            'assigned_by' => $this->fixtures['user']->id,
            'role_key' => 'participant',
        ];

        foreach (['user_id', 'event_id', 'institution_id', 'assigned_by'] as $column) {
            $this->assertMysqlConstraintViolation(fn () => RoleAssignment::forceCreate([
                ...$attributes, $column => (string) Str::uuid7(),
            ]), 1452);
        }

        RoleAssignment::forceCreate($attributes);
        foreach (['user', 'event', 'institution'] as $parent) {
            $this->assertMysqlConstraintViolation(fn () => $this->fixtures[$parent]->delete(), 1451);
        }
    }

    public function test_regrant_keeps_revocation_history_and_rejects_duplicate_active_scopes(): void
    {
        $user = $this->fixtures['user'];
        $event = $this->fixtures['event'];

        foreach ([null, $this->fixtures['institution']] as $institution) {
            $first = FoundationFixtures::assign($user, $event, $institution);
            $firstRevocation = now()->subMinutes(2)->startOfSecond();
            $first->revoked_at = $firstRevocation;
            $first->save();

            $second = FoundationFixtures::assign($user, $event, $institution);
            $this->assertNotSame($first->id, $second->id);
            $this->assertMysqlConstraintViolation(fn () => FoundationFixtures::assign($user, $event, $institution), 1062);

            $secondRevocation = now()->subMinute()->startOfSecond();
            $second->revoked_at = $secondRevocation;
            $second->save();
            $third = FoundationFixtures::assign($user, $event, $institution);

            $scope = fn () => RoleAssignment::query()->forUser($user)->forEvent($event)
                ->where('role_key', 'participant')->where('institution_id', $institution?->id);
            $this->assertSame(3, $scope()->count());
            $this->assertSame(2, $scope()->whereNotNull('revoked_at')->count());
            $this->assertSame(1, $scope()->active()->count());
            $this->assertSame($third->id, $scope()->active()->sole()->id);
            $this->assertNull($first->fresh()->institution_scope);
            $this->assertNull($second->fresh()->institution_scope);
            $this->assertSame($institution?->id ?? '', $third->fresh()->institution_scope);
            $this->assertSame($firstRevocation->toDateTimeString(), $first->fresh()->revoked_at->toDateTimeString());
            $this->assertSame($secondRevocation->toDateTimeString(), $second->fresh()->revoked_at->toDateTimeString());

            $this->assertMysqlConstraintViolation(fn () => FoundationFixtures::assign($user, $event, $institution), 1062);
            $this->assertMysqlConstraintViolation(fn () => DB::table('role_assignments')->where('id', $first->id)->update(['revoked_at' => null]), 1062);
            $this->assertSame($firstRevocation->toDateTimeString(), $first->fresh()->revoked_at->toDateTimeString());
            $this->assertFalse(Gate::forUser($user)->allows('view', $this->fixtures['otherEvent']));
            $this->assertFalse(Gate::forUser($user)->allows('view', [$this->fixtures['otherInstitution'], $event]));
            $this->assertFalse(Gate::forUser($this->fixtures['otherUser'])->allows('view', $event));
        }
    }

    public function test_policies_isolate_users_events_institutions_and_revoked_memberships(): void
    {
        ['user' => $user, 'otherUser' => $otherUser, 'event' => $event, 'otherEvent' => $otherEvent, 'institution' => $institution, 'otherInstitution' => $otherInstitution] = $this->fixtures;
        $gate = Gate::forUser($user);
        $this->assertTrue($gate->allows('view', $user));
        $this->assertFalse($gate->allows('view', $otherUser));
        $this->assertFalse($gate->allows('view', $event));

        $role = FoundationFixtures::assign($user, $event, $institution, 'cohost_pic');
        FoundationFixtures::assign($otherUser, $otherEvent, $institution, 'cohost_pic');
        $this->assertTrue($gate->allows('view', $event));
        $this->assertTrue($gate->allows('view', [$institution, $event]));
        $this->assertFalse($gate->allows('view', $institution));
        $this->assertFalse($gate->allows('view', $otherEvent));
        $this->assertFalse($gate->allows('view', [$otherInstitution, $event]));
        $this->assertFalse($gate->allows('view', [$institution, $otherEvent]));
        $this->assertSame(1, RoleAssignment::query()->active()->forUser($user)->forEvent($event)->forInstitution($institution)->count());

        $user->load('roleAssignments');
        $role->revoked_at = now();
        $role->save();
        $this->assertFalse($gate->allows('view', $event));
        $this->assertFalse($gate->allows('view', [$institution, $event]));
        $this->assertTrue(Gate::forUser($otherUser)->allows('view', [$institution, $otherEvent]));
        $this->assertFalse($gate->allows('update', $event));
    }

    public function test_eventwide_membership_does_not_grant_institution_scope(): void
    {
        $user = $this->fixtures['user'];
        $event = $this->fixtures['event'];
        FoundationFixtures::assign($user, $event);

        $this->assertTrue(Gate::forUser($user)->allows('view', $event));
        $this->assertFalse(Gate::forUser($user)->allows('view', [$this->fixtures['institution'], $event]));
        $this->assertSame(0, RoleAssignment::query()->active()->forUser($this->fixtures['otherUser'])->forEvent($event)->count());
    }

    public function test_transaction_rollback_removes_only_its_synthetic_changes(): void
    {
        $this->connection->beginTransaction();
        $user = User::create(['name' => 'Synthetic Rollback', 'email' => Str::uuid7().'@example.invalid']);
        $this->assertTrue(User::whereKey($user->id)->exists());
        $this->connection->rollBack();
        $this->assertFalse(User::whereKey($user->id)->exists());
        $this->assertTrue(User::whereKey($this->fixtures['user']->id)->exists());
    }

    private function assertMysqlConstraintViolation(Closure $operation, int $driverCode): void
    {
        try {
            $this->connection->transaction($operation);
            $this->fail('Expected a MySQL integrity constraint violation.');
        } catch (QueryException $exception) {
            $this->assertSame($driverCode, (int) $exception->errorInfo[1]);
        }
    }
}
