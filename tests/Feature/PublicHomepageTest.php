<?php

namespace Tests\Feature;

use App\Services\Cms\PageSnapshotValidator;
use App\Services\Cms\PublishedCmsPageReader;
use App\Services\Cms\PublishedSectionPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

class PublicHomepageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)),
            'inertia.testing.page_paths' => [resource_path('js/pages')],
            'iches-public.event_slug' => 'canonical-event', 'iches-public.homepage_slug' => 'homepage']);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(Builder::class));
        $this->withoutVite();
    }

    public static function unavailableConfiguration(): iterable
    {
        foreach ([null, '', 'Bad', 'bad--slug', '../event', ['event']] as $slug) {
            yield [$slug];
        }
    }

    #[DataProvider('unavailableConfiguration')]
    public function test_missing_or_invalid_canonical_configuration_never_reads_cms(mixed $slug): void
    {
        config(['iches-public.event_slug' => $slug]);
        Schema::shouldReceive('hasTable')->never();
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldNotReceive('read');
        $this->app->instance(PublishedCmsPageReader::class, $reader);

        $this->assertPreparation();
    }

    public function test_missing_cms_schema_uses_the_existing_reader_and_safe_fallback(): void
    {
        Schema::shouldReceive('hasTable')->once()->with('events')->andReturnFalse();
        $this->assertPreparation();
    }

    public function test_default_homepage_slug_is_not_the_reserved_home_slug(): void
    {
        $configuration = require config_path('iches-public.php');
        $this->assertSame('homepage', $configuration['homepage_slug']);
    }

    public static function locales(): iterable
    {
        foreach (['en', 'id', 'ar'] as $locale) {
            yield $locale => [$locale];
        }
    }

    #[DataProvider('locales')]
    public function test_published_homepage_uses_only_the_configured_scope_and_current_locale(string $locale): void
    {
        $presenter = new PublishedSectionPresenter(new PageSnapshotValidator);
        $cms = $presenter->present($presenter->editorial(Fixtures::snapshot()), Fixtures::event(), 'homepage', $locale);
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldReceive('read')->once()->with('canonical-event', 'homepage', $locale)->andReturn($cms);
        $this->app->instance(PublishedCmsPageReader::class, $reader);

        $response = $this->withSession(['locale' => $locale])->get('/')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page->component('public/CmsPage')
                ->where('cms', $cms)->where('locale', $locale)
                ->where('direction', $locale === 'ar' ? 'rtl' : 'ltr')
                ->where('cms.page.fallback', $locale !== 'en')
                ->has('cms.sections', 7)->missing('auth')->missing('cms.revision_id'));
        foreach (['SECRET DRAFT', 'media_ids', 'draft_revision_id', 'published_revision_id', '/draft-private'] as $private) {
            $response->assertDontSee($private, false);
        }
        $this->assertNull($cms['event']['starts_at']);
    }

    #[DataProvider('locales')]
    public function test_preparation_preserves_locale_and_rtl(string $locale): void
    {
        config(['iches-public.event_slug' => null]);
        $this->withSession(['locale' => $locale])->get('/')->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('public/Home')
                ->where('locale', $locale)->where('direction', $locale === 'ar' ? 'rtl' : 'ltr')->missing('cms'));
    }

    public static function unavailableContent(): iterable
    {
        yield 'missing event page or revision' => [new ModelNotFoundException];
        yield 'unpublished corrupt or foreign content rejected by reader' => [new HttpException(404)];
    }

    #[DataProvider('unavailableContent')]
    public function test_expected_reader_absence_renders_preparation(\Throwable $exception): void
    {
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldReceive('read')->once()->with('canonical-event', 'homepage', 'en')->andThrow($exception);
        $this->app->instance(PublishedCmsPageReader::class, $reader);
        $this->assertPreparation();
    }

    public function test_unexpected_failures_are_not_hidden_by_preparation(): void
    {
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldReceive('read')->once()->andThrow(new RuntimeException('Unexpected reader failure'));
        $this->app->instance(PublishedCmsPageReader::class, $reader);
        $this->withoutExceptionHandling();
        $this->expectException(RuntimeException::class);
        $this->get('/');
    }

    public function test_non_404_http_errors_are_not_converted_to_success(): void
    {
        $reader = Mockery::mock(PublishedCmsPageReader::class);
        $reader->shouldReceive('read')->once()->andThrow(new HttpException(503));
        $this->app->instance(PublishedCmsPageReader::class, $reader);
        $this->get('/')->assertStatus(503);
    }

    private function assertPreparation(): void
    {
        $this->get('/')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page->component('public/Home')->missing('cms')->missing('auth'));
    }
}
