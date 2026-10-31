<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Services\Workspaces;

class EventPolicy
{
    public function manageSchedule(User $user, Event $event, ?string $workspace = null): bool
    {
        return is_string($workspace) && preg_match('/^assignment:[1-9][0-9]*$/D', $workspace)
            && app(Workspaces::class)->selected($user, $workspace)->forEvent($event)
                ->whereNull('institution_id')->where('role_key', 'superadmin')->exists();
    }

    public function view(User $user, Event $event, ?string $workspace = null): bool
    {
        return app(Workspaces::class)->selected($user, $workspace)->forEvent($event)->exists();
    }

    public function manageBankSettings(User $user, Event $event, ?string $workspace = null): bool
    {
        return app(Workspaces::class)->selected($user, $workspace)->forEvent($event)
            ->whereNull('institution_id')->where('role_key', 'superadmin')->exists();
    }
}
