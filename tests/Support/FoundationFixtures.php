<?php

namespace Tests\Support;

use App\Models\Event;
use App\Models\Institution;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Support\Str;

class FoundationFixtures
{
    public static function create(): array
    {
        $suffix = Str::uuid7()->toString();

        return [
            'user' => User::create(['name' => 'Synthetic Member', 'email' => "  MEMBER.$suffix@EXAMPLE.INVALID  "]),
            'otherUser' => User::create(['name' => 'Synthetic Other Member', 'email' => "other.$suffix@example.invalid"]),
            'institution' => Institution::create(['official_name' => 'Synthetic Institution A', 'country_code' => 'ID']),
            'otherInstitution' => Institution::create(['official_name' => 'Synthetic Institution B']),
            'event' => Event::create(['slug' => "synthetic-a-$suffix", 'name' => 'Synthetic Event A', 'year' => 2099, 'timezone' => 'Asia/Jakarta']),
            'otherEvent' => Event::create(['slug' => "synthetic-b-$suffix", 'name' => 'Synthetic Event B', 'year' => 2099, 'timezone' => 'UTC']),
        ];
    }

    public static function assign(User $user, Event $event, ?Institution $institution = null, string $role = 'participant'): RoleAssignment
    {
        return RoleAssignment::forceCreate([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'institution_id' => $institution?->id,
            'role_key' => $role,
            'assigned_by' => $user->id,
        ]);
    }
}
