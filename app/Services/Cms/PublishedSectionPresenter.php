<?php

namespace App\Services\Cms;

use App\Models\Event;

class PublishedSectionPresenter
{
    public function __construct(private PageSnapshotValidator $validator) {}

    // Cache only explicit v2 presentation media and ready translations; legacy media IDs stay private.
    public function editorial(mixed $snapshot): array
    {
        $snapshot = $this->validator->validate($snapshot);
        abort_unless($snapshot['locales']['en']['status'] === 'ready', 404);
        $sections = [];
        foreach ($snapshot['sections'] as $section) {
            if (! $section['visible']) {
                continue;
            }
            $media = $snapshot['schema_version'] === 2 ? $section['media'] : null;
            $sections[] = [
                'id' => $section['id'], 'component_key' => $section['component_key'],
                'variant' => $section['variant'], 'locales' => $this->ready($section['locales']),
                'bindings' => array_column($section['bindings'], 'field'),
                'media' => $media ? ['file_id' => $media['file_id'], 'focal' => $media['focal'], 'locales' => $this->ready($media['locales'])] : null,
            ];
        }

        return ['locales' => $this->ready($snapshot['locales']), 'sections' => $sections];
    }

    public function present(array $editorial, Event $event, string $slug, string $locale): array
    {
        $locale = in_array($locale, ['en', 'id', 'ar'], true) ? $locale : 'en';
        $facts = [
            'name' => $event->name, 'timezone' => $event->timezone,
            'starts_at' => $event->starts_at?->setTimezone($event->timezone)->toIso8601String(),
            'ends_at' => $event->ends_at?->setTimezone($event->timezone)->toIso8601String(),
        ];
        $titleLocale = isset($editorial['locales'][$locale]) ? $locale : 'en';
        $sections = [];
        foreach ($editorial['sections'] as $section) {
            $contentLocale = isset($section['locales'][$locale]) ? $locale : 'en';
            if (! isset($section['locales'][$contentLocale])) {
                continue;
            }
            $media = $section['media'] ?? null;
            $mediaLocale = isset($media['locales'][$locale]) ? $locale : 'en';
            $image = $media && isset($media['locales'][$mediaLocale]) ? [
                'url' => route('public.cms.media', ['event' => $event->slug, 'page' => $slug, 'section' => $section['id'], 'media' => $media['file_id']], false),
                'focal' => $media['focal'], 'locale' => $mediaLocale, 'fallback' => $mediaLocale !== $locale,
                ...$media['locales'][$mediaLocale],
            ] : null;
            $sections[] = [
                'id' => $section['id'], 'component_key' => $section['component_key'], 'variant' => $section['variant'],
                'content' => $section['locales'][$contentLocale], 'locale' => $contentLocale,
                'fallback' => $contentLocale !== $locale,
                'bindings' => array_intersect_key($facts, array_flip($section['bindings'])),
                'media' => $image,
            ];
        }

        return [
            'event' => ['slug' => $event->slug, ...$facts],
            'page' => ['slug' => $slug, 'title' => $editorial['locales'][$titleLocale]['title'],
                'locale' => $titleLocale, 'fallback' => $titleLocale !== $locale],
            'sections' => $sections,
            'catalog' => $this->validator->catalog(),
        ];
    }

    private function ready(array $locales): array
    {
        return array_map(fn ($translation) => $translation['content'],
            array_filter($locales, fn ($translation) => $translation['status'] === 'ready'));
    }
}
