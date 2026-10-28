<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Institution;
use App\Models\User;

class InstitutionPolicy
{
    public function view(User $user, Institution $institution, ?Event $event = null): bool
    {
        return $event !== null
            && $user->roleAssignments()->active()->forEvent($event)->forInstitution($institution)->exists();
    }
}
