<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use LogicException;

// Instance guards protect application history; query-builder/raw SQL with DB privileges can bypass them.
class CmsPublicationEvent extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $guarded = ['*'];

    protected function casts(): array
    {
        return ['occurred_at' => 'immutable_datetime'];
    }

    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new LogicException('Publication audit is immutable.');
        }

        return parent::save($options);
    }

    public function delete()
    {
        throw new LogicException('Publication audit cannot be deleted.');
    }

    protected function incrementOrDecrement($column, $amount, $extra, $method)
    {
        throw new LogicException('Publication audit is immutable.');
    }

    protected function incrementOrDecrementEach(array $columns, array $extra, string $method)
    {
        throw new LogicException('Publication audit is immutable.');
    }
}
