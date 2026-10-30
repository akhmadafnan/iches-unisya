<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Services\Cms\CmsPublicationLifecycle;
use App\Services\Cms\PublishedCmsPageReader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use PDOException;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

class PublicCmsRenderingTest extends TestCase
{
    private Event $event;

    private CmsPage $page;

    private CmsPageRevision $revision;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'inertia.testing.page_paths' => [resource_path('js/pages')]]);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        $this->withoutVite();
    }

    private function fixture(?string $missingClass = null): void
    {
        Schema::shouldReceive('hasTable')->andReturnTrue();
        $this->event = Fixtures::event();
        $this->page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => $this->event->id,
            'slug' => 'about', 'published_revision_id' => (string) Str::uuid7(), 'draft_revision_id' => (string) Str::uuid7()]);
        $this->revision = (new CmsPageRevision)->forceFill(['id' => $this->page->published_revision_id,
            'page_id' => $this->page->id, 'schema_version' => 1, 'snapshot' => Fixtures::snapshot()]);
        foreach ([Event::class => $this->event, CmsPage::class => $this->page, CmsPageRevision::class => $this->revision] as $class => $record) {
            $model = Mockery::mock($class)->makePartial();
            $query = Mockery::mock(Builder::class);
            $model->shouldReceive('newQuery')->andReturn($query);
            if ($class === Event::class) {
                $query->shouldReceive('where')->with('slug', 'synthetic-event')->andReturnSelf();
            } elseif ($class === CmsPage::class) {
                $query->shouldReceive('where')->with('event_id', $this->event->id)->andReturnSelf();
                $query->shouldReceive('where')->with('slug', 'about')->andReturnSelf();
            } else {
                $query->shouldReceive('where')->with('page_id', $this->page->id)->andReturnSelf();
                $query->shouldReceive('whereKey')->andReturnUsing(function ($id) use ($query) {
                    $this->assertSame($this->page->published_revision_id, $id);
                    $this->assertNotSame($this->page->draft_revision_id, $id);

                    return $query;
                });
            }
            if ($class === $missingClass) {
                $query->shouldReceive('firstOrFail')->andThrow(new ModelNotFoundException);
            } else {
                $query->shouldReceive('firstOrFail')->andReturn($record);
            }
            $this->app->instance($class, $model);
        }
    }

    private function url(): string
    {
        return '/events/synthetic-event/pages/about';
    }

    public function test_guest_public_route_uses_scoped_published_reader_in_all_locales(): void
    {
        $this->fixture();
        $route = Route::getRoutes()->getByName('public.cms.page');
        $this->assertSame('events/{event}/pages/{page}', $route->uri());
        $this->assertSame(['event' => 'slug', 'page' => 'slug'], $route->bindingFields());
        $this->assertNotContains('auth', $route->gatherMiddleware());
        foreach (['en', 'id', 'ar'] as $locale) {
            $this->withSession(['locale' => $locale])->get($this->url())->assertOk()
                ->assertHeader('X-Robots-Tag', 'noindex, nofollow')->assertHeader('Cache-Control', 'no-store, private')
                ->assertInertia(fn (Assert $page) => $page->component('public/CmsPage')
                    ->where('locale', $locale)->where('direction', $locale === 'ar' ? 'rtl' : 'ltr')
                    ->where('cms.page.title', 'Published page')->where('cms.page.fallback', $locale !== 'en')
                    ->where('cms.event.starts_at', null)->has('cms.sections', 7)
                    ->missing('account')->missing('workspaces')->missing('cms.snapshot')
                    ->missing('cms.page.published_revision_id')->missing('cms.page.draft_revision_id')
                    ->missing('cms.sections.0.media_ids')->missing('cms.audit'))
                ->assertDontSee('SECRET DRAFT')->assertDontSee($this->page->draft_revision_id);
        }
    }

    public function test_missing_schema_is_safe_404_before_any_model_query(): void
    {
        Schema::shouldReceive('hasTable')->with('events')->andReturnTrue();
        Schema::shouldReceive('hasTable')->with('cms_pages')->andReturnFalse();
        $this->get($this->url())->assertNotFound()->assertDontSee('SQLSTATE');
    }

    public function test_existing_locale_endpoint_sets_session_for_public_page_reload(): void
    {
        $this->fixture();
        foreach (['en', 'id', 'ar'] as $locale) {
            $this->postJson('/locale', ['locale' => $locale])->assertRedirect('/login')->assertSessionHas('locale', $locale);
            $this->get($this->url())->assertOk()->assertInertia(fn (Assert $page) => $page->where('locale', $locale));
        }
    }

    public function test_schema_query_failure_is_safe_404(): void
    {
        Schema::shouldReceive('hasTable')->andThrow(new QueryException('disabled', 'private SQL', [], new PDOException('private database detail')));
        $this->get($this->url())->assertNotFound()->assertDontSee('private database detail')->assertDontSee('private SQL');
    }

    public function test_missing_event_page_or_revision_are_safe_404(): void
    {
        foreach ([Event::class, CmsPage::class, CmsPageRevision::class] as $class) {
            $this->fixture($class);
            $this->get($this->url())->assertNotFound();
        }
    }

    public function test_unpublished_foreign_page_and_foreign_revision_are_rejected_even_with_warm_cache(): void
    {
        $this->fixture();
        $this->get($this->url())->assertOk();
        $published = $this->page->published_revision_id;
        $this->page->published_revision_id = null;
        $this->get($this->url())->assertNotFound();
        $this->page->published_revision_id = $published;
        $this->page->event_id = 'foreign';
        $this->get($this->url())->assertNotFound();
        $this->page->event_id = $this->event->id;
        $this->revision->page_id = 'foreign';
        $this->get($this->url())->assertNotFound();
        $this->revision->page_id = $this->page->id;
        $this->revision->id = 'foreign';
        $this->get($this->url())->assertNotFound();
    }

    public function test_corrupt_snapshot_or_schema_never_enters_public_cache(): void
    {
        $this->fixture();
        $key = CmsPublicationLifecycle::cacheKey($this->page);
        foreach ([['schema_version' => 2], ['sections' => [['component_key' => 'unknown']]], ['locales' => []]] as $corrupt) {
            $this->revision->snapshot = array_replace(Fixtures::snapshot(), $corrupt);
            $this->get($this->url())->assertNotFound();
            $this->assertNull(Cache::get($key));
        }
        $this->revision->schema_version = 2;
        $this->get($this->url())->assertNotFound();
    }

    public function test_cache_has_only_ready_editorial_and_refreshes_on_publish_restore_and_event_updates(): void
    {
        $this->fixture();
        $reader = app(PublishedCmsPageReader::class);
        $original = $reader->read('synthetic-event', 'about', 'en');
        $key = CmsPublicationLifecycle::cacheKey($this->page);
        $cache = Cache::get($key);
        foreach (['SECRET DRAFT', 'media_ids', 'starts_at":null', 'draft_revision_id', 'storage_key'] as $secret) {
            $this->assertStringNotContainsString($secret, json_encode($cache));
        }
        $this->event->starts_at = '2027-01-05 02:15:00';
        $this->assertSame('2027-01-05T09:15:00+07:00', $reader->read('synthetic-event', 'about', 'ar')['event']['starts_at']);
        $this->assertSame($cache, Cache::get($key));
        // Simulate the existing post-commit invalidation contract without opening a DB connection.
        Cache::forget($key);
        $snapshot = Fixtures::snapshot();
        $snapshot['locales']['en']['content']['title'] = 'New publication';
        $this->revision->snapshot = $snapshot;
        $oldId = $this->revision->id;
        $this->page->published_revision_id = $this->revision->id = (string) Str::uuid7();
        $this->assertSame('New publication', $reader->read('synthetic-event', 'about', 'en')['page']['title']);
        // A late cache fill cannot override a fresh published pointer, even before invalidation.
        $this->page->published_revision_id = $this->revision->id = $oldId;
        $this->revision->snapshot = Fixtures::snapshot();
        $this->assertSame($original['page'], $reader->read('synthetic-event', 'about', 'en')['page']);
    }

    public function test_foundation_root_and_noindex_remain_unchanged_and_invalid_slugs_fail_closed(): void
    {
        Schema::shouldReceive('hasTable')->never();
        $this->get('/')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Foundation')->where('task', 'ICH-S0-01'));
        $this->assertStringContainsString('noindex, nofollow', file_get_contents(resource_path('views/app.blade.php')));
        foreach (['Bad', 'bad--slug', 'bad_slug'] as $slug) {
            $this->get('/events/'.$slug.'/pages/about')->assertNotFound();
            $this->get('/events/synthetic-event/pages/'.$slug)->assertNotFound();
        }
    }
}
