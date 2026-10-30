<?php

namespace Tests\Database;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\CmsPublicationEvent;
use App\Models\Event;
use App\Models\Institution;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Cms\PageRevisionWriter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event as Events;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

// Never auto-migrate. Run only after separate owner schema + persistence-test approval.
class CmsPublicationLifecycleTest extends TestCase
{
    private ?Connection $connection = null;

    private User $actor;

    private Event $event;

    private CmsPage $page;

    private RoleAssignment $grant;

    private string $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('ICHES_DB_TESTS') !== '1' || getenv('ICHES_CMS_DB_TESTS') !== '1'
            || getenv('ICHES_CMS_LIFECYCLE_DB_TESTS') !== '1' || getenv('ICHES_CMS_DB_SCHEMA') !== 'iches_unisya_cms_test') {
            $this->markTestSkipped('BLOCKED_NOT_RUN: owner GO APPLY MIGRATION ICH-S1-02, lifecycle test GO and all explicit opt-ins required.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $mysql['database'] = 'iches_unisya_cms_test';
        $mysql['url'] = null;
        config(['database.connections.cms_test' => $mysql, 'database.default' => 'cms_test', 'app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        $this->connection = DB::connection();
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya_cms_test', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        foreach (['events', 'users', 'role_assignments', 'stored_files', 'cms_pages', 'cms_page_revisions', 'cms_publication_events'] as $table) {
            $this->assertTrue(Schema::hasTable($table), 'STOP: separately approved schema apply required.');
        }
        $this->connection->beginTransaction();
        $this->actor = User::create(['name' => 'Synthetic CMS publisher', 'email' => Str::uuid7().'@iches.test']);
        $this->event = $this->event();
        $this->page = CmsPage::create(['event_id' => $this->event->id, 'slug' => 'about']);
        $this->grant = $this->assign($this->actor, $this->event);
        $this->workspace = 'assignment:'.$this->grant->id;
        Cache::spy();
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

    private function event(): Event
    {
        return Event::create(['slug' => 'cms-'.Str::uuid7(), 'name' => 'Synthetic event', 'year' => 2027, 'timezone' => 'Asia/Jakarta']);
    }

    private function assign(User $user, Event $event, string $role = 'superadmin', ?Institution $institution = null): RoleAssignment
    {
        return RoleAssignment::forceCreate(['user_id' => $user->id, 'event_id' => $event->id, 'role_key' => $role,
            'institution_id' => $institution?->id, 'assigned_by' => $this->actor->id]);
    }

    private function snapshot(string $status = 'ready'): array
    {
        $missing = ['status' => 'missing', 'content' => null];

        return ['schema_version' => 1, 'locales' => ['en' => ['status' => $status, 'content' => ['title' => 'Conference information']], 'id' => $missing, 'ar' => $missing], 'sections' => []];
    }

    private function draft(?array $snapshot = null): CmsPageRevision
    {
        return app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $this->page, $snapshot ?? $this->snapshot(), $this->workspace);
    }

    private function publish(CmsPageRevision $revision): CmsPageRevision
    {
        return app(CmsPublicationLifecycle::class)->publish($this->actor, $this->event, $this->page, $this->workspace, $revision->id);
    }

    private function url(string $action, ?Event $event = null, ?CmsPage $page = null): string
    {
        return route('cms.'.$action, ['event' => ($event ?? $this->event)->id, 'page' => ($page ?? $this->page)->id]);
    }

    public function test_create_draft_preview_publish_restore_http_flow_preserves_draft_and_history(): void
    {
        $this->actingAs($this->actor)->withSession(['workspace' => $this->workspace]);
        $this->postJson($this->url('create'), ['slug' => 'new-page', 'event_id' => $this->event()->id])->assertCreated();
        $this->assertSame(2, CmsPage::where('event_id', $this->event->id)->count());
        $this->postJson($this->url('draft'), ['snapshot' => $this->snapshot()])->assertCreated();
        $first = $this->page->fresh()->draftRevision;
        $this->getJson($this->url('preview'))->assertOk()->assertJsonPath('revision_id', $first->id)
            ->assertJsonPath('snapshot.locales.ar.status', 'missing')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')->assertDontSee('storage_key');
        $this->postJson($this->url('publish'), ['revision_id' => $first->id])->assertOk();
        $second = $this->draft();
        $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
        $this->getJson($this->url('preview'))->assertJsonPath('revision_id', $second->id);
        $this->postJson($this->url('publish'), ['revision_id' => $second->id])->assertOk();
        $before = $first->fresh()->getAttributes();
        $this->postJson($this->url('restore'), ['revision_id' => $first->id])->assertOk();
        $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
        $this->assertSame($second->id, $this->page->fresh()->draft_revision_id);
        $this->assertSame($before, $first->fresh()->getAttributes());
        $audits = CmsPublicationEvent::where('page_id', $this->page->id)->orderBy('id')->get();
        $this->assertSame(['publish', 'publish', 'restore'], $audits->pluck('action')->all());
        $this->assertSame([null, $first->id, $second->id], $audits->pluck('from_revision_id')->all());
        $this->assertSame([$first->id, $second->id, $first->id], $audits->pluck('to_revision_id')->all());
        foreach ($audits as $audit) {
            $this->assertSame($this->actor->id, $audit->actor_user_id);
            $this->assertSame('7', $audit->id[14]);
            $this->assertNotNull($audit->occurred_at);
        }
    }

    public function test_roles_revoked_foreign_user_other_event_and_institution_scopes_are_denied(): void
    {
        $other = User::create(['name' => 'Synthetic other', 'email' => Str::uuid7().'@iches.test']);
        $foreign = $this->assign($other, $this->event);
        $institution = Institution::create(['official_name' => 'Synthetic institution']);
        $keys = [null, 'personal', 'assignment:'.$foreign->id, 'assignment:'.$this->assign($this->actor, $this->event())->id,
            'assignment:'.$this->assign($this->actor, $this->event, 'superadmin', $institution)->id];
        foreach (['participant', 'presenter', 'reviewer', 'cohost_pic'] as $role) {
            $keys[] = 'assignment:'.$this->assign($this->actor, $this->event, $role)->id;
        }
        $this->grant->forceFill(['revoked_at' => now()])->saveOrFail();
        $keys[] = $this->workspace;
        foreach ($keys as $key) {
            $this->assertFalse(Gate::forUser($this->actor)->allows('create', [CmsPage::class, $this->event, $key]));
            foreach (['updateDraft', 'preview', 'publish', 'restore'] as $ability) {
                $this->assertFalse(Gate::forUser($this->actor)->allows($ability, [$this->page, $this->event, $key]));
            }
            $this->actingAs($this->actor)->withSession(['workspace' => $key]);
            $this->postJson($this->url('create'), ['slug' => 'denied'])->assertForbidden();
            $this->postJson($this->url('draft'), ['snapshot' => $this->snapshot(), 'workspace' => 'assignment:'.$foreign->id])->assertForbidden();
            $this->getJson($this->url('preview'))->assertForbidden();
            foreach (['publish', 'restore'] as $action) {
                $this->postJson($this->url($action), ['revision_id' => (string) Str::uuid7()])->assertForbidden();
            }
        }
        $this->assertSame(0, CmsPublicationEvent::count());
        $this->assertSame(0, $this->page->revisions()->count());
    }

    public function test_writer_rechecks_database_grants_and_rejects_revoked_cached_actor(): void
    {
        $this->actor->load('roleAssignments');
        $this->grant->forceFill(['revoked_at' => now()])->saveOrFail();
        try {
            $this->draft();
            $this->fail('Revoked writer accepted.');
        } catch (AuthorizationException) {
            $this->assertNull($this->page->fresh()->draft_revision_id);
            $this->assertSame(0, $this->page->revisions()->count());
        }
    }

    public function test_cross_event_pages_and_cross_page_restore_never_change_publication(): void
    {
        $first = $this->draft();
        $this->publish($first);
        $otherEvent = $this->event();
        $other = CmsPage::create(['event_id' => $otherEvent->id, 'slug' => 'other']);
        $this->actingAs($this->actor)->withSession(['workspace' => $this->workspace]);
        foreach (['draft', 'publish', 'restore'] as $action) {
            $this->postJson($this->url($action, $this->event, $other), ['snapshot' => $this->snapshot(), 'revision_id' => $first->id])->assertNotFound();
        }
        $this->getJson($this->url('preview', $this->event, $other))->assertNotFound();
        $sameEventPage = CmsPage::create(['event_id' => $this->event->id, 'slug' => 'another']);
        $foreignRevision = app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $sameEventPage, $this->snapshot(), $this->workspace);
        $this->postJson($this->url('restore'), ['revision_id' => $foreignRevision->id])->assertNotFound();
        $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
        $this->assertSame(1, CmsPublicationEvent::count());
    }

    public function test_direct_lifecycle_reauthorizes_after_revocation_and_ignores_forged_page_attributes(): void
    {
        $first = $this->draft();
        $this->publish($first);
        $next = $this->draft();
        $this->actor->load('roleAssignments');
        $this->grant->forceFill(['revoked_at' => now()])->saveOrFail();
        $this->page->published_revision_id = null;
        $this->page->draft_revision_id = $first->id;
        foreach (['publish' => $next, 'restore' => $first] as $action => $target) {
            try {
                app(CmsPublicationLifecycle::class)->$action($this->actor, $this->event, $this->page, $this->workspace, $target->id);
                $this->fail('Revoked lifecycle action accepted.');
            } catch (AuthorizationException) {
                $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
                $this->assertSame($next->id, $this->page->fresh()->draft_revision_id);
                $this->assertSame(1, CmsPublicationEvent::count());
            }
        }
    }

    public function test_empty_stale_duplicate_and_unready_publication_fail_without_audit_or_cache_changes(): void
    {
        $this->actingAs($this->actor)->withSession(['workspace' => $this->workspace]);
        $this->getJson($this->url('preview'))->assertStatus(409);
        $this->postJson($this->url('publish'), ['revision_id' => (string) Str::uuid7()])->assertStatus(409);
        $first = $this->draft($this->snapshot('draft'));
        $this->postJson($this->url('publish'), ['revision_id' => $first->id])->assertUnprocessable();
        $second = $this->draft();
        $this->postJson($this->url('publish'), ['revision_id' => $first->id])->assertStatus(409);
        $this->assertSame(0, CmsPublicationEvent::count());
        $this->assertNull($this->page->fresh()->published_revision_id);
        $this->publish($second);
        $this->postJson($this->url('publish'), ['revision_id' => $second->id])->assertStatus(409);
        $this->assertSame(1, CmsPublicationEvent::count());
        Cache::shouldNotHaveReceived('forget');
        Cache::shouldNotHaveReceived('flush');
    }

    public function test_failed_audit_insert_rolls_back_pointer_and_does_not_schedule_invalidation(): void
    {
        $revision = $this->draft();
        $eventName = 'eloquent.creating: '.CmsPublicationEvent::class;
        Events::listen($eventName, fn () => throw new RuntimeException('Synthetic audit failure.'));
        try {
            $this->publish($revision);
            $this->fail('Expected audit failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Synthetic audit failure.', $exception->getMessage());
        } finally {
            Events::forget($eventName);
        }
        $this->assertNull($this->page->fresh()->published_revision_id);
        $this->assertSame(0, CmsPublicationEvent::count());
        $this->assertSame($revision->id, $this->page->fresh()->draft_revision_id);
        Cache::shouldNotHaveReceived('forget');
        $manager = app('db.transactions');
        $this->assertCount(0, $manager->getCommittedTransactions());
    }

    public function test_cache_callback_waits_for_outer_commit_and_is_discarded_on_rollback(): void
    {
        $this->publish($this->draft());
        Cache::shouldNotHaveReceived('forget');
        $manager = app('db.transactions');
        $this->assertNotEmpty($manager->getCommittedTransactions());
        // Fixture transaction is always rolled back; no test data is committed.
        $this->connection->rollBack();
        $this->assertCount(0, $manager->getCommittedTransactions());
        Cache::shouldNotHaveReceived('forget');
    }

    public function test_unsafe_snapshot_and_http_input_fail_without_mutating_draft(): void
    {
        $safe = $this->draft();
        $bad = $this->snapshot();
        $bad['locales']['en']['content']['title'] = '<script>bad()</script>';
        $this->actingAs($this->actor)->withSession(['workspace' => $this->workspace]);
        $this->postJson($this->url('draft'), ['snapshot' => $bad])->assertUnprocessable();
        $this->postJson($this->url('publish'), ['revision_id' => 'bad'])->assertUnprocessable();
        $this->postJson($this->url('create'), ['slug' => 'admin'])->assertUnprocessable();
        $this->postJson($this->url('create'), ['slug' => 'about'])->assertUnprocessable();
        $this->assertSame($safe->id, $this->page->fresh()->draft_revision_id);
        $this->assertNull($this->page->fresh()->published_revision_id);
        $this->assertSame(1, $this->page->revisions()->count());
    }

    public function test_publication_revalidates_persisted_snapshots_before_updating_pointer(): void
    {
        $snapshot = $this->snapshot();
        $snapshot['locales']['en']['content']['title'] = '<script>unsafe()</script>';
        // Simulate an unsafe historical import; normal draft writer cannot create this.
        $revision = new CmsPageRevision;
        $revision->forceFill(['page_id' => $this->page->id, 'created_by_user_id' => $this->actor->id,
            'version' => 1, 'schema_version' => 1, 'snapshot' => $snapshot])->saveOrFail();
        $this->page->draft_revision_id = $revision->id;
        $this->page->saveOrFail();
        try {
            $this->publish($revision);
            $this->fail('Unsafe historical snapshot published.');
        } catch (ValidationException) {
            $this->assertNull($this->page->fresh()->published_revision_id);
            $this->assertSame(0, CmsPublicationEvent::count());
            Cache::shouldNotHaveReceived('forget');
        }
    }

    public function test_audit_composite_foreign_keys_reject_cross_page_revisions_and_orphans(): void
    {
        $revision = $this->draft();
        $this->publish($revision);
        $other = CmsPage::create(['event_id' => $this->event->id, 'slug' => 'other']);
        $foreign = app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $other, $this->snapshot(), $this->workspace);
        $audit = CmsPublicationEvent::firstOrFail()->getAttributes();
        foreach ([['from_revision_id' => $foreign->id], ['to_revision_id' => $foreign->id], ['actor_user_id' => (string) Str::uuid7()], ['page_id' => $other->id]] as $bad) {
            try {
                DB::table('cms_publication_events')->insert(array_merge($audit, ['id' => (string) Str::uuid7()], $bad));
                $this->fail('Invalid audit FK accepted.');
            } catch (QueryException $exception) {
                $this->assertSame('23000', $exception->errorInfo[0]);
            }
        }
        $this->assertSame(1, CmsPublicationEvent::count());
    }

    public function test_page_lock_blocks_competing_publisher_and_stale_request_cannot_republish(): void
    {
        $first = $this->draft();
        $this->publish($first);
        $observer = DB::build(config('database.connections.cms_test'));
        try {
            $observer->statement('SET SESSION innodb_lock_wait_timeout = 1');
            $observer->beginTransaction();
            try {
                $observer->table('cms_pages')->where('id', $this->page->id)->lockForUpdate()->first();
                $this->fail('Competing publisher acquired locked page.');
            } catch (QueryException $exception) {
                $this->assertSame(1205, $exception->errorInfo[1]);
            }
        } finally {
            if ($observer->transactionLevel() > 0) {
                $observer->rollBack();
            }
            $observer->disconnect();
        }
        $this->draft();
        try {
            $this->publish($first);
            $this->fail('Stale publication accepted.');
        } catch (HttpException $exception) {
            $this->assertSame(409, $exception->getStatusCode());
        }
        $this->assertSame(1, CmsPublicationEvent::count());
        $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
    }
}
