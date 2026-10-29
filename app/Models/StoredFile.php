<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class StoredFile extends Model
{
    use HasUuids;

    protected $guarded = ['*'];

    protected $hidden = ['disk', 'storage_key', 'sha256'];

    protected static function booted(): void
    {
        static::updating(function (): void {
            throw new LogicException('Stored file metadata is immutable. Store a new version instead.');
        });
    }

    protected function casts(): array
    {
        return ['size_bytes' => 'integer'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
