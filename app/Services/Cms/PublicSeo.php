<?php

namespace App\Services\Cms;

use App\Models\Event;
use App\Models\StoredFile;
use App\Services\Files\PrivateFiles;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicSeo
{
    public const LOCALES = ['en', 'id', 'ar'];

    public function origin(): ?string
    {
        $url = config('app.url');
        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL) || preg_match('/[\s\\\\]/', $url)) {
            return null;
        }
        $parts = parse_url($url);
        if (! $parts || ! in_array($parts['scheme'] ?? null, ['https', 'http'], true)
            || isset($parts['user']) || isset($parts['pass'])
            || isset($parts['query']) || isset($parts['fragment']) || ! in_array($parts['path'] ?? '', ['', '/'], true)) {
            return null;
        }

        return $parts['scheme'].'://'.strtolower($parts['host']).(isset($parts['port']) ? ':'.$parts['port'] : '');
    }

    public function path(string $event, string $page, string $locale): string
    {
        if ($event === config('iches-public.event_slug') && $page === config('iches-public.homepage_slug')) {
            return route('public.home.'.$locale, [], false);
        }

        return route('public.cms.page.'.$locale, ['event' => $event, 'page' => $page], false);
    }

    public function eligible(array $editorial): array
    {
        return array_values(array_filter(self::LOCALES, function ($locale) use ($editorial): bool {
            if (! isset($editorial['locales'][$locale])) {
                return false;
            }
            foreach ($editorial['sections'] as $section) {
                if (! isset($section['locales'][$locale]) || (($section['media'] ?? null) && ! isset($section['media']['locales'][$locale]))) {
                    return false;
                }
            }

            return true;
        }));
    }

    public function present(array $cms, array $editorial, string $locale): array
    {
        $origin = $this->origin();
        $contentLocale = $cms['page']['locale'];
        $paths = [];
        foreach (self::LOCALES as $language) {
            $paths[$language] = $this->path($cms['event']['slug'], $cms['page']['slug'], $language);
        }
        $eligible = $this->eligible($editorial);
        $canonicalLocale = in_array($locale, $eligible, true) ? $locale : (in_array('en', $eligible, true) ? 'en' : null);
        $alternates = [];
        if ($origin) {
            foreach ($eligible as $language) {
                $alternates[$language] = $origin.$paths[$language];
            }
        }

        return [
            'title' => $editorial['seo']['title'][$contentLocale]['value'] ?? $cms['page']['title'],
            'description' => $editorial['seo']['description'][$contentLocale]['value'] ?? null,
            'canonical' => $origin && $canonicalLocale ? $origin.$paths[$canonicalLocale] : null,
            'alternates' => $alternates, 'language_urls' => $paths,
            'og_locale' => ['en' => 'en_US', 'id' => 'id_ID', 'ar' => 'ar_AR'][$contentLocale],
            'image' => null,
        ];
    }

    public function image(array $cms, Event $event, array $editorial): ?string
    {
        $origin = $this->origin();
        if (! $cms['seo']['canonical'] || ! $origin) {
            return null;
        }
        foreach ($cms['sections'] as $section) {
            if (! $section['media']) {
                continue;
            }
            $source = collect($editorial['sections'])->firstWhere('id', $section['id']);
            try {
                $file = app(StoredFile::class)->newQuery()->where('event_id', $event->id)->where('kind', 'cms_image')
                    ->whereKey($source['media']['file_id'])->first();
                if (! $file || $file->id !== $source['media']['file_id']) {
                    return null;
                }
                app(PageRevisionWriter::class)->imageBytes($file, $event, app(PrivateFiles::class));

                return $origin.$section['media']['url'];
            } catch (QueryException) {
                return null;
            } catch (HttpException $exception) {
                if ($exception->getStatusCode() !== 404) {
                    throw $exception;
                }

                return null;
            }
        }

        return null;
    }
}
