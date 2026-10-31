<?php

namespace App\Services\Cms;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;

class PageSnapshotValidator
{
    private const LOCALES = ['en', 'id', 'ar'];

    // New coded renderers can inject additional typed schemas without changing the snapshot envelope.
    public function __construct(private array $registry = [
        'editorial-copy' => ['variants' => ['default', 'wide'], 'fields' => ['body' => 'text']],
        'hero' => ['variants' => ['default', 'split'], 'fields' => ['heading' => 'text', 'body' => 'text']],
        'event-essentials' => ['variants' => ['default'], 'fields' => ['heading' => 'text']],
        'countdown' => ['variants' => ['default'], 'fields' => ['heading' => 'text']],
        'cta' => ['variants' => ['default'], 'fields' => ['label' => 'text', 'href' => 'url']],
    ]) {}

    public function catalog(): array
    {
        return $this->registry;
    }

    public function validate(mixed $snapshot): array
    {
        try {
            $json = is_string($snapshot) ? $snapshot : json_encode($snapshot, JSON_THROW_ON_ERROR, 32);
            $this->require(strlen($json) <= 262144);
            $document = json_decode($json, false, 32, JSON_THROW_ON_ERROR);
            $this->require($document instanceof \stdClass && isset($document->sections) && is_array($document->sections));
            foreach ($document->sections as $section) {
                $this->require($section instanceof \stdClass && isset($section->media_ids, $section->bindings)
                    && is_array($section->media_ids) && is_array($section->bindings));
            }
            $snapshot = json_decode($json, true, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $this->invalid();
        }
        $this->keys($snapshot, ['schema_version', 'locales', 'sections']);
        $this->require(in_array($snapshot['schema_version'], [1, 2], true));
        $this->translations($snapshot['locales'], ['title' => 'text']);
        $this->require(is_array($snapshot['sections']) && array_is_list($snapshot['sections']) && count($snapshot['sections']) <= 100);
        $ids = [];
        foreach ($snapshot['sections'] as $position => $section) {
            $this->keys($section, array_merge(['id', 'component_key', 'variant', 'position', 'visible', 'locales', 'media_ids', 'bindings'],
                $snapshot['schema_version'] === 2 ? ['media'] : []));
            $this->require(is_string($section['id']) && Str::isUuid($section['id']) && ! in_array($section['id'], $ids, true));
            $ids[] = $section['id'];
            $this->require(is_string($section['component_key']) && isset($this->registry[$section['component_key']]));
            $schema = $this->registry[$section['component_key']];
            $this->require(in_array($section['variant'], $schema['variants'], true)
                && $section['position'] === $position && is_bool($section['visible']));
            $this->translations($section['locales'], $schema['fields']);
            $this->require(is_array($section['media_ids']) && array_is_list($section['media_ids']) && count($section['media_ids']) <= 20);
            foreach ($section['media_ids'] as $id) {
                $this->require(is_string($id) && Str::isUuid($id));
            }
            $this->require(count(array_unique($section['media_ids'])) === count($section['media_ids']));
            if ($snapshot['schema_version'] === 2 && $section['media'] !== null) {
                $this->require(in_array($section['component_key'], ['hero', 'editorial-copy'], true));
                $media = $section['media'];
                $this->keys($media, ['file_id', 'focal', 'locales']);
                $this->require(is_string($media['file_id']) && in_array($media['file_id'], $section['media_ids'], true));
                $this->keys($media['focal'], ['x', 'y']);
                foreach ($media['focal'] as $value) {
                    $this->require((is_int($value) || is_float($value)) && is_finite((float) $value) && $value >= 0 && $value <= 100);
                }
                $this->translations($media['locales'], ['alt' => 'text', 'caption' => 'text', 'credit' => 'text'], ['caption', 'credit']);
            }
            $this->require(is_array($section['bindings']) && array_is_list($section['bindings']) && count($section['bindings']) <= 10);
            foreach ($section['bindings'] as $binding) {
                $this->keys($binding, ['source', 'field']);
                // Context resolves Event Master; no editable value, event ID, fee or business state override.
                $this->require($binding['source'] === 'event' && in_array($binding['field'], ['name', 'timezone', 'starts_at', 'ends_at'], true));
            }
            if ($section['component_key'] === 'countdown') {
                $this->require(count($section['bindings']) === 1
                    && $section['bindings'][0]['source'] === 'event'
                    && $section['bindings'][0]['field'] === 'starts_at');
            }
        }

        return $snapshot;
    }

    private function translations(mixed $translations, array $fields, array $optional = []): void
    {
        $this->keys($translations, self::LOCALES);
        foreach ($translations as $translation) {
            $this->keys($translation, ['status', 'content']);
            $this->require(in_array($translation['status'], ['missing', 'draft', 'ready'], true));
            if ($translation['status'] === 'missing') {
                $this->require($translation['content'] === null);

                continue;
            }
            $this->keys($translation['content'], array_keys($fields));
            foreach ($fields as $key => $type) {
                $value = $translation['content'][$key];
                $this->require(is_string($value) && mb_strlen($value) <= 5000
                    && ($translation['status'] !== 'ready' || in_array($key, $optional, true) || trim($value) !== ''));
                $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $this->require(! preg_match('/[<>\x00-\x08\x0b\x0c\x0e-\x1f\x7f]|(?:javascript|vbscript|data)\s*:|expression\s*\(/iu', $decoded));
                $this->require(in_array($type, ['text', 'url'], true));
                if ($type === 'url') {
                    $this->require(strlen($value) <= 2048 && ! preg_match('/[\s\\\\\x22\x27<>]|%0[ad]|%5c/i', $value));
                    $parts = parse_url($value);
                    $this->require($parts !== false && ! isset($parts['user']) && ! isset($parts['pass'])
                        && ((str_starts_with($value, '/') && ! str_starts_with($value, '//'))
                            || (filter_var($value, FILTER_VALIDATE_URL) !== false && ($parts['scheme'] ?? null) === 'https')));
                }
            }
        }
    }

    private function keys(mixed $value, array $keys): void
    {
        $this->require(is_array($value) && count($value) === count($keys) && array_diff($keys, array_keys($value)) === []);
    }

    private function require(bool $valid): void
    {
        if (! $valid) {
            $this->invalid();
        }
    }

    private function invalid(): never
    {
        throw ValidationException::withMessages(['snapshot' => 'Invalid or unsafe CMS snapshot contract (schema versions 1 and 2).']);
    }
}
