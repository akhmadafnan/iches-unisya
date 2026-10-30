<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\Cms\PageSnapshotValidator;
use App\Services\Cms\PublishedSectionPresenter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublishedSectionPresenterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::shouldReceive('connection')->never();
    }

    public static function snapshot(): array
    {
        $missing = ['status' => 'missing', 'content' => null];
        $sections = [];
        foreach ((new PageSnapshotValidator)->catalog() as $key => $schema) {
            foreach ($schema['variants'] as $variant) {
                $content = array_map(fn ($type) => $type === 'url' ? '/information' : 'Published '.$key.' '.$variant, $schema['fields']);
                $sections[] = [
                    'id' => (string) Str::uuid7(), 'component_key' => $key, 'variant' => $variant,
                    'position' => count($sections), 'visible' => true,
                    'locales' => ['en' => ['status' => 'ready', 'content' => $content],
                        'id' => ['status' => 'draft', 'content' => array_map(fn ($type) => $type === 'url' ? '/draft-private' : 'SECRET DRAFT', $schema['fields'])], 'ar' => $missing],
                    'media_ids' => [(string) Str::uuid7()],
                    'bindings' => $key === 'countdown' ? [['source' => 'event', 'field' => 'starts_at']] : [],
                ];
            }
        }

        return ['schema_version' => 1, 'locales' => [
            'en' => ['status' => 'ready', 'content' => ['title' => 'Published page']],
            'id' => ['status' => 'draft', 'content' => ['title' => 'SECRET DRAFT']], 'ar' => $missing,
        ], 'sections' => $sections];
    }

    public static function event(): Event
    {
        return (new Event)->setDateFormat('Y-m-d H:i:s')->forceFill(['id' => (string) Str::uuid7(), 'slug' => 'synthetic-event',
            'name' => 'Synthetic event', 'timezone' => 'Asia/Jakarta', 'starts_at' => null, 'ends_at' => null]);
    }

    public static function locales(): iterable
    {
        foreach (['en', 'id', 'ar'] as $locale) {
            yield $locale => [$locale];
        }
    }

    #[DataProvider('locales')]
    public function test_ready_only_with_explicit_english_fallback_and_no_media(string $locale): void
    {
        $presenter = app(PublishedSectionPresenter::class);
        $cached = $presenter->editorial(self::snapshot());
        $result = $presenter->present($cached, self::event(), 'about', $locale);
        $this->assertSame('Published page', $result['page']['title']);
        $this->assertSame($locale !== 'en', $result['page']['fallback']);
        $this->assertCount(7, $result['sections']);
        $this->assertSame((new PageSnapshotValidator)->catalog(), $result['catalog']);
        foreach ($result['sections'] as $section) {
            $this->assertSame('en', $section['locale']);
            $this->assertSame($locale !== 'en', $section['fallback']);
            $this->assertArrayNotHasKey('media_ids', $section);
        }
        foreach ([$cached, $result] as $value) {
            $this->assertStringNotContainsString('SECRET DRAFT', json_encode($value));
            $this->assertStringNotContainsString('media_ids', json_encode($value));
        }
    }

    #[DataProvider('locales')]
    public function test_selected_ready_translation_is_used_and_never_automatically_translated(string $locale): void
    {
        $snapshot = self::snapshot();
        $title = ['en' => 'English title', 'id' => 'Judul siap', 'ar' => 'عنوان جاهز'][$locale];
        $snapshot['locales'][$locale] = ['status' => 'ready', 'content' => ['title' => $title]];
        $snapshot['sections'][0]['locales'][$locale] = ['status' => 'ready', 'content' => ['body' => $title]];
        $presenter = app(PublishedSectionPresenter::class);
        $result = $presenter->present($presenter->editorial($snapshot), self::event(), 'about', $locale);
        $this->assertSame($title, $result['page']['title']);
        $this->assertFalse($result['page']['fallback']);
        $this->assertSame($title, $result['sections'][0]['content']['body']);
        $this->assertSame($locale, $result['sections'][0]['locale']);
        $this->assertFalse($result['sections'][0]['fallback']);
    }

    public function test_order_duplicates_hidden_and_unready_sections(): void
    {
        $snapshot = self::snapshot();
        $snapshot['sections'][0]['visible'] = false;
        $snapshot['sections'][2]['locales']['en']['status'] = 'draft';
        $presenter = app(PublishedSectionPresenter::class);
        $cached = $presenter->editorial($snapshot);
        $result = $presenter->present($cached, self::event(), 'about', 'ar');
        $this->assertSame(array_column(array_values(array_filter($snapshot['sections'], fn ($section) => $section['visible'] && $section['locales']['en']['status'] === 'ready')), 'id'), array_column($result['sections'], 'id'));
        $this->assertCount(5, $result['sections']);
        $this->assertSame('hero', $result['sections'][1]['component_key']);
        $this->assertSame('split', $result['sections'][1]['variant']);
    }

    public function test_bindings_are_fresh_authoritative_and_offset_aware(): void
    {
        $snapshot = self::snapshot();
        $snapshot['sections'][0]['bindings'] = array_map(fn ($field) => ['source' => 'event', 'field' => $field], ['name', 'timezone', 'starts_at', 'ends_at']);
        $presenter = app(PublishedSectionPresenter::class);
        $cached = $presenter->editorial($snapshot);
        $event = self::event();
        $this->assertNull($presenter->present($cached, $event, 'about', 'en')['sections'][0]['bindings']['starts_at']);
        // Synthetic time only: not an official conference start hour.
        $event->starts_at = '2027-01-05 02:15:00';
        $event->ends_at = '2027-01-05 04:15:00';
        $event->name = 'Updated event';
        $result = $presenter->present($cached, $event, 'about', 'en');
        $this->assertSame('2027-01-05T09:15:00+07:00', $result['sections'][0]['bindings']['starts_at']);
        $this->assertSame('2027-01-05T11:15:00+07:00', $result['event']['ends_at']);
        $this->assertSame('Updated event', $result['sections'][0]['bindings']['name']);
        $this->assertArrayNotHasKey('name', $result['sections'][1]['bindings']);
        $this->assertStringNotContainsString('Updated event', json_encode($cached));
    }

    public static function unsafe(): iterable
    {
        yield 'unknown renderer' => ['sections.0.component_key', 'unknown'];
        yield 'unknown variant' => ['sections.0.variant', 'arbitrary'];
        yield 'future schema' => ['schema_version', 2];
        yield 'html' => ['sections.0.locales.en.content.body', '<script>alert(1)</script>'];
        yield 'unapproved binding' => ['sections.0.bindings', [['source' => 'finance', 'field' => 'fee']]];
        foreach (['javascript:alert(1)', '//evil.test', 'http://evil.test', '/bad%0aevil', 'https://u:p@evil.test'] as $href) {
            yield $href => ['sections.6.locales.en.content.href', $href];
        }
    }

    #[DataProvider('unsafe')]
    public function test_rejects_unsafe_snapshot_before_projection(string $path, mixed $value): void
    {
        $snapshot = self::snapshot();
        data_set($snapshot, $path, $value);
        $this->expectException(ValidationException::class);
        app(PublishedSectionPresenter::class)->editorial($snapshot);
    }
}
