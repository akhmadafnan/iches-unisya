<?php

namespace Tests\Database;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Cms\PageRevisionWriter;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use Tests\TestCase;

// No RefreshDatabase, migrations or seeds. Requires separate owner GO and an already migrated test schema.
class CmsPersistenceTest extends TestCase
{
    private ?Connection $connection = null;

    private User $actor;

    private Event $event;

    private CmsPage $page;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('ICHES_DB_TESTS') !== '1' || getenv('ICHES_CMS_DB_TESTS') !== '1'
            || getenv('ICHES_CMS_DB_SCHEMA') !== 'iches_unisya_cms_test') {
            $this->markTestSkipped('BLOCKED_NOT_RUN: separate CMS database GO, both opt-ins and authorized test schema required.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        // Never repoint .env or the development schema. This connection is process-local.
        $mysql['database'] = 'iches_unisya_cms_test';
        $mysql['url'] = null;
        config(['database.connections.cms_test' => $mysql, 'database.default' => 'cms_test']);
        $this->connection = DB::connection();
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya_cms_test', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        foreach (['events', 'users', 'stored_files', 'cms_pages', 'cms_page_revisions'] as $table) {
            $this->assertTrue(Schema::hasTable($table), 'Apply reviewed schema separately before opting in.');
        }
        $this->connection->beginTransaction();
        $this->actor = User::create(['name' => 'Synthetic CMS editor', 'email' => Str::uuid7().'@iches.test']);
        $this->event = $this->event();
        $this->page = CmsPage::create(['event_id' => $this->event->id, 'slug' => 'about']);
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
        return Event::create(['slug' => 'cms-'.Str::uuid7(), 'name' => 'Synthetic CMS test', 'year' => 2027, 'timezone' => 'Asia/Jakarta', 'status' => 'draft']);
    }

    private function snapshot(): array
    {
        $missing = ['status' => 'missing', 'content' => null];

        return ['schema_version' => 1, 'locales' => ['en' => $missing, 'id' => $missing, 'ar' => $missing], 'sections' => []];
    }

    private function draft(?array $snapshot = null): CmsPageRevision
    {
        return app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $this->page, $snapshot ?? $this->snapshot());
    }

    private function constraint(callable $action): void
    {
        try {
            $action();
            $this->fail('Expected a database integrity violation.');
        } catch (QueryException $exception) {
            $this->assertSame('23000', $exception->errorInfo[0]);
        }
    }

    public function test_event_slug_uniqueness_and_empty_initial_pointers(): void
    {
        $other = CmsPage::create(['event_id' => $this->event()->id, 'slug' => 'about']);
        $this->assertSame('about', $other->slug);
        $this->assertNull($this->page->draft_revision_id);
        $this->assertNull($this->page->published_revision_id);
        $this->assertNull($this->event->fresh()->starts_at);
        $this->assertSame('7', $this->page->id[14]);
        $this->constraint(fn () => CmsPage::create(['event_id' => $this->event->id, 'slug' => 'about']));
        $this->constraint(fn () => DB::table('cms_pages')->insert(['id' => (string) Str::uuid7(), 'event_id' => (string) Str::uuid7(), 'slug' => 'orphan']));
    }

    public function test_writer_appends_versions_without_republishing_or_mutating_history(): void
    {
        $first = $this->draft();
        // Simulate a future authorized publisher solely to verify writer isolation.
        DB::table('cms_pages')->where('id', $this->page->id)->update(['published_revision_id' => $first->id]);
        $before = $first->fresh()->getAttributes();
        $next = $this->snapshot();
        $next['locales']['en'] = ['status' => 'ready', 'content' => ['title' => 'New draft']];
        $second = $this->draft($next);
        $this->assertSame([1, 2], [$first->version, $second->version]);
        $this->assertSame($before, $first->fresh()->getAttributes());
        $this->assertSame($second->id, $this->page->fresh()->draft_revision_id);
        $this->assertSame($first->id, $this->page->fresh()->published_revision_id);
        $this->assertSame($this->actor->id, $second->creator->id);
        $this->assertSame($this->page->id, $second->page->id);
        $this->assertSame('7', $second->id[14]);
        $this->constraint(fn () => DB::table('cms_page_revisions')->insert(array_merge($first->getAttributes(), ['id' => (string) Str::uuid7()])));
        foreach ([fn () => $first->save(), fn () => $first->delete()] as $action) {
            try {
                $action();
                $this->fail('Historic revision mutation accepted.');
            } catch (LogicException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_database_rejects_cross_page_pointers_and_orphans(): void
    {
        $first = $this->draft();
        foreach ([$this->event, $this->event()] as $event) {
            $other = CmsPage::create(['event_id' => $event->id, 'slug' => 'other']);
            foreach (['draft_revision_id', 'published_revision_id'] as $pointer) {
                $this->constraint(fn () => DB::table('cms_pages')->where('id', $other->id)->update([$pointer => $first->id]));
            }
        }
        $this->constraint(fn () => DB::table('cms_pages')->where('id', $this->page->id)->delete());
        $this->constraint(fn () => DB::table('cms_page_revisions')->where('id', $first->id)->delete());
        $this->constraint(fn () => DB::table('users')->where('id', $this->actor->id)->delete());
        $this->constraint(fn () => DB::table('events')->where('id', $this->event->id)->delete());
        $this->constraint(fn () => DB::table('cms_page_revisions')->insert(array_merge($first->getAttributes(), ['id' => (string) Str::uuid7(), 'page_id' => (string) Str::uuid7()])));
        $this->constraint(fn () => DB::table('cms_page_revisions')->insert(array_merge($first->getAttributes(), ['id' => (string) Str::uuid7(), 'version' => 2, 'created_by_user_id' => (string) Str::uuid7()])));
        $this->constraint(fn () => DB::table('cms_pages')->where('id', $this->page->id)->update(['draft_revision_id' => (string) Str::uuid7()]));
    }

    public function test_unpersisted_actor_and_unsafe_snapshot_leave_no_revision(): void
    {
        try {
            app(PageRevisionWriter::class)->saveDraft(new User, $this->event, $this->page, $this->snapshot());
            $this->fail('Unpersisted actor accepted.');
        } catch (ValidationException) {
            $this->assertSame(0, $this->page->revisions()->count());
        }
        $snapshot = $this->snapshot();
        $snapshot['locales']['en'] = ['status' => 'ready', 'content' => ['title' => '<script>alert(1)</script>']];
        try {
            $this->draft($snapshot);
            $this->fail('Unsafe snapshot accepted.');
        } catch (ValidationException) {
            $this->assertSame(0, $this->page->revisions()->count());
            $this->assertNull($this->page->fresh()->draft_revision_id);
        }
    }

    public function test_cross_event_scope_rejection_uses_fresh_page_not_caller_attributes(): void
    {
        $other = $this->event();
        $this->page->event_id = $other->id;
        $this->expectException(ValidationException::class);
        try {
            app(PageRevisionWriter::class)->saveDraft($this->actor, $other, $this->page, $this->snapshot());
        } finally {
            $this->assertSame(0, $this->page->revisions()->count());
        }
    }

    private function image(array $overrides = []): StoredFile
    {
        $file = new StoredFile;
        $file->forceFill(array_merge([
            'event_id' => $this->event->id, 'uploaded_by_user_id' => $this->actor->id, 'owner_user_id' => $this->actor->id,
            'kind' => 'cms_image', 'disk' => 'local', 'visibility' => 'private',
            'storage_key' => 'files/'.$this->event->id.'/cms_image/'.Str::uuid7().'.png',
            'original_name' => 'synthetic.png', 'detected_mime' => 'image/png', 'size_bytes' => 50, 'sha256' => str_repeat('a', 64),
        ], $overrides))->saveOrFail();

        return $file;
    }

    public function test_media_scope_kind_and_metadata_are_checked_atomically(): void
    {
        $valid = $this->image();
        $missing = ['status' => 'missing', 'content' => null];
        $snapshot = $this->snapshot();
        $snapshot['sections'] = [[
            'id' => (string) Str::uuid7(), 'component_key' => 'editorial-copy', 'variant' => 'default', 'position' => 0, 'visible' => false,
            'locales' => ['en' => $missing, 'id' => $missing, 'ar' => $missing], 'media_ids' => [$valid->id], 'bindings' => [],
        ]];
        $this->assertSame([$valid->id], $this->draft($snapshot)->snapshot['sections'][0]['media_ids']);
        $invalid = [
            (string) Str::uuid7(),
            $this->image(['event_id' => $this->event()->id])->id,
            $this->image(['kind' => 'payment_proof'])->id,
            $this->image(['kind' => 'abstract_pdf'])->id,
            $this->image(['detected_mime' => 'image/svg+xml'])->id,
            $this->image(['storage_key' => '../escape.png'])->id,
            $this->image(['sha256' => str_repeat('z', 64)])->id,
            $this->image(['size_bytes' => 0])->id,
        ];
        foreach ($invalid as $id) {
            $snapshot['sections'][0]['media_ids'] = [$id];
            try {
                $this->draft($snapshot);
                $this->fail('Invalid media accepted.');
            } catch (ValidationException) {
                $this->assertSame(1, $this->page->revisions()->count());
            }
        }
        $this->assertSame('private', $valid->fresh()->visibility);
        $this->assertArrayNotHasKey('url', $valid->toArray());
    }

    public function test_page_lock_blocks_competing_connection_and_versions_remain_sequential(): void
    {
        $first = $this->draft();
        $observer = DB::build(config('database.connections.cms_test'));
        try {
            $observer->statement('SET SESSION innodb_lock_wait_timeout = 1');
            $observer->beginTransaction();
            try {
                $observer->table('cms_pages')->where('id', $this->page->id)->lockForUpdate()->first();
                $this->fail('Competing connection acquired a locked page.');
            } catch (QueryException $exception) {
                $this->assertSame(1205, $exception->errorInfo[1]);
            }
        } finally {
            if ($observer->transactionLevel() > 0) {
                $observer->rollBack();
            }
            $observer->disconnect();
        }
        $this->assertSame($first->version + 1, $this->draft()->version);
    }
}
