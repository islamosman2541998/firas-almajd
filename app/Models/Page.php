<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasMedia;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use FlushesSiteCache, HasMedia, HasSeo, HasTranslations, Sortable;

    protected $fillable = ['slug', 'title', 'description', 'image', 'is_active', 'sort_order'];

    public array $translatable = ['title', 'description'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function url(?string $locale = null): string
    {
        return lroute('pages.show', $this->slug, $locale);
    }
}
