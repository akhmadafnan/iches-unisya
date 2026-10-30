<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

// Trusted internal callers only. S1-02 must authorize actor/event before any HTTP integration.
class PageRevisionWriter
{
    public function __construct(private PageSnapshotValidator $validator) {}

    public function saveDraft(User $actor, Event $event, CmsPage $page, mixed $snapshot): CmsPageRevision
    {
        $snapshot = $this->validator->validate($snapshot);

        return DB::transaction(function () use ($actor, $event, $page, $snapshot): CmsPageRevision {
            if (! $actor->exists || ! $event->exists || ! $page->exists
                || ! User::whereKey($actor->getKey())->exists() || ! Event::whereKey($event->getKey())->exists()) {
                throw ValidationException::withMessages(['page' => 'Persisted actor, event and page required.']);
            }
            $locked = CmsPage::whereKey($page->getKey())->lockForUpdate()->firstOrFail();
            if ($locked->event_id !== $event->getKey()) {
                throw ValidationException::withMessages(['page' => 'Page does not belong to the supplied event.']);
            }
            foreach ($snapshot['sections'] as $section) {
                foreach ($section['media_ids'] as $id) {
                    $file = StoredFile::whereKey($id)->lockForUpdate()->first();
                    $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
                    $extension = $file ? pathinfo($file->storage_key, PATHINFO_EXTENSION) : '';
                    if (! $file || $file->event_id !== $locked->event_id || $file->kind !== 'cms_image'
                        || $file->disk !== 'local' || $file->visibility !== 'private'
                        || ($types[$extension] ?? null) !== $file->detected_mime || $file->size_bytes <= 0
                        || $file->size_bytes > 5 * 1024 * 1024 || ! preg_match('/^[a-f0-9]{64}$/D', $file->sha256)
                        || ! preg_match('~^files/'.preg_quote($locked->event_id, '~').'/cms_image/[a-f0-9-]{36}\.(jpg|jpeg|png|webp)$~D', $file->storage_key)) {
                        throw ValidationException::withMessages(['media_ids' => 'Invalid private CMS image reference for this event.']);
                    }
                }
            }
            // A locking current read avoids a stale MAX from MySQL REPEATABLE READ snapshots.
            $latest = $locked->revisions()->orderByDesc('version')->lockForUpdate()->first();
            $revision = new CmsPageRevision;
            $revision->forceFill([
                'page_id' => $locked->getKey(),
                'version' => ($latest?->version ?? 0) + 1,
                'created_by_user_id' => $actor->getKey(),
                'schema_version' => $snapshot['schema_version'],
                'snapshot' => $snapshot,
            ])->saveOrFail();
            $locked->draft_revision_id = $revision->getKey();
            $locked->saveOrFail();

            return $revision;
        }, 3);
    }
}
