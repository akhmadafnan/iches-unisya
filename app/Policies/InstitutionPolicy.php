<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Institution;
use App\Models\User;
use App\Services\Workspaces;

class InstitutionPolicy
{
    public function view(User $user, Institution $institution, ?Event $event = null, ?string $workspace = null): bool
    {
        return $event !== null
            && app(Workspaces::class)->selected($user, $workspace)->forEvent($event)->forInstitution($institution)->exists();
    }
}
