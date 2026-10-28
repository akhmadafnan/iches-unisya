<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Services\Workspaces;

class EventPolicy
{
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
