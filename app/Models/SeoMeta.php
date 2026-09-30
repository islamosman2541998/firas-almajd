<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class SeoMeta extends Model
{
    use FlushesSiteCache, HasTranslations;

    protected $fillable = ['meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical_url', 'noindex'];

    public array $translatable = ['meta_title', 'meta_description', 'meta_keywords'];

    protected function casts(): array
    {
        return ['noindex' => 'boolean'];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
