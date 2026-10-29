<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\StoredFile;
use App\Models\User;
use App\Services\Workspaces;

class StoredFilePolicy
{
    public function create(User $user, Event $event, string $workspace): bool
    {
        return app(Workspaces::class)->selected($user, $workspace)->forEvent($event)->exists();
    }

    public function download(User $user, StoredFile $file, string $workspace): bool
    {
        // Only standalone owner files exist in S0-05. Domain access must be added by real resource policies.
        return $file->owner_user_id === $user->getKey()
            && $file->visibility === 'private'
            && $file->disk === 'local'
            && app(Workspaces::class)->selected($user, $workspace)->where('event_id', $file->event_id)->exists();
    }
}
