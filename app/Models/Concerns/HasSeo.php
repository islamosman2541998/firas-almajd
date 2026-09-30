<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::deleting(fn ($model) => $model->seo()->delete());
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function saveSeo(array $data): void
    {
        $this->seo()->updateOrCreate([], [
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
            'noindex' => (bool) ($data['noindex'] ?? false),
        ]);
    }
}
