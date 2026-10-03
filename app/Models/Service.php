<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasMedia;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use FlushesSiteCache, HasMedia, HasSeo, HasTranslations, Sortable;

    protected $fillable = ['slug', 'title', 'short_description', 'image', 'scope_items', 'prep_items', 'show_on_home', 'is_active', 'sort_order'];

    public array $translatable = ['title', 'short_description'];

    protected function casts(): array
    {
        return [
            'scope_items' => 'array',
            'prep_items' => 'array',
            'show_on_home' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_related', 'service_id', 'related_id')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function url(?string $locale = null): string
    {
        return lroute('services.show', $this->slug, $locale);
    }

    /** Translated list items (scope_items / prep_items) for the current locale. */
    public function items(string $field): array
    {
        return collect($this->{$field} ?? [])->map(fn ($item) => tval($item))->filter()->values()->all();
    }
}
