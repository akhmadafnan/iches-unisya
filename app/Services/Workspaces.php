<?php

namespace App\Services;

use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Workspaces
{
    // Only these roles have scoped read behavior in this slice. Demo admin is not a grant.
    private const ROLES = ['participant', 'presenter', 'reviewer', 'cohost_pic', 'superadmin'];

    public function grants(User $user): Builder
    {
        return $user->roleAssignments()->active()->whereIn('role_key', self::ROLES)->getQuery();
    }

    public function choices(User $user): array
    {
        return [
            ['key' => 'personal', 'label' => 'Personal'],
            ...$this->grants($user)->with(['event', 'institution'])->orderBy('id')->get()->map(fn (RoleAssignment $grant) => [
                'key' => 'assignment:'.$grant->id,
                'label' => implode(' / ', array_filter([$grant->event->name, $grant->institution?->official_name, $grant->role_key])),
            ])->all(),
        ];
    }

    public function current(Request $request): string
    {
        $key = $request->session()->get('workspace', 'personal');
        if (! is_string($key) || ($key !== 'personal' && ! $this->selected($request->user(), $key)->exists())) {
            $key = 'personal';
        }
        $request->session()->put('workspace', $key);

        return $key;
    }

    public function selected(User $user, ?string $key): Builder
    {
        $query = $this->grants($user);
        if ($key !== null) {
            if (! preg_match('/^assignment:([1-9][0-9]*)$/D', $key, $match)) {
                return $query->whereRaw('1 = 0');
            }
            $query->whereKey($match[1]);
        }

        return $query;
    }
}
