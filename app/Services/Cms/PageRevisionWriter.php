<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Files\PrivateFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class PageRevisionWriter
{
    public function __construct(private PageSnapshotValidator $validator) {}

    public function saveDraft(User $actor, Event $event, CmsPage $page, mixed $snapshot, ?string $workspace): CmsPageRevision
    {
        return DB::transaction(function () use ($actor, $event, $page, $snapshot, $workspace): CmsPageRevision {
            if (! $actor->exists || ! $event->exists || ! $page->exists
                || ! User::whereKey($actor->getKey())->exists() || ! Event::whereKey($event->getKey())->exists()) {
                throw ValidationException::withMessages(['page' => 'Persisted actor, event and page required.']);
            }
            $locked = CmsPage::whereKey($page->getKey())->lockForUpdate()->firstOrFail();
            if ($locked->event_id !== $event->getKey()) {
                throw ValidationException::withMessages(['page' => 'Page does not belong to the supplied event.']);
            }
            Gate::forUser($actor)->authorize('updateDraft', [$locked, $event, $workspace]);
            $snapshot = $this->validateSnapshot($event, $snapshot);
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

    public function validateSnapshot(Event $event, mixed $snapshot): array
    {
        $snapshot = $this->validator->validate($snapshot);
        foreach ($snapshot['sections'] as $section) {
            foreach ($section['media_ids'] as $id) {
                $file = StoredFile::whereKey($id)->lockForUpdate()->first();
                if (! $file || ! $this->isPrivateImage($file, $event)) {
                    throw ValidationException::withMessages(['media_ids' => 'Invalid private CMS image reference for this event.']);
                }
            }
        }

        return $snapshot;
    }

    public function isPrivateImage(StoredFile $file, Event $event): bool
    {
        $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        $extension = pathinfo($file->storage_key, PATHINFO_EXTENSION);

        return $file->event_id === $event->getKey() && $file->kind === 'cms_image'
            && $file->disk === 'local' && $file->visibility === 'private'
            && ($types[$extension] ?? null) === $file->detected_mime && $file->size_bytes > 0
            && $file->size_bytes <= 5 * 1024 * 1024 && preg_match('/^[a-f0-9]{64}$/D', $file->sha256)
            && preg_match('~^files/'.preg_quote($event->getKey(), '~').'/cms_image/[a-f0-9-]{36}\.(jpg|jpeg|png|webp)$~D', $file->storage_key);
    }

    public function imageBytes(StoredFile $file, Event $event, PrivateFiles $files): string
    {
        abort_unless($this->isPrivateImage($file, $event), 404);
        $stream = $files->verifiedStream($file);
        try {
            $bytes = stream_get_contents($stream);
        } finally {
            fclose($stream);
        }
        $image = is_string($bytes) ? @getimagesizefromstring($bytes) : false;
        abort_unless($image && $image['mime'] === $file->detected_mime
            && (new \finfo(FILEINFO_MIME_TYPE))->buffer($bytes) === $file->detected_mime, 404);

        return $bytes;
    }
}
