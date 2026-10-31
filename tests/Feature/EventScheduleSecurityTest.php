<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateWorkspace;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Policies\EventPolicy;
use App\Services\Workspaces;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Mockery;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EventScheduleSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        DB::shouldReceive('connection')->never();
        Gate::policy(Event::class, EventPolicy::class);
    }

    public function test_guest_and_csrf_guards_are_retained(): void
    {
        $route = Route::getRoutes()->getByName('event.schedule');
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains(ValidateWorkspace::class, $route->gatherMiddleware());
        $this->postJson('/workspace/events/selected-event/schedule')->assertUnauthorized();
        $this->app->instance('env', 'local');
        $this->postJson('/workspace/events/selected-event/schedule')->assertStatus(419);
    }

    public function test_missing_personal_and_malformed_workspaces_cannot_write(): void
    {
        $user = (new User)->forceFill(['id' => 'actor', 'email' => 'admin@iches.test']);
        $workspaces = Mockery::mock(Workspaces::class);
        $workspaces->shouldReceive('current')->andReturn('personal');
        $workspaces->shouldNotReceive('selected');
        $this->app->instance(Workspaces::class, $workspaces);
        $this->actingAs($user);
        $event = (new Event)->forceFill(['id' => 'selected-event']);
        $this->assertInstanceOf(EventPolicy::class, Gate::getPolicyFor($event));
        foreach ([null, '', 'personal', 'superadmin', 'assignment:0', 'assignment:1:superadmin'] as $workspace) {
            $this->assertFalse(Gate::forUser($user)->allows('manageSchedule', [$event, $workspace]));
            $this->withSession(['workspace' => $workspace])->postJson('/workspace/events/selected-event/schedule', [
                'workspace' => 'assignment:1', 'role' => 'superadmin', 'event_id' => 'selected-event',
            ])->assertForbidden();
        }
    }

    public static function grants(): iterable
    {
        yield 'eligible' => [[], true];
        yield 'forged assignment' => [['id' => 2], false];
        yield 'other user' => [['user_id' => 'another-user'], false];
        yield 'revoked' => [['revoked_at' => '2031-01-01 00:00:00'], false];
        yield 'institution scoped' => [['institution_id' => 'institution'], false];
        yield 'participant' => [['role_key' => 'participant'], false];
        yield 'foreign event' => [['event_id' => 'foreign-event'], false];
    }

    #[DataProvider('grants')]
    public function test_real_policy_and_workspace_queries_enforce_exact_scopes_without_pdo(array $changes, bool $allowed): void
    {
        $user = (new User)->forceFill(['id' => 'actor']);
        $event = (new Event)->forceFill(['id' => 'selected-event']);
        $grant = array_replace(['id' => 1, 'user_id' => 'actor', 'event_id' => 'selected-event',
            'revoked_at' => null, 'institution_id' => null, 'role_key' => 'superadmin'], $changes);
        $connection = Mockery::mock(MySqlConnection::class)->makePartial();
        $connection->__construct(Mockery::mock(PDO::class), 'schedule_contract', '', ['name' => 'schedule_contract']);
        $connection->shouldNotReceive('insert');
        $connection->shouldNotReceive('update');
        $connection->shouldNotReceive('delete');
        $connection->shouldReceive('select')->once()->andReturnUsing(function ($sql, $bindings) use ($grant): array {
            // Assert the actual SQL from Workspaces, relation ownership and EventPolicy before answering the fake query.
            foreach (['`role_assignments`.`user_id` = ?', '`revoked_at` is null', '`role_assignments`.`id` = ?',
                '`event_id` = ?', '`institution_id` is null', '`role_key` = ?'] as $clause) {
                $this->assertStringContainsString($clause, $sql);
            }
            $this->assertSame(['actor', 'participant', 'presenter', 'reviewer', 'cohost_pic', 'superadmin', '1', 'selected-event', 'superadmin'], $bindings);
            $matches = $grant['id'] === 1 && $grant['user_id'] === 'actor' && $grant['event_id'] === 'selected-event'
                && $grant['revoked_at'] === null && $grant['institution_id'] === null && $grant['role_key'] === 'superadmin';

            return [(object) ['exists' => $matches]];
        });
        $resolver = new ConnectionResolver(['schedule_contract' => $connection]);
        $resolver->setDefaultConnection('schedule_contract');
        $original = Model::getConnectionResolver();
        Model::setConnectionResolver($resolver);
        try {
            $this->assertSame($allowed, Gate::forUser($user)->allows('manageSchedule', [$event, 'assignment:1']));
        } finally {
            Model::setConnectionResolver($original);
        }
    }

    private function workspace(bool $selected = true, bool $eligible = true, bool $policyAllows = true, bool $willSave = false): Event
    {
        $user = (new User)->forceFill(['id' => 'actor', 'email' => 'synthetic@iches.test']);
        $event = Mockery::mock(Event::class)->makePartial();
        $event->__construct();
        $event->setDateFormat('Y-m-d H:i:s')->forceFill(['id' => 'selected-event', 'name' => 'Synthetic event',
            'slug' => 'synthetic', 'year' => 2032, 'status' => 'draft', 'timezone' => 'Asia/Jakarta',
            'starts_at' => null, 'ends_at' => null])->syncOriginal();
        if (! $willSave) {
            $event->shouldNotReceive('save');
        }
        $grant = (new RoleAssignment)->forceFill(['id' => 1]);
        $grant->setRelation('event', $event);
        $workspaces = Mockery::mock(Workspaces::class)->makePartial();
        $query = Mockery::mock(Builder::class);
        $workspaces->shouldReceive('selected')->with($user, 'assignment:1')->andReturn($query);
        $query->shouldReceive('exists')->andReturn($selected);
        $query->shouldReceive('whereNull')->with('institution_id')->andReturnSelf();
        $query->shouldReceive('where')->with('role_key', 'superadmin')->andReturnSelf();
        $query->shouldReceive('with')->with('event')->andReturnSelf();
        $query->shouldReceive('first')->andReturn($eligible ? $grant : null);
        $policy = Mockery::mock(Builder::class);
        $query->shouldReceive('forEvent')->with($event)->andReturn($policy);
        $policy->shouldReceive('whereNull')->with('institution_id')->andReturnSelf();
        $policy->shouldReceive('where')->with('role_key', 'superadmin')->andReturnSelf();
        $policy->shouldReceive('exists')->andReturn($policyAllows);
        $this->app->instance(Workspaces::class, $workspaces);
        $this->actingAs($user)->withSession(['workspace' => 'assignment:1']);

        return $event;
    }

    public function test_ineligible_or_revoked_grant_and_foreign_event_are_denied_before_write(): void
    {
        $this->workspace(false);
        $this->postJson('/workspace/events/selected-event/schedule')->assertForbidden();
        $this->workspace(true, false);
        $this->postJson('/workspace/events/selected-event/schedule')->assertForbidden();
        $this->workspace(true, true, false);
        $this->postJson('/workspace/events/selected-event/schedule')->assertForbidden();
        $this->workspace();
        $this->postJson('/workspace/events/foreign-event/schedule', ['event_id' => 'selected-event'])->assertForbidden();
    }

    public static function invalidInput(): iterable
    {
        yield 'missing' => [[], 'local_start'];
        foreach (['', 'bad', '2032-06-18', '2032-02-30T14:23', '2031-02-29T14:23', '2032-06-18T24:00',
            '2032-06-18T14:23Z', '0000-06-18T14:23', '0001-06-18T14:23', ['bad']] as $value) {
            yield 'local '.json_encode($value) => [['local_start' => $value], 'local_start'];
        }
        foreach (['Not/AZone', '+07:00', '', ['Asia/Jakarta']] as $zone) {
            yield 'zone '.json_encode($zone) => [['timezone' => $zone], 'timezone'];
        }
        yield 'DST gap' => [['local_start' => '2032-03-14T02:30', 'timezone' => 'America/New_York'], 'local_start'];
        yield 'DST overlap' => [['local_start' => '2032-11-07T01:30', 'timezone' => 'America/New_York'], 'local_start'];
        yield 'UTC below storage range' => [['local_start' => '1000-01-01T00:00', 'timezone' => 'Asia/Jakarta'], 'local_start'];
        yield 'UTC above storage range' => [['local_start' => '9999-12-31T23:59', 'timezone' => 'America/New_York'], 'local_start'];
        yield 'unconfirmed' => [['confirmed' => false], 'confirmed'];
    }

    #[DataProvider('invalidInput')]
    public function test_bad_schedule_never_saves(array $input, string $field): void
    {
        $event = $this->workspace();
        $before = $event->getAttributes();
        $this->postJson('/workspace/events/selected-event/schedule', array_replace([
            'local_start' => '2032-06-18T14:23', 'timezone' => 'Asia/Jakarta', 'confirmed' => true,
        ], $input, $input === [] ? ['local_start' => null] : []))->assertUnprocessable()->assertJsonValidationErrors($field);
        $this->assertSame($before, $event->getAttributes());
    }

    public function test_start_must_precede_existing_end(): void
    {
        foreach (['2032-06-18 07:22:00', '2032-06-18 07:23:00'] as $end) {
            $event = $this->workspace();
            $event->ends_at = $end;
            $this->postJson('/workspace/events/selected-event/schedule', [
                'local_start' => '2032-06-18T14:23', 'timezone' => 'Asia/Jakarta', 'confirmed' => true,
            ])->assertUnprocessable()->assertJsonValidationErrors('local_start')->assertJsonPath('errors.local_start.0', 'schedule.beforeEnd');
        }
    }

    public static function validTimes(): iterable
    {
        yield 'Jakarta' => ['2032-06-18T14:23', 'Asia/Jakarta', '2032-06-18T07:23:00+00:00'];
        yield 'UTC' => ['2032-06-18T07:23', 'UTC', '2032-06-18T07:23:00+00:00'];
        yield 'after DST gap' => ['2032-03-14T03:30', 'America/New_York', '2032-03-14T07:30:00+00:00'];
        yield 'after DST overlap' => ['2032-11-07T02:30', 'America/New_York', '2032-11-07T07:30:00+00:00'];
    }

    #[DataProvider('validTimes')]
    public function test_eligible_operator_converts_time_and_only_mutates_schedule_using_mock_save(string $local, string $timezone, string $expected): void
    {
        $event = $this->workspace(willSave: true);
        $event->ends_at = '2033-01-01 00:00:00';
        $event->syncOriginal();
        $event->shouldReceive('save')->once()->andReturnUsing(function () use ($event, $expected, $timezone): bool {
            $this->assertSame($expected, $event->starts_at->format(DATE_ATOM));
            $this->assertSame($timezone, $event->timezone);
            $this->assertEmpty(array_diff(array_keys($event->getDirty()), ['starts_at', 'timezone']));
            $this->assertSame('Synthetic event', $event->name);
            $this->assertSame('draft', $event->status);

            return true;
        });
        $this->postJson('/workspace/events/selected-event/schedule', [
            'local_start' => $local, 'timezone' => $timezone, 'confirmed' => true,
            'event_id' => 'forged', 'name' => 'forged', 'status' => 'published', 'ends_at' => '1900-01-01',
        ])->assertOk()->assertHeader('Cache-Control', 'no-store, private')->assertExactJson([
            'starts_at' => $expected, 'timezone' => $timezone,
        ]);
    }
}
