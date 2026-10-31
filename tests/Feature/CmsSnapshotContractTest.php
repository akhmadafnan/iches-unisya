<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Services\Cms\PageSnapshotValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CmsSnapshotContractTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::shouldReceive('connection')->never();
    }

    private function snapshot(): array
    {
        $missing = ['status' => 'missing', 'content' => null];

        return [
            'schema_version' => 1,
            'locales' => [
                'en' => ['status' => 'ready', 'content' => ['title' => 'Conference information']],
                'id' => ['status' => 'draft', 'content' => ['title' => 'Informasi konferensi']],
                'ar' => $missing,
            ],
            'sections' => [[
                'id' => (string) Str::uuid7(), 'component_key' => 'editorial-copy', 'variant' => 'default',
                'position' => 0, 'visible' => true,
                'locales' => ['en' => ['status' => 'ready', 'content' => ['body' => 'Editorial copy']], 'id' => $missing, 'ar' => $missing],
                'media_ids' => [], 'bindings' => [],
            ]],
        ];
    }

    public function test_typed_snapshot_round_trips_without_inventing_missing_translations(): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['media_ids'] = [(string) Str::uuid7()];
        $snapshot['sections'][0]['bindings'] = [['source' => 'event', 'field' => 'starts_at']];
        $validator = new PageSnapshotValidator;
        $this->assertSame($snapshot, $validator->validate($snapshot));
        $this->assertSame($snapshot, $validator->validate(json_encode($snapshot)));
        $this->assertNull($snapshot['locales']['ar']['content']);
    }

    public function test_registry_accepts_a_new_coded_component_schema_and_arabic(): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['component_key'] = 'notice';
        $snapshot['sections'][0]['variant'] = 'compact';
        $snapshot['sections'][0]['locales']['ar'] = ['status' => 'ready', 'content' => ['body' => 'معلومات المؤتمر']];
        $validator = new PageSnapshotValidator(['notice' => ['variants' => ['compact'], 'fields' => ['body' => 'text']]]);
        $this->assertSame($snapshot, $validator->validate($snapshot));
    }

    public function test_hidden_and_reordered_sections_and_empty_pages_are_valid(): void
    {
        $snapshot = $this->snapshot();
        $next = $snapshot['sections'][0];
        $next['id'] = (string) Str::uuid7();
        $next['position'] = 1;
        $next['visible'] = false;
        $snapshot['sections'][] = $next;
        $this->assertSame($snapshot, (new PageSnapshotValidator)->validate($snapshot));
        $snapshot['sections'] = [];
        $this->assertSame($snapshot, (new PageSnapshotValidator)->validate($snapshot));
    }

    public static function invalidCases(): iterable
    {
        yield 'future schema' => ['schema_version', 4];
        yield 'string schema' => ['schema_version', '1'];
        yield 'unknown locale' => ['locales.fr', ['status' => 'missing', 'content' => null]];
        yield 'bad status' => ['locales.en.status', 'published'];
        yield 'missing with copy' => ['locales.en.status', 'missing'];
        yield 'ready empty title' => ['locales.en.content.title', ''];
        yield 'html' => ['locales.en.content.title', '<script>alert(1)</script>'];
        yield 'encoded html' => ['locales.en.content.title', '&lt;img src=x onerror=alert(1)&gt;'];
        yield 'script URL in text' => ['locales.en.content.title', 'javascript:alert(1)'];
        yield 'control' => ['locales.en.content.title', "bad\x00text"];
        yield 'large text' => ['locales.en.content.title', str_repeat('a', 5001)];
        yield 'array instead of text' => ['locales.en.content.title', []];
        yield 'raw CSS' => ['sections.0.css', 'body { display:none }'];
        yield 'raw HTML field' => ['sections.0.html', '<iframe src=x>'];
        yield 'unknown block' => ['sections.0.component_key', 'arbitrary-html'];
        yield 'malformed key' => ['sections.0.component_key', []];
        yield 'unknown variant' => ['sections.0.variant', 'custom-css'];
        yield 'wrong order' => ['sections.0.position', 1];
        yield 'string order' => ['sections.0.position', '0'];
        yield 'string visibility' => ['sections.0.visible', 'true'];
        yield 'bad section ID' => ['sections.0.id', 'section'];
        yield 'URL instead of media ID' => ['sections.0.media_ids', ['https://example.test/private.png']];
        yield 'non-list media' => ['sections.0.media_ids', ['file' => 'id']];
        yield 'unknown domain' => ['sections.0.bindings', [['source' => 'finance', 'field' => 'verified']]];
        yield 'invented event value' => ['sections.0.bindings', [['source' => 'event', 'field' => 'starts_at', 'value' => '2027-01-05T00:00:00+07:00']]];
        yield 'unknown official field' => ['sections.0.bindings', [['source' => 'event', 'field' => 'fee']]];
        yield 'foreign event override' => ['sections.0.bindings', [['source' => 'event', 'field' => 'starts_at', 'event_id' => 'other']]];
        yield 'null sections' => ['sections', null];
        yield 'non-list sections' => ['sections', ['bad' => []]];
    }

    #[DataProvider('invalidCases')]
    public function test_invalid_contract_is_rejected(string $path, mixed $value): void
    {
        $snapshot = $this->snapshot();
        data_set($snapshot, $path, $value);
        $this->expectException(ValidationException::class);
        (new PageSnapshotValidator)->validate($snapshot);
    }

    public static function malformedJson(): iterable
    {
        foreach (['{', 'null', '[]', '"text"', '42', '{"schema_version":1,"schema_version":2}', str_repeat(' ', 262145), str_repeat('[', 40).'0'.str_repeat(']', 40)] as $value) {
            yield [$value];
        }
    }

    #[DataProvider('malformedJson')]
    public function test_malformed_or_oversized_json_is_rejected(string $value): void
    {
        $this->expectException(ValidationException::class);
        (new PageSnapshotValidator)->validate($value);
    }

    public function test_required_locales_duplicate_sections_and_large_lists_are_rejected(): void
    {
        $base = $this->snapshot();
        $missing = $base;
        unset($missing['locales']['ar']);
        $duplicate = $base;
        $duplicate['sections'][] = $duplicate['sections'][0];
        $many = $base;
        $many['sections'] = array_fill(0, 101, $base['sections'][0]);
        foreach ([$missing, $duplicate, $many] as $snapshot) {
            try {
                (new PageSnapshotValidator)->validate($snapshot);
                $this->fail('Invalid snapshot accepted.');
            } catch (ValidationException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_json_objects_cannot_masquerade_as_lists(): void
    {
        $snapshot = $this->snapshot();
        $badSections = $snapshot;
        $badSections['sections'] = (object) [];
        $badMedia = $snapshot;
        $badMedia['sections'][0]['media_ids'] = (object) [];
        $badBindings = $snapshot;
        $badBindings['sections'][0]['bindings'] = (object) [];
        foreach ([$badSections, $badMedia, $badBindings] as $invalid) {
            try {
                (new PageSnapshotValidator)->validate(json_encode($invalid));
                $this->fail('Object accepted in place of a list.');
            } catch (ValidationException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public static function urls(): iterable
    {
        foreach (['https://example.test/info', '/call-for-papers', '/info?lang=ar#top'] as $url) {
            yield $url => [$url, true];
        }
        foreach (['javascript:alert(1)', 'data:text/html,x', 'http://example.test', '//example.test', '/\\example.test', 'https://user:pass@example.test', '/bad%0d%0aLocation:x', 'https://example.test/ bad', 'https://example.test/"onmouseover="x', 'ftp://example.test'] as $url) {
            yield $url => [$url, false];
        }
    }

    #[DataProvider('urls')]
    public function test_urls_are_typed_and_safe(string $url, bool $valid): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['component_key'] = 'cta';
        $snapshot['sections'][0]['locales']['en']['content'] = ['label' => 'Read more', 'href' => $url];
        if (! $valid) {
            $this->expectException(ValidationException::class);
        }
        $this->assertSame($snapshot, (new PageSnapshotValidator)->validate($snapshot));
    }

    public function test_countdown_requires_event_master_binding_with_no_fallback_hour(): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['component_key'] = 'countdown';
        $snapshot['sections'][0]['locales']['en']['content'] = ['heading' => 'Conference countdown'];
        $snapshot['sections'][0]['bindings'] = [['source' => 'event', 'field' => 'starts_at']];
        $this->assertSame($snapshot, (new PageSnapshotValidator)->validate($snapshot));
        $snapshot['sections'][0]['bindings'] = [];
        $this->expectException(ValidationException::class);
        (new PageSnapshotValidator)->validate($snapshot);
    }

    public static function validCountdownBindings(): iterable
    {
        foreach ([
            'source first' => ['source' => 'event', 'field' => 'starts_at'],
            'field first' => ['field' => 'starts_at', 'source' => 'event'],
        ] as $name => $binding) {
            foreach ([false, true] as $json) {
                yield $name.($json ? ' JSON' : ' array') => [[$binding], $json];
            }
        }
    }

    #[DataProvider('validCountdownBindings')]
    public function test_countdown_accepts_either_object_key_order_without_changing_snapshot(array $bindings, bool $json): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['component_key'] = 'countdown';
        $snapshot['sections'][0]['locales']['en']['content'] = ['heading' => 'Conference countdown'];
        $snapshot['sections'][0]['bindings'] = $bindings;
        $validated = (new PageSnapshotValidator)->validate($json ? json_encode($snapshot, JSON_THROW_ON_ERROR) : $snapshot);
        $this->assertSame($snapshot, $validated);
        $this->assertSame('event', $validated['sections'][0]['bindings'][0]['source']);
        $this->assertSame('starts_at', $validated['sections'][0]['bindings'][0]['field']);
    }

    public static function invalidCountdownBindings(): iterable
    {
        $binding = ['source' => 'event', 'field' => 'starts_at'];
        $cases = [
            'no binding' => [],
            'two bindings' => [$binding, $binding],
            'wrong source' => [['source' => 'finance', 'field' => 'starts_at']],
            'wrong field' => [['source' => 'event', 'field' => 'ends_at']],
            'missing source' => [['field' => 'starts_at']],
            'missing field' => [['source' => 'event']],
            'positional binding' => [[$binding['source'], $binding['field']]],
            'injected value' => [$binding + ['value' => '2027-01-05T00:00:00+07:00']],
            'injected event ID' => [$binding + ['event_id' => 'other']],
            'integer source' => [['source' => 1, 'field' => 'starts_at']],
            'boolean source' => [['source' => true, 'field' => 'starts_at']],
            'null source' => [['source' => null, 'field' => 'starts_at']],
            'array source' => [['source' => ['event'], 'field' => 'starts_at']],
            'integer field' => [['source' => 'event', 'field' => 1]],
            'boolean field' => [['source' => 'event', 'field' => true]],
            'null field' => [['source' => 'event', 'field' => null]],
            'array field' => [['source' => 'event', 'field' => ['starts_at']]],
            'scalar binding' => ['event.starts_at'],
            'null binding' => [null],
            'unwrapped binding' => $binding,
            'non-list key' => ['binding' => $binding],
            'nonzero list start' => [1 => $binding],
            'empty object list' => (object) [],
            'numeric object list' => (object) [$binding],
            'null list' => null,
            'string list' => 'event.starts_at',
        ];
        foreach ($cases as $name => $bindings) {
            foreach ([false, true] as $json) {
                yield $name.($json ? ' JSON' : ' array') => [$bindings, $json];
            }
        }
    }

    #[DataProvider('invalidCountdownBindings')]
    public function test_countdown_rejects_invalid_values_and_ordered_list_structures(mixed $bindings, bool $json): void
    {
        $snapshot = $this->snapshot();
        $snapshot['sections'][0]['component_key'] = 'countdown';
        $snapshot['sections'][0]['locales']['en']['content'] = ['heading' => 'Conference countdown'];
        $snapshot['sections'][0]['bindings'] = $bindings;
        $this->expectException(ValidationException::class);
        (new PageSnapshotValidator)->validate($json ? json_encode($snapshot, JSON_THROW_ON_ERROR) : $snapshot);
    }

    public function test_v2_media_is_strict_and_v1_snapshot_remains_unchanged(): void
    {
        $validator = new PageSnapshotValidator;
        $v1 = $this->snapshot();
        $this->assertSame($v1, $validator->validate($v1));
        $v2 = CmsMediaSecurityTest::mediaSnapshot((string) Str::uuid7());
        $this->assertSame($v2, $validator->validate($v2));
        $this->assertSame($v2, $validator->validate(json_encode($v2)));
        $this->assertSame($v1, $validator->validate($v1));
        foreach ([
            'sections.0.media.url' => 'https://remote.test/image.png',
            'sections.0.media.file_id' => (string) Str::uuid7(),
            'sections.0.media.focal.x' => -1,
            'sections.0.media.focal.y' => 101,
            'sections.0.media.focal.css' => 'background:red',
            'sections.0.media.focal' => ['x' => '50', 'y' => 50],
            'sections.0.media.locales.en.content.alt' => '',
            'sections.0.media.locales.en.content.caption' => '<script>bad</script>',
            'sections.0.media.locales.en.content.credit' => 'javascript:bad',
            'sections.0.media.locales.en.content.extra' => 'unexpected',
            'sections.0.media.locales.fr' => ['status' => 'missing', 'content' => null],
            'sections.0.component_key' => 'countdown',
        ] as $path => $value) {
            $changed = $v2;
            data_set($changed, $path, $value);
            try {
                $validator->validate($changed);
                $this->fail('Unsafe v2 media accepted: '.$path);
            } catch (ValidationException) {
                $this->addToAssertionCount(1);
            }
        }
        $v1['sections'][0]['media'] = null;
        $this->expectException(ValidationException::class);
        $validator->validate($v1);
    }

    public function test_v3_seo_is_strict_bounded_and_backward_compatible(): void
    {
        $validator = new PageSnapshotValidator;
        foreach ([$this->snapshot(), CmsMediaSecurityTest::mediaSnapshot((string) Str::uuid7()), PublicSeoLocalizationTest::snapshot()] as $snapshot) {
            $this->assertSame($snapshot, $validator->validate($snapshot));
            $this->assertSame($snapshot, $validator->validate(json_encode($snapshot)));
        }
        $v3 = PublicSeoLocalizationTest::snapshot();
        $v3['seo']['title']['id'] = ['status' => 'draft', 'content' => ['value' => '']];
        $this->assertSame($v3, $validator->validate($v3));
        foreach ([
            ['seo.canonical', 'https://attacker.invalid'],
            ['seo.title.en.content.value', str_repeat('a', 161)],
            ['seo.description.en.content.value', str_repeat('a', 321)],
            ['seo.title.en.content.value', '<script>bad</script>'],
            ['seo.description.en.content.value', '&lt;img src=x&gt;'],
            ['seo.title.en.content.value', 'javascript:bad'],
            ['seo.title.en.content.extra', 'unexpected'],
            ['seo.title.en.content.value', ''],
            ['seo.description.ar', ['status' => 'missing', 'content' => ['value' => 'invented']]],
        ] as [$path, $value]) {
            $changed = $v3;
            data_set($changed, $path, $value);
            try {
                $validator->validate($changed);
                $this->fail('Unsafe SEO accepted: '.$path);
            } catch (ValidationException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public static function slugs(): iterable
    {
        foreach (['about', 'call-for-papers', 'page-2'] as $slug) {
            yield [$slug, true];
        }
        foreach (['', 'About', ' two ', '../auth', 'a/b', '-page', 'page-', 'a--b', 'login', 'auth', 'files', 'me', 'workspace', 'locale', 'dashboard', 'logout', 'dev', 'admin', 'storage', 'home', 'up', str_repeat('a', 121)] as $slug) {
            yield [$slug, false];
        }
    }

    #[DataProvider('slugs')]
    public function test_slug_rejects_current_route_collisions(string $slug, bool $valid): void
    {
        if (! $valid) {
            $this->expectException(ValidationException::class);
        }
        CmsPage::validateSlug($slug);
        $this->addToAssertionCount(1);
    }

    public function test_uuidv7_and_immutable_revision_guards_work_without_database(): void
    {
        $this->assertSame('7', (new CmsPage)->newUniqueId()[14]);
        $revision = new CmsPageRevision;
        $this->assertSame('7', $revision->newUniqueId()[14]);
        $revision->exists = true;
        foreach ([fn () => $revision->save(), fn () => $revision->saveQuietly(), fn () => $revision->delete(), fn () => $revision->deleteQuietly(), fn () => $revision->increment('version'), fn () => $revision->decrementQuietly('version'), fn () => $revision->incrementEach(['version' => 1])] as $mutate) {
            try {
                $mutate();
                $this->fail('Revision mutation accepted.');
            } catch (LogicException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_page_layer_cannot_publish_or_move_an_existing_page(): void
    {
        foreach (['event_id', 'published_revision_id', 'id'] as $attribute) {
            $page = (new CmsPage)->forceFill(['id' => (string) Str::uuid7(), 'slug' => 'about', 'event_id' => (string) Str::uuid7()]);
            $page->exists = true;
            $page->syncOriginal();
            $page->$attribute = (string) Str::uuid7();
            try {
                $page->saveQuietly();
                $this->fail('Protected page mutation accepted.');
            } catch (LogicException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
