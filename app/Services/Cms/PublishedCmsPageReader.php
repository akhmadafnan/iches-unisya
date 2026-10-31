<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublishedCmsPageReader
{
    public function __construct(
        private PublishedSectionPresenter $presenter,
        private Event $events,
        private CmsPage $pages,
        private CmsPageRevision $revisions,
    ) {}

    public function read(string $eventSlug, string $pageSlug, string $locale): array
    {
        try {
            [$event, $page, $revision] = $this->published($eventSlug, $pageSlug);
            $key = CmsPublicationLifecycle::cacheKey($page);
            $cached = Cache::get($key);
            // Checking the fresh pointer also protects against a concurrent publish/restore cache fill.
            if (! is_array($cached) || ($cached['revision_id'] ?? null) !== $revision->getKey()) {
                $cached = ['revision_id' => $revision->getKey(), 'editorial' => $this->presenter->editorial($revision->snapshot)];
                Cache::put($key, $cached, now()->addMinutes(5));
            }

            $cms = $this->presenter->present($cached['editorial'], $event, $page->slug, $locale);
            $cms['seo']['image'] = app(PublicSeo::class)->image($cms, $event, $cached['editorial']);

            return $cms;
        } catch (QueryException|ValidationException) {
            abort(404);
        }
    }

    public function sitemap(string $eventSlug): array
    {
        try {
            foreach (['events', 'cms_pages', 'cms_page_revisions'] as $table) {
                if (! Schema::hasTable($table)) {
                    return [];
                }
            }
            $event = $this->events->newQuery()->where('slug', $eventSlug)->firstOrFail();
            $pages = $this->pages->newQuery()->where('event_id', $event->id)->whereNotNull('published_revision_id')
                ->orderBy('id')->limit(100)->get(['id', 'event_id', 'slug', 'published_revision_id']);
            if ($pages->isEmpty()) {
                return [];
            }
            $revisions = $this->revisions->newQuery()->whereIn('page_id', $pages->modelKeys())
                ->whereIn('id', $pages->pluck('published_revision_id')->all())->limit(100)->get()->keyBy('id');
            $result = [];
            foreach ($pages as $page) {
                $revision = $revisions->get($page->published_revision_id);
                if ($page->event_id !== $event->id || ! $revision || $revision->page_id !== $page->id
                    || ! in_array($revision->schema_version, [1, 2, 3], true)
                    || ($revision->snapshot['schema_version'] ?? null) !== $revision->schema_version
                    || ! preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $page->slug)) {
                    continue;
                }
                try {
                    $editorial = $this->presenter->editorial($revision->snapshot);
                    $result[] = ['slug' => $page->slug, 'locales' => app(PublicSeo::class)->eligible($editorial)];
                } catch (ValidationException|HttpException) {
                    // Invalid or unready publications never enter the sitemap.
                }
            }

            return $result;
        } catch (QueryException|ModelNotFoundException) {
            return [];
        }
    }

    public function published(string $eventSlug, string $pageSlug): array
    {
        try {
            foreach (['events', 'cms_pages', 'cms_page_revisions'] as $table) {
                abort_unless(Schema::hasTable($table), 404);
            }
            $event = $this->events->newQuery()->where('slug', $eventSlug)->firstOrFail();
            $page = $this->pages->newQuery()->where('event_id', $event->getKey())->where('slug', $pageSlug)->firstOrFail();
            abort_unless($page->event_id === $event->getKey() && $page->published_revision_id, 404);
            $revision = $this->revisions->newQuery()->where('page_id', $page->getKey())
                ->whereKey($page->published_revision_id)->firstOrFail();
            abort_unless($revision->page_id === $page->getKey()
                && $revision->getKey() === $page->published_revision_id && in_array($revision->schema_version, [1, 2, 3], true), 404);

            abort_unless(($revision->snapshot['schema_version'] ?? null) === $revision->schema_version, 404);

            return [$event, $page, $revision];
        } catch (QueryException|ValidationException) {
            abort(404);
        }
    }
}
