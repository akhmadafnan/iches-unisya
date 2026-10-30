<?php

namespace Tests\Database;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Cms\PageRevisionWriter;
use App\Services\Cms\PublishedCmsPageReader;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

// Source only until separate GO TEST ICH-S1-04. Never migrate, seed or commit fixture rows.
class PublicCmsRenderingTest extends TestCase
{
    private ?Connection $connection = null;

    private Event $event;

    private CmsPage $page;

    private User $actor;

    private string $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['ICHES_DB_TESTS', 'ICHES_CMS_DB_TESTS', 'ICHES_CMS_LIFECYCLE_DB_TESTS', 'ICHES_CMS_UI_DB_TESTS', 'ICHES_CMS_PUBLIC_RENDER_DB_TESTS'] as $flag) {
            if (getenv($flag) !== '1') {
                $this->markTestSkipped('BLOCKED_NOT_RUN: separate GO TEST ICH-S1-04 and all explicit opt-ins required.');
            }
        }
        if (getenv('ICHES_CMS_DB_SCHEMA') !== 'iches_unisya_cms_test') {
            $this->markTestSkipped('BLOCKED_NOT_RUN: isolated CMS schema opt-in required.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $mysql['database'] = 'iches_unisya_cms_test';
        $mysql['url'] = null;
        config(['database.connections.cms_test' => $mysql, 'database.default' => 'cms_test',
            'cache.default' => 'array', 'app.key' => 'base64:'.base64_encode(random_bytes(32)),
            'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        $this->connection = DB::connection();
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya_cms_test', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        foreach (['users', 'events', 'role_assignments', 'stored_files', 'cms_pages', 'cms_page_revisions', 'cms_publication_events', 'migrations'] as $table) {
            $this->assertTrue(Schema::hasTable($table), 'STOP: schema must already exist; never auto-migrate.');
        }
        foreach (glob(database_path('migrations/*.php')) as $migration) {
            $this->assertTrue(DB::table('migrations')->where('migration', pathinfo($migration, PATHINFO_FILENAME))->exists());
        }
        $this->connection->beginTransaction();
        $this->withoutVite();
        $this->actor = User::create(['name' => 'Synthetic public CMS publisher', 'email' => Str::uuid7().'@iches.test']);
        $this->event = Event::create(['slug' => 'public-'.Str::uuid7(), 'name' => 'Synthetic public event', 'year' => 2027, 'timezone' => 'Asia/Jakarta']);
        $grant = RoleAssignment::forceCreate(['user_id' => $this->actor->id, 'event_id' => $this->event->id,
            'role_key' => 'superadmin', 'assigned_by' => $this->actor->id]);
        $this->workspace = 'assignment:'.$grant->id;
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

    private function draft(string $title): CmsPageRevision
    {
        $snapshot = Fixtures::snapshot();
        $snapshot['locales']['en']['content']['title'] = $title;
        foreach ($snapshot['sections'] as &$section) {
            $section['media_ids'] = [];
        }

        return app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $this->page, $snapshot, $this->workspace);
    }

    private function read(): array
    {
        return app(PublishedCmsPageReader::class)->read($this->event->slug, $this->page->slug, 'en');
    }

    private function publish(CmsPageRevision $revision): void
    {
        app(CmsPublicationLifecycle::class)->publish($this->actor, $this->event, $this->page, $this->workspace, $revision->id);
    }

    public function test_guest_reads_persisted_published_revision_and_never_latest_draft_or_foreign_event(): void
    {
        $url = '/events/'.$this->event->slug.'/pages/about';
        $this->get($url)->assertNotFound();
        $published = $this->draft('Published persisted title');
        $this->get($url)->assertNotFound();
        $this->publish($published);
        $draft = $this->draft('PRIVATE unpublished title');
        $this->get($url)->assertOk()->assertInertia(fn (Assert $page) => $page->component('public/CmsPage')
            ->where('cms.page.title', 'Published persisted title')->where('cms.event.starts_at', null)
            ->missing('cms.snapshot')->missing('cms.page.draft_revision_id'))
            ->assertDontSee('PRIVATE unpublished title')->assertDontSee($draft->id);
        $other = Event::create(['slug' => 'foreign-'.Str::uuid7(), 'name' => 'Synthetic foreign event', 'year' => 2027, 'timezone' => 'Asia/Jakarta']);
        $this->get('/events/'.$other->slug.'/pages/about')->assertNotFound();
        $this->get('/events/'.$this->event->slug.'/pages/absent')->assertNotFound();
        $this->get('/events/absent/pages/about')->assertNotFound();
    }

    public function test_publish_restore_callbacks_share_reader_cache_and_transaction_rollback_discards_rows(): void
    {
        $first = $this->draft('First publication');
        $this->publish($first);
        $this->assertSame('First publication', $this->read()['page']['title']);
        $key = CmsPublicationLifecycle::cacheKey($this->page);
        $this->assertNotNull(Cache::get($key));
        $second = $this->draft('Second publication');
        $this->publish($second);
        // Execute the registered real afterCommit callbacks in isolation; fixture DB rows never commit.
        foreach (app('db.transactions')->getCommittedTransactions() as $transaction) {
            foreach ($transaction->getCallbacks() as $callback) {
                $callback();
            }
        }
        $this->assertNull(Cache::get($key));
        $this->assertSame('Second publication', $this->read()['page']['title']);
        app(CmsPublicationLifecycle::class)->restore($this->actor, $this->event, $this->page, $this->workspace, $first->id);
        foreach (app('db.transactions')->getCommittedTransactions() as $transaction) {
            foreach ($transaction->getCallbacks() as $callback) {
                $callback();
            }
        }
        $this->assertNull(Cache::get($key));
        $this->assertSame('First publication', $this->read()['page']['title']);
        $this->assertSame($second->id, $this->page->fresh()->draft_revision_id);
        $eventId = $this->event->id;
        $this->connection->rollBack();
        $this->assertFalse(Event::whereKey($eventId)->exists());
        $this->assertFalse(CmsPage::whereKey($this->page->id)->exists());
        $this->assertCount(0, app('db.transactions')->getCommittedTransactions());
    }

    public function test_private_media_is_removed_and_corrupt_persisted_snapshot_is_a_safe_404(): void
    {
        $fileId = (string) Str::uuid7();
        $file = StoredFile::forceCreate(['id' => $fileId, 'event_id' => $this->event->id,
            'uploaded_by_user_id' => $this->actor->id, 'owner_user_id' => $this->actor->id,
            'kind' => 'cms_image', 'disk' => 'local', 'storage_key' => 'files/'.$this->event->id.'/cms_image/'.$fileId.'.png',
            'original_name' => 'private.png', 'detected_mime' => 'image/png', 'size_bytes' => 128,
            'sha256' => str_repeat('a', 64), 'visibility' => 'private']);
        $snapshot = Fixtures::snapshot();
        foreach ($snapshot['sections'] as &$section) {
            $section['media_ids'] = [$fileId];
        }
        $revision = app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $this->page, $snapshot, $this->workspace);
        $this->publish($revision);
        $result = json_encode($this->read());
        $cache = json_encode(Cache::get(CmsPublicationLifecycle::cacheKey($this->page)));
        foreach ([$fileId, $file->storage_key, 'media_ids', 'storage_key', 'signed'] as $secret) {
            $this->assertStringNotContainsString($secret, $result);
            $this->assertStringNotContainsString($secret, $cache);
        }
        // Simulate corrupt historical storage inside this rolled-back fixture, never through normal writer.
        DB::table('cms_page_revisions')->where('id', $revision->id)->update(['snapshot' => '{"schema_version":2}']);
        Cache::forget(CmsPublicationLifecycle::cacheKey($this->page));
        $this->get('/events/'.$this->event->slug.'/pages/about')->assertNotFound()->assertDontSee('SQLSTATE');
    }
}
