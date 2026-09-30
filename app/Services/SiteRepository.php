<?php

namespace App\Services;

use App\Models\CareerJob;
use App\Models\Certificate;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\MenuItem;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Support\SiteCache;
use Illuminate\Support\Collection;

/**
 * Cached, read-only data for the public site. Every collection is cached
 * until the next content change (see SiteCache / FlushesSiteCache).
 */
class SiteRepository
{
    public function services(): Collection
    {
        return SiteCache::remember('services', fn () => Service::query()->active()->ordered()->get());
    }

    public function sliders(): Collection
    {
        return SiteCache::remember('sliders', fn () => Slider::query()->active()->ordered()->get());
    }

    public function partners(): Collection
    {
        return SiteCache::remember('partners', fn () => Partner::query()->active()->ordered()->get());
    }

    public function faqs(): Collection
    {
        return SiteCache::remember('faqs', fn () => Faq::query()->active()->ordered()->get());
    }

    public function projects(): Collection
    {
        return SiteCache::remember('projects', fn () => Project::query()->active()->ordered()->get());
    }

    public function gallery(): Collection
    {
        return SiteCache::remember('gallery', fn () => GalleryItem::query()->active()->ordered()->get());
    }

    public function certificates(): Collection
    {
        return SiteCache::remember('certificates', fn () => Certificate::query()->active()->ordered()->get());
    }

    public function jobs(): Collection
    {
        return SiteCache::remember('jobs', fn () => CareerJob::query()->active()->ordered()->get());
    }

    /**
     * Menu tree as plain arrays: title (translations), link, new_tab, children.
     */
    public function menu(string $location = 'header'): array
    {
        return SiteCache::remember("menu.{$location}", function () use ($location) {
            $items = MenuItem::query()->where('location', $location)->where('is_active', true)->ordered()->get();

            $build = function ($parentId) use (&$build, $items) {
                return $items->where('parent_id', $parentId)->map(fn (MenuItem $item) => [
                    'title' => $item->getTranslations('title'),
                    'link' => $item->linkValue(),
                    'new_tab' => $item->new_tab,
                    'children' => $build($item->id),
                ])->values()->all();
            };

            return $build(null);
        });
    }
}
