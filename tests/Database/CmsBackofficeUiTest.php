<?php

namespace Tests\Database;

use App\Models\CmsPage;
use App\Models\Event;
use App\Models\Institution;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Cms\PageRevisionWriter;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

// Separate owner GO TEST required; no RefreshDatabase, migrations, seeds or committed fixtures.
class CmsBackofficeUiTest extends TestCase
{
    private ?Connection $connection = null;

    private User $actor;

    private Event $event;

    private string $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('ICHES_DB_TESTS') !== '1' || getenv('ICHES_CMS_DB_TESTS') !== '1'
            || getenv('ICHES_CMS_LIFECYCLE_DB_TESTS') !== '1' || getenv('ICHES_CMS_UI_DB_TESTS') !== '1'
            || getenv('ICHES_CMS_DB_SCHEMA') !== 'iches_unisya_cms_test') {
            $this->markTestSkipped('BLOCKED_NOT_RUN: separate owner GO TEST ICH-S1-03 and all five CMS opt-ins required.');
        }
        $mysql = config('database.connections.mysql');
        $this->assertSame('127.0.0.1', $mysql['host']);
        $this->assertSame('3306', (string) $mysql['port']);
        $mysql['database'] = 'iches_unisya_cms_test';
        $mysql['url'] = null;
        config(['database.connections.cms_test' => $mysql, 'database.default' => 'cms_test',
            'app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        $this->connection = DB::connection();
        $selected = $this->connection->selectOne('SELECT DATABASE() AS db, @@foreign_key_checks AS fk');
        $this->assertSame('iches_unisya_cms_test', $selected->db);
        $this->assertSame(1, (int) $selected->fk);
        foreach (['events', 'users', 'role_assignments', 'cms_pages', 'cms_page_revisions', 'cms_publication_events'] as $table) {
            $this->assertTrue(Schema::hasTable($table), 'STOP: expected approved test schema.');
        }
        $this->connection->beginTransaction();
        $this->actor = User::create(['name' => 'Synthetic CMS admin', 'email' => Str::uuid7().'@iches.test']);
        $this->event = $this->event();
        $grant = $this->assign($this->actor, $this->event);
        $this->workspace = 'assignment:'.$grant->id;
        $this->withoutVite();
        $this->actingAs($this->actor)->withSession(['workspace' => $this->workspace]);
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

    public function test_index_is_event_scoped_searchable_and_paginated(): void
    {
        for ($i = 0; $i < 22; $i++) {
            CmsPage::create(['event_id' => $this->event->id, 'slug' => 'page-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT)]);
        }
        CmsPage::create(['event_id' => $this->event()->id, 'slug' => 'foreign-page']);
        $this->get('/backoffice/website/pages')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('cms/Pages')->where('schemaAvailable', true)->has('pages.data', 20)->where('pages.total', 22));
        $this->get('/backoffice/website/pages?page=2')->assertOk()->assertInertia(fn (Assert $page) => $page->has('pages.data', 2));
        $this->get('/backoffice/website/pages?search=page-01')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->has('pages.data', 1)->where('pages.data.0.slug', 'page-01')->where('pages.data.0.draft_version', null));
        $this->get('/backoffice/website/pages?search=foreign')->assertInertia(fn (Assert $page) => $page->has('pages.data', 0));
    }

    public function test_real_create_edit_preview_publish_restore_contract_and_safe_history(): void
    {
        $url = '/workspace/events/'.$this->event->id.'/cms/pages';
        $id = $this->postJson($url, ['slug' => 'about'])->assertCreated()->json('page_id');
        $page = CmsPage::findOrFail($id);
        $missing = ['status' => 'missing', 'content' => null];
        $snapshot = ['schema_version' => 1, 'locales' => ['en' => ['status' => 'ready', 'content' => ['title' => 'Synthetic title']], 'id' => $missing, 'ar' => $missing], 'sections' => []];
        $first = app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $page, $snapshot, $this->workspace);
        app(CmsPublicationLifecycle::class)->publish($this->actor, $this->event, $page, $this->workspace, $first->id);
        $second = app(PageRevisionWriter::class)->saveDraft($this->actor, $this->event, $page, $snapshot, $this->workspace);
        $this->get('/backoffice/website/pages/'.$id)->assertOk()->assertInertia(fn (Assert $view) => $view
            ->component('cms/Editor')->where('snapshot', $snapshot)->where('cmsPage.draft_revision_id', $second->id)
            ->where('cmsPage.published_revision_id', $first->id)->has('revisions', 2)->has('audit', 1)
            ->missing('account.password')->missing('storage_key')->missing('revisions.0.snapshot')->missing('audit.0.actor_user_id'));
        $this->getJson($url.'/'.$id.'/preview')->assertOk()->assertJsonPath('revision_id', $second->id);
        $this->postJson($url.'/'.$id.'/publish', ['revision_id' => $second->id])->assertOk();
        $this->postJson($url.'/'.$id.'/restore', ['revision_id' => $first->id])->assertOk();
        $this->get('/backoffice/website/pages/'.$id)->assertInertia(fn (Assert $view) => $view
            ->where('cmsPage.draft_revision_id', $second->id)->where('cmsPage.published_revision_id', $first->id)->has('audit', 3));
        $this->postJson($url, ['slug' => 'about'])->assertUnprocessable();
        $this->postJson($url, ['slug' => 'admin'])->assertUnprocessable();
    }

    public function test_cross_event_page_is_not_found_and_other_workspace_scopes_are_denied(): void
    {
        $foreign = CmsPage::create(['event_id' => $this->event()->id, 'slug' => 'foreign']);
        $this->get('/backoffice/website/pages/'.$foreign->id)->assertNotFound();
        $other = User::create(['name' => 'Synthetic other', 'email' => Str::uuid7().'@iches.test']);
        $institution = Institution::create(['official_name' => 'Synthetic institution']);
        $keys = ['personal', 'assignment:'.$this->assign($other, $this->event)->id,
            'assignment:'.$this->assign($this->actor, $this->event, 'superadmin', $institution)->id];
        foreach (['participant', 'presenter', 'reviewer', 'cohost_pic'] as $role) {
            $keys[] = 'assignment:'.$this->assign($this->actor, $this->event, $role)->id;
        }
        RoleAssignment::where('user_id', $this->actor->id)->whereNull('institution_id')->where('role_key', 'superadmin')->update(['revoked_at' => now()]);
        $keys[] = $this->workspace;
        foreach ($keys as $key) {
            $this->withSession(['workspace' => $key])->get('/backoffice/website/pages')->assertForbidden();
            $this->get('/backoffice/website/pages/'.$foreign->id)->assertForbidden();
        }
    }
}
