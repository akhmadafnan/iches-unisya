<?php

namespace App\Policies;

use App\Models\CmsPage;
use App\Models\Event;
use App\Models\User;
use App\Services\Workspaces;

class CmsPagePolicy
{
    public function create(User $user, Event $event, ?string $workspace): bool
    {
        // Workspaces::selected(null) searches all grants, so reject it before querying.
        if (! $workspace || ! preg_match('/^assignment:[1-9][0-9]*$/D', $workspace)) {
            return false;
        }

        return app(Workspaces::class)->selected($user, $workspace)->forEvent($event)
            ->whereNull('institution_id')->where('role_key', 'superadmin')->lockForUpdate()->first() !== null;
    }

    public function updateDraft(User $user, CmsPage $page, Event $event, ?string $workspace): bool
    {
        return $page->event_id === $event->getKey() && $this->create($user, $event, $workspace);
    }

    public function preview(User $user, CmsPage $page, Event $event, ?string $workspace): bool
    {
        return $this->updateDraft($user, $page, $event, $workspace);
    }

    public function publish(User $user, CmsPage $page, Event $event, ?string $workspace): bool
    {
        return $this->updateDraft($user, $page, $event, $workspace);
    }

    public function restore(User $user, CmsPage $page, Event $event, ?string $workspace): bool
    {
        return $this->updateDraft($user, $page, $event, $workspace);
    }
}
