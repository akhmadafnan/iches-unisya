<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\User;
use App\Services\Cms\PublicSeo;
use App\Services\Cms\PublishedSectionPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

class PublicSeoLocalizationTest extends TestCase
{
    private CmsPage $page;

    private CmsPageRevision $revision;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32)), 'app.url' => 'https://conference.example',
            'inertia.testing.page_paths' => [resource_path('js/pages')], 'iches-public.event_slug' => 'synthetic-event']);
        DB::shouldReceive('connection')->never();
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        $this->withoutVite();
    }

    public static function emptySeo(): array
    {
        $missing = ['status' => 'missing', 'content' => null];

        return ['title' => ['en' => $missing, 'id' => $missing, 'ar' => $missing],
            'description' => ['en' => $missing, 'id' => $missing, 'ar' => $missing]];
    }

    public static function snapshot(array $ready = ['en']): array
    {
        $snapshot = Fixtures::snapshot();
        $snapshot['schema_version'] = 3;
        $snapshot['seo'] = self::emptySeo();
        foreach ($snapshot['sections'] as &$section) {
            $section['media'] = null;
            $section['media_ids'] = [];
            foreach ($ready as $locale) {
                $section['locales'][$locale] = $section['locales']['en'];
            }
        }
        unset($section);
        foreach ($ready as $locale) {
            $snapshot['locales'][$locale] = ['status' => 'ready', 'content' => ['title' => 'Published '.$locale.' page']];
            $snapshot['seo']['title'][$locale] = ['status' => 'ready', 'content' => ['value' => 'Published '.$locale.' SEO title']];
            $snapshot['seo']['description'][$locale] = ['status' => 'ready', 'content' => ['value' => 'Published '.$locale.' description']];
        }

        return $snapshot;
    }

    private function fixture(?array $snapshot = null): void
    {
        Schema::shouldReceive('hasTable')->andReturnTrue();
        $event = Fixtures::event();
        $this->page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'event_id' => $event->id, 'slug' => 'about',
            'published_revision_id' => (string) Str::uuid7(), 'draft_revision_id' => (string) Str::uuid7()]);
        $this->revision = (new CmsPageRevision)->forceFill(['id' => $this->page->published_revision_id, 'page_id' => $this->page->id,
            'schema_version' => 3, 'snapshot' => $snapshot ?? self::snapshot()]);
        foreach ([Event::class => $event, CmsPage::class => $this->page, CmsPageRevision::class => $this->revision] as $class => $record) {
            $model = Mockery::mock($class)->makePartial();
            $query = Mockery::mock(Builder::class);
            $model->shouldReceive('newQuery')->andReturn($query);
            if ($class === Event::class) {
                $query->shouldReceive('where')->with('slug', 'synthetic-event')->andReturnSelf();
            } elseif ($class === CmsPage::class) {
                $query->shouldReceive('where')->with('event_id', $event->id)->andReturnSelf();
                $query->shouldReceive('where')->with('slug', 'about')->andReturnSelf();
            } else {
                $query->shouldReceive('where')->with('page_id', $this->page->id)->andReturnSelf();
                $query->shouldReceive('whereKey')->andReturnUsing(function ($id) use ($query) {
                    $this->assertSame($this->page->published_revision_id, $id);
                    $this->assertNotSame($this->page->draft_revision_id, $id);

                    return $query;
                });
            }
            $query->shouldReceive('firstOrFail')->andReturn($record);
            $this->app->instance($class, $model);
        }
    }

    private function url(string $locale): string
    {
        return '/'.$locale.'/events/synthetic-event/pages/about';
    }

    public function test_locale_urls_override_request_language_preserve_page_and_do_not_mutate_authenticated_session(): void
    {
        $this->fixture(self::snapshot(['en', 'id', 'ar']));
        $this->actingAs((new User)->forceFill(['id' => (string) Str::uuid7()]));
        $alternates = [];
        foreach (PublicSeo::LOCALES as $locale) {
            $alternates[$locale] = 'https://conference.example'.$this->url($locale);
        }
        foreach (PublicSeo::LOCALES as $locale) {
            $this->withSession(['locale' => 'id'])->get($this->url($locale))->assertOk()->assertSessionHas('locale', 'id')
                ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
                ->assertSee('<html lang="'.$locale.'" dir="'.($locale === 'ar' ? 'rtl' : 'ltr').'">', false)
                ->assertInertia(fn (Assert $page) => $page->component('public/CmsPage')
                    ->where('locale', $locale)->where('direction', $locale === 'ar' ? 'rtl' : 'ltr')
                    ->where('cms.event.slug', 'synthetic-event')->where('cms.page.slug', 'about')
                    ->where('cms.seo.title', 'Published '.$locale.' SEO title')
                    ->where('cms.seo.description', 'Published '.$locale.' description')
                    ->where('cms.seo.canonical', $alternates[$locale])->where('cms.seo.alternates', $alternates)
                    ->where('cms.seo.language_urls.ar', $this->url('ar'))->missing('account')->missing('cms.snapshot'));
        }
        $this->withSession(['locale' => 'ar'])->get('/events/synthetic-event/pages/about')->assertOk()
            ->assertSessionHas('locale', 'ar')->assertInertia(fn (Assert $page) => $page->where('locale', 'ar'));
        foreach (['/fr', '/en/login', '/fr/events/synthetic-event/pages/about', '/en/events/Bad/pages/about'] as $url) {
            $this->get($url)->assertNotFound();
        }
    }

    public function test_home_locale_routes_and_fallback_do_not_change_session_or_invent_seo(): void
    {
        config(['iches-public.event_slug' => null]);
        Schema::shouldReceive('hasTable')->never();
        foreach (PublicSeo::LOCALES as $locale) {
            $this->withSession(['locale' => 'id'])->get('/'.$locale.'?publicLocale=ar')->assertOk()->assertSessionHas('locale', 'id')
                ->assertInertia(fn (Assert $page) => $page->component('public/Home')->where('locale', $locale)->missing('cms'));
        }
    }

    public function test_configured_homepage_has_one_localized_home_canonical(): void
    {
        config(['iches-public.homepage_slug' => 'about']);
        $this->fixture();
        $this->get('/en')->assertOk()->assertInertia(fn (Assert $page) => $page->where('cms.seo.canonical', 'https://conference.example/en')
            ->where('cms.seo.language_urls.ar', '/ar'));
        $this->get($this->url('en'))->assertOk()->assertInertia(fn (Assert $page) => $page->where('cms.seo.canonical', 'https://conference.example/en'));
    }

    public function test_draft_seo_is_omitted_and_missing_title_uses_genuine_published_title(): void
    {
        $snapshot = self::snapshot();
        foreach (['title', 'description'] as $field) {
            $snapshot['seo'][$field]['en'] = ['status' => 'draft', 'content' => ['value' => 'SECRET SEO DRAFT']];
        }
        $this->fixture($snapshot);
        $this->get($this->url('ar'))->assertOk()->assertDontSee('SECRET SEO DRAFT')->assertInertia(fn (Assert $page) => $page
            ->where('cms.seo.title', 'Published en page')->where('cms.seo.description', null)
            ->where('cms.seo.canonical', 'https://conference.example'.$this->url('en'))
            ->where('cms.seo.alternates', ['en' => 'https://conference.example'.$this->url('en')]))->assertDontSee('storage_key');
    }

    public function test_active_pointer_and_restore_select_metadata_even_with_warm_cache(): void
    {
        $this->fixture();
        $original = $this->revision->snapshot;
        $originalId = $this->revision->id;
        $this->get($this->url('en'))->assertOk();
        $snapshot = $original;
        $snapshot['seo']['title']['en']['content']['value'] = 'New published metadata';
        $this->revision->snapshot = $snapshot;
        $this->page->published_revision_id = $this->revision->id = (string) Str::uuid7();
        $this->get($this->url('en'))->assertOk()->assertInertia(fn (Assert $page) => $page->where('cms.seo.title', 'New published metadata'));
        $this->page->published_revision_id = $this->revision->id = $originalId;
        $this->revision->snapshot = $original;
        $this->get($this->url('en'))->assertOk()->assertInertia(fn (Assert $page) => $page->where('cms.seo.title', 'Published en SEO title'));
    }

    public function test_partial_section_or_alt_translations_are_not_advertised_as_complete(): void
    {
        $snapshot = self::snapshot(['en', 'id', 'ar']);
        $snapshot['sections'][0]['locales']['id']['status'] = 'draft';
        $this->fixture($snapshot);
        $this->get($this->url('id'))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->missing('cms.seo.alternates.id')->has('cms.seo.alternates.en')->has('cms.seo.alternates.ar'));
        $media = CmsMediaSecurityTest::mediaSnapshot((string) Str::uuid7());
        foreach ($media['sections'] as &$section) {
            $section['locales']['id'] = $section['locales']['en'];
        }
        unset($section);
        $media['locales']['id'] = $media['locales']['en'];
        $presenter = app(PublishedSectionPresenter::class);
        $editorial = $presenter->editorial($media);
        $this->assertSame(['en'], app(PublicSeo::class)->eligible($editorial));
    }

    public static function invalidOrigins(): iterable
    {
        foreach ([null, '', 'javascript:bad', 'https://user:pass@conference.example', 'https://conference.example/path',
            'https://conference.example?query=bad', 'https://conference.example#fragment', 'https://conference.example\\bad'] as $origin) {
            yield [$origin];
        }
    }

    #[DataProvider('invalidOrigins')]
    public function test_invalid_origin_omits_absolute_metadata(mixed $origin): void
    {
        config(['app.url' => $origin]);
        $this->fixture();
        $this->get($this->url('en'))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->where('cms.seo.canonical', null)->where('cms.seo.alternates', [])->where('cms.seo.image', null));
    }

    public function test_request_host_never_supplies_canonical_origin_and_indexing_protections_remain(): void
    {
        $this->fixture();
        $this->get('http://attacker.invalid'.$this->url('en'))->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page->where('cms.seo.canonical', 'https://conference.example'.$this->url('en')));
        $this->assertStringContainsString('noindex, nofollow', file_get_contents(resource_path('views/app.blade.php')));
        $this->assertStringContainsString('Disallow: /', file_get_contents(public_path('robots.txt')));
    }
}
