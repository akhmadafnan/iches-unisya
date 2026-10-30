<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class CmsPageRevision extends Model
{
    use HasUuids;

    public const UPDATED_AT = null;

    protected $guarded = ['*'];

    protected function casts(): array
    {
        return ['version' => 'integer', 'schema_version' => 'integer', 'snapshot' => 'array', 'created_at' => 'immutable_datetime'];
    }

    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new LogicException('CMS revisions are immutable; append a new draft.');
        }

        return parent::save($options);
    }

    public function delete()
    {
        throw new LogicException('CMS revision history cannot be deleted.');
    }

    protected function incrementOrDecrement($column, $amount, $extra, $method)
    {
        throw new LogicException('CMS revision counters are immutable.');
    }

    protected function incrementOrDecrementEach(array $columns, array $extra, string $method)
    {
        throw new LogicException('CMS revision counters are immutable.');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
