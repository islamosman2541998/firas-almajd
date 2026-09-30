<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use FlushesSiteCache, HasTranslations;

    public const TYPES = ['route', 'page', 'service', 'url', 'none'];

    public const LOCATIONS = ['header'];

    protected $fillable = ['location', 'parent_id', 'title', 'type', 'route_name', 'linkable_type', 'linkable_id', 'url', 'new_tab', 'is_active', 'sort_order'];

    public array $translatable = ['title'];

    protected function casts(): array
    {
        return ['new_tab' => 'boolean', 'is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Stored link value understood by LinkResolver. */
    public function linkValue(): ?string
    {
        return match ($this->type) {
            'route' => 'route:'.$this->route_name,
            'page' => 'page:'.$this->linkable_id,
            'service' => 'service:'.$this->linkable_id,
            'url' => $this->url,
            default => null,
        };
    }
}
