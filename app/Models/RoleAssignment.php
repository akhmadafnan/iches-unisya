<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAssignment extends Model
{
    // Assignment grants/revocations require explicit trusted writes, never request mass assignment.
    protected $guarded = ['*'];

    protected function casts(): array
    {
        return ['assigned_at' => 'immutable_datetime', 'revoked_at' => 'immutable_datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeActive(Builder $query): void
    {
        $query->whereNull('revoked_at');
    }

    public function scopeForUser(Builder $query, User $user): void
    {
        $query->where('user_id', $user->getKey());
    }

    public function scopeForEvent(Builder $query, Event $event): void
    {
        $query->where('event_id', $event->getKey());
    }

    public function scopeForInstitution(Builder $query, Institution $institution): void
    {
        $query->where('institution_id', $institution->getKey());
    }
}
