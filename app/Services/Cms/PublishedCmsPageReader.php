<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

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
            foreach (['events', 'cms_pages', 'cms_page_revisions'] as $table) {
                abort_unless(Schema::hasTable($table), 404);
            }
            $event = $this->events->newQuery()->where('slug', $eventSlug)->firstOrFail();
            $page = $this->pages->newQuery()->where('event_id', $event->getKey())->where('slug', $pageSlug)->firstOrFail();
            abort_unless($page->event_id === $event->getKey() && $page->published_revision_id, 404);
            $revision = $this->revisions->newQuery()->where('page_id', $page->getKey())
                ->whereKey($page->published_revision_id)->firstOrFail();
            abort_unless($revision->page_id === $page->getKey()
                && $revision->getKey() === $page->published_revision_id && $revision->schema_version === 1, 404);

            $key = CmsPublicationLifecycle::cacheKey($page);
            $cached = Cache::get($key);
            // Checking the fresh pointer also protects against a concurrent publish/restore cache fill.
            if (! is_array($cached) || ($cached['revision_id'] ?? null) !== $revision->getKey()) {
                $cached = ['revision_id' => $revision->getKey(), 'editorial' => $this->presenter->editorial($revision->snapshot)];
                Cache::put($key, $cached, now()->addMinutes(5));
            }

            return $this->presenter->present($cached['editorial'], $event, $page->slug, $locale);
        } catch (QueryException|ValidationException) {
            abort(404);
        }
    }
}
