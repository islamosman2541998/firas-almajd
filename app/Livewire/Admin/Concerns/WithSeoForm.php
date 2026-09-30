<?php

namespace App\Livewire\Admin\Concerns;

use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Model;

/**
 * SEO block of a record (seo_metas): meta title / description / keywords
 * per language, share image, canonical URL and noindex.
 */
trait WithSeoForm
{
    public array $seo = [];

    public $seoImage = null;

    protected function fillSeo(?Model $record): void
    {
        $meta = $record?->seo;

        $this->seo = [
            'meta_title' => $this->translations($meta, 'meta_title'),
            'meta_description' => $this->translations($meta, 'meta_description'),
            'meta_keywords' => $this->translations($meta, 'meta_keywords'),
            'og_image' => $meta?->og_image,
            'canonical_url' => $meta?->canonical_url,
            'noindex' => (bool) $meta?->noindex,
        ];
    }

    protected function seoRules(): array
    {
        return [
            'seo.meta_title.*' => ['nullable', 'string', 'max:120'],
            'seo.meta_description.*' => ['nullable', 'string', 'max:320'],
            'seo.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'seo.canonical_url' => ['nullable', 'url', 'max:255'],
            'seo.noindex' => ['boolean'],
            'seoImage' => ['nullable', 'image', 'max:'.config('site.uploads.image_max_kb')],
        ];
    }

    protected function persistSeo(Model $record): void
    {
        $uploader = app(MediaUploader::class);

        if ($this->seoImage) {
            $this->seo['og_image'] = $uploader->replace($this->seo['og_image'] ?? null, $this->seoImage, 'seo', true, 1200);
            $this->seoImage = null;
        }

        $record->saveSeo([
            'meta_title' => $this->cleanT($this->seo['meta_title']),
            'meta_description' => $this->cleanT($this->seo['meta_description']),
            'meta_keywords' => $this->cleanT($this->seo['meta_keywords']),
            'og_image' => $this->seo['og_image'] ?: null,
            'canonical_url' => $this->seo['canonical_url'] ?: null,
            'noindex' => (bool) $this->seo['noindex'],
        ]);
    }

    public function removeSeoImage(): void
    {
        $this->seo['og_image'] = null;
    }
}
