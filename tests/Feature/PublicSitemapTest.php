<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Services\Cms\PublishedCmsPageReader;
use DOMDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mockery;
use PDOException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

class PublicSitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'app.url' => 'https://conference.example',
            'iches-public.event_slug' => 'synthetic-event', 'iches-public.homepage_slug' => 'homepage']);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
    }

    private function locations($response): array
    {
        $response->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')->assertHeader('Cache-Control', 'no-store, private');
        $document = new DOMDocument;
        $this->assertTrue($document->loadXML($response->getContent()));

        return array_map(fn ($node) => $node->textContent, iterator_to_array($document->getElementsByTagName('loc')));
    }

    public static function unavailableConfiguration(): iterable
    {
        foreach ([null, '', 'Bad', '../event', ['event']] as $slug) {
            yield ['iches-public.event_slug', $slug];
        }
        foreach ([null, '', 'https://user:pass@conference.example', 'https://conference.example/subdir'] as $origin) {
            yield ['app.url', $origin];
        }
    }

    #[DataProvider('unavailableConfiguration')]
    public function test_unverified_configuration_never_discovers_pages(string $key, mixed $value): void
    {
        config([$key => $value]);
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldNotReceive('sitemap');
        $this->app->instance(PublishedCmsPageReader::class, $reader);
        Schema::shouldReceive('hasTable')->never();
        $this->assertSame([], $this->locations($this->get('/sitemap.xml')));
    }

    public function test_missing_schema_and_query_errors_return_empty_valid_xml(): void
    {
        Schema::shouldReceive('hasTable')->once()->with('events')->andReturnFalse();
        $this->assertSame([], $this->locations($this->get('/sitemap.xml')));
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        Schema::shouldReceive('hasTable')->andThrow(new QueryException('disabled', 'SECRET SQL', [], new PDOException('SECRET DETAILS')));
        $response = $this->get('/sitemap.xml')->assertDontSee('SECRET');
        $this->assertSame([], $this->locations($response));
    }

    public function test_missing_official_event_does_not_query_other_events_or_pages(): void
    {
        Schema::shouldReceive('hasTable')->andReturnTrue();
        $events = Mockery::mock(Event::class)->makePartial();
        $query = Mockery::mock(Builder::class);
        $events->shouldReceive('newQuery')->once()->andReturn($query);
        $query->shouldReceive('where')->once()->with('slug', 'synthetic-event')->andReturnSelf();
        $query->shouldReceive('firstOrFail')->once()->andThrow(new ModelNotFoundException);
        $pages = Mockery::mock(CmsPage::class);
        $pages->shouldNotReceive('newQuery');
        $this->app->instance(Event::class, $events);
        $this->app->instance(CmsPage::class, $pages);
        $this->assertSame([], $this->locations($this->get('/sitemap.xml')));
    }

    public function test_only_scoped_current_publications_and_complete_locales_enter_bounded_sitemap(): void
    {
        Schema::shouldReceive('hasTable')->andReturnTrue();
        $event = Fixtures::event();
        $pages = new Collection;
        $revisions = new Collection;
        foreach (['homepage', 'about', 'draft-only', 'foreign-event', 'foreign-revision', 'unready', 'bad_slug'] as $slug) {
            $page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => $event->id,
                'slug' => $slug, 'published_revision_id' => $slug === 'draft-only' ? null : (string) Str::uuid7()]);
            if ($slug === 'foreign-event') {
                $page->event_id = (string) Str::uuid7();
            }
            $snapshot = PublicSeoLocalizationTest::snapshot($slug === 'homepage' ? ['en', 'id'] : ['en']);
            if ($slug === 'unready') {
                $snapshot['locales']['en']['status'] = 'draft';
            }
            $revision = (new CmsPageRevision)->forceFill(['id' => $page->published_revision_id ?? (string) Str::uuid7(),
                'page_id' => $slug === 'foreign-revision' ? (string) Str::uuid7() : $page->id, 'schema_version' => 3, 'snapshot' => $snapshot]);
            $pages->push($page);
            $revisions->push($revision);
        }
        $events = Mockery::mock(Event::class)->makePartial();
        $eventQuery = Mockery::mock(Builder::class);
        $events->shouldReceive('newQuery')->once()->andReturn($eventQuery);
        $eventQuery->shouldReceive('where')->once()->with('slug', 'synthetic-event')->andReturnSelf();
        $eventQuery->shouldReceive('firstOrFail')->once()->andReturn($event);
        $pageModel = Mockery::mock(CmsPage::class)->makePartial();
        $pageQuery = Mockery::mock(Builder::class);
        $pageModel->shouldReceive('newQuery')->once()->andReturn($pageQuery);
        $pageQuery->shouldReceive('where')->once()->with('event_id', $event->id)->andReturnSelf();
        $pageQuery->shouldReceive('whereNotNull')->once()->with('published_revision_id')->andReturnSelf();
        $pageQuery->shouldReceive('orderBy')->once()->with('id')->andReturnSelf();
        $pageQuery->shouldReceive('limit')->once()->with(100)->andReturnSelf();
        $pageQuery->shouldReceive('get')->once()->with(['id', 'event_id', 'slug', 'published_revision_id'])->andReturn($pages);
        $revisionModel = Mockery::mock(CmsPageRevision::class)->makePartial();
        $revisionQuery = Mockery::mock(Builder::class);
        $revisionModel->shouldReceive('newQuery')->once()->andReturn($revisionQuery);
        $revisionQuery->shouldReceive('whereIn')->once()->with('page_id', $pages->modelKeys())->andReturnSelf();
        $revisionQuery->shouldReceive('whereIn')->once()->with('id', $pages->pluck('published_revision_id')->all())->andReturnSelf();
        $revisionQuery->shouldReceive('limit')->once()->with(100)->andReturnSelf();
        $revisionQuery->shouldReceive('get')->once()->andReturn($revisions);
        $this->app->instance(Event::class, $events);
        $this->app->instance(CmsPage::class, $pageModel);
        $this->app->instance(CmsPageRevision::class, $revisionModel);
        $response = $this->get('http://attacker.invalid/sitemap.xml');
        $this->assertSame(['https://conference.example/en', 'https://conference.example/id',
            'https://conference.example/en/events/synthetic-event/pages/about'], $this->locations($response));
        foreach (['attacker.invalid', 'draft-only', 'foreign-event', 'foreign-revision', 'unready', 'bad_slug', 'backoffice', 'storage_key', 'SECRET'] as $private) {
            $response->assertDontSee($private);
        }
        foreach ($pages as $page) {
            $response->assertDontSee($page->id);
        }
        foreach ($revisions as $revision) {
            $response->assertDontSee($revision->id);
        }
    }
}
