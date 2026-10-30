<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use LogicException;

class CmsPage extends Model
{
    use HasUuids;

    protected $fillable = ['event_id', 'slug'];

    public static function validateSlug(mixed $slug): void
    {
        $reserved = ['admin', 'api', 'backoffice', 'cms', 'storage', 'build', 'assets', 'up'];
        foreach (Route::getRoutes() as $route) {
            $reserved[] = explode('/', $route->uri())[0];
            $reserved[] = $route->getName();
        }
        if (! is_string($slug) || strlen($slug) > 120
            || ! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug)
            || in_array($slug, $reserved, true)) {
            throw ValidationException::withMessages(['slug' => 'Use a non-reserved lowercase page slug.']);
        }
    }

    public function save(array $options = [])
    {
        self::validateSlug($this->slug);
        if ((! $this->exists && ($this->draft_revision_id !== null || $this->published_revision_id !== null))
            || ($this->exists && $this->isDirty(['id', 'event_id', 'published_revision_id']))) {
            throw new LogicException('Page identity and publication cannot be changed by the draft layer.');
        }
        if ($this->draft_revision_id !== null && ! $this->revisions()->whereKey($this->draft_revision_id)->exists()) {
            throw new LogicException('Draft revision must belong to this page.');
        }

        return parent::save($options);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(CmsPageRevision::class, 'page_id');
    }

    public function draftRevision(): BelongsTo
    {
        return $this->belongsTo(CmsPageRevision::class, 'draft_revision_id');
    }

    public function publishedRevision(): BelongsTo
    {
        return $this->belongsTo(CmsPageRevision::class, 'published_revision_id');
    }
}
