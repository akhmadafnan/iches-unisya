<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\CmsPublicationEvent;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class CmsPublicationLifecycle
{
    public function __construct(private PageRevisionWriter $writer) {}

    // Future public renderers must use this key with persisted event/page identities.
    public static function cacheKey(CmsPage $page): string
    {
        return 'cms:published:'.$page->event_id.':'.$page->getKey();
    }

    public function publish(User $actor, Event $event, CmsPage $page, ?string $workspace, string $draftRevisionId): CmsPageRevision
    {
        return $this->transition($actor, $event, $page, $workspace, $draftRevisionId, 'publish');
    }

    public function restore(User $actor, Event $event, CmsPage $page, ?string $workspace, string $revisionId): CmsPageRevision
    {
        return $this->transition($actor, $event, $page, $workspace, $revisionId, 'restore');
    }

    private function transition(User $actor, Event $event, CmsPage $page, ?string $workspace, string $revisionId, string $action): CmsPageRevision
    {
        return DB::transaction(function () use ($actor, $event, $page, $workspace, $revisionId, $action): CmsPageRevision {
            $event = Event::whereKey($event->getKey())->firstOrFail();
            $actor = User::whereKey($actor->getKey())->firstOrFail();
            $locked = CmsPage::where('event_id', $event->getKey())->whereKey($page->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($actor)->authorize($action, [$locked, $event, $workspace]);
            if ($action === 'publish') {
                abort_if(! $locked->draft_revision_id || $locked->draft_revision_id !== $revisionId, 409, 'Draft missing or changed; reload before publishing.');
            } else {
                abort_unless($locked->published_revision_id, 409, 'No publication to restore.');
            }
            $revision = $locked->revisions()->whereKey($revisionId)->firstOrFail();
            abort_if($locked->published_revision_id === $revision->id, 409, 'Revision is already published.');
            $snapshot = $this->writer->validateSnapshot($event, $revision->snapshot);
            $this->writer->validatePublicationMedia($snapshot);
            if ($snapshot['locales']['en']['status'] !== 'ready' || trim($snapshot['locales']['en']['content']['title'] ?? '') === '') {
                throw ValidationException::withMessages(['snapshot' => 'Publication requires a ready, nonempty EN title.']);
            }

            // Trusted publication boundary: page and grant are locked and authorized above.
            DB::table('cms_pages')->where('id', $locked->id)->where('event_id', $event->id)
                ->update(['published_revision_id' => $revision->id, 'updated_at' => now()]);
            $audit = new CmsPublicationEvent;
            $audit->forceFill([
                'page_id' => $locked->id, 'actor_user_id' => $actor->id,
                'from_revision_id' => $locked->published_revision_id, 'to_revision_id' => $revision->id,
                'action' => $action, 'occurred_at' => now(),
            ])->saveOrFail();
            $key = self::cacheKey($locked);
            DB::afterCommit(function () use ($key): void {
                try {
                    Cache::forget($key);
                } catch (Throwable $exception) {
                    report($exception);
                    throw new HttpException(503, 'Publication committed, but cache invalidation failed. Reload publication state before retrying.');
                }
            });

            return $revision;
        }, 3);
    }
}
