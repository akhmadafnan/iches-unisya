<?php

namespace App\Services\Cms;

use App\Models\Event;

class PublishedSectionPresenter
{
    public function __construct(private PageSnapshotValidator $validator) {}

    // This locale-independent cache projection deliberately drops all media and unready copy.
    public function editorial(mixed $snapshot): array
    {
        $snapshot = $this->validator->validate($snapshot);
        abort_unless($snapshot['locales']['en']['status'] === 'ready', 404);
        $sections = [];
        foreach ($snapshot['sections'] as $section) {
            if (! $section['visible']) {
                continue;
            }
            $sections[] = [
                'id' => $section['id'], 'component_key' => $section['component_key'],
                'variant' => $section['variant'], 'locales' => $this->ready($section['locales']),
                'bindings' => array_column($section['bindings'], 'field'),
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
            $sections[] = [
                'id' => $section['id'], 'component_key' => $section['component_key'], 'variant' => $section['variant'],
                'content' => $section['locales'][$contentLocale], 'locale' => $contentLocale,
                'fallback' => $contentLocale !== $locale,
                'bindings' => array_intersect_key($facts, array_flip($section['bindings'])),
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
