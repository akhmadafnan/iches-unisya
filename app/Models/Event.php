<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasUuids;

    protected $fillable = ['slug', 'name', 'year', 'timezone', 'starts_at', 'ends_at', 'status'];

    protected function casts(): array
    {
        return ['year' => 'integer', 'starts_at' => 'immutable_datetime', 'ends_at' => 'immutable_datetime'];
    }

    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleAssignment::class);
    }
}
