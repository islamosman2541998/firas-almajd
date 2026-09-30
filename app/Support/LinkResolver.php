<?php

namespace App\Support;

use App\Models\Page;
use App\Models\Service;
use Illuminate\Support\Str;

/**
 * Link values stored by the dashboard:
 *   route:about        localized static page
 *   page:{id}          custom page
 *   service:{id}       service detail page
 *   #anchor            anchor on the current page
 *   /path              site-relative path (locale prefix added)
 *   https://...        external link
 */
class LinkResolver
{
    public function url(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '#';
        }

        if (Str::startsWith($value, ['#', 'http://', 'https://', 'mailto:', 'tel:'])) {
            return $value;
        }

        if (Str::startsWith($value, 'route:')) {
            $name = Str::after($value, 'route:');

            return array_key_exists($name, config('site.routes')) ? lroute($name) : '#';
        }

        if (Str::startsWith($value, 'page:')) {
            $slug = $this->pageSlugs()[(int) Str::after($value, 'page:')] ?? null;

            return $slug ? lroute('pages.show', $slug) : '#';
        }

        if (Str::startsWith($value, 'service:')) {
            $slug = $this->serviceSlugs()[(int) Str::after($value, 'service:')] ?? null;

            return $slug ? lroute('services.show', $slug) : '#';
        }

        if (Str::startsWith($value, '/')) {
            return url(app()->getLocale().$value);
        }

        return $value;
    }

    /**
     * Options for link pickers in the dashboard: [value => label].
     */
    public function options(): array
    {
        $options = [];
        foreach (config('site.routes') as $route => $page) {
            $options['route:'.$route] = tval(config("sections.pages.{$page}.label"));
        }
        foreach (Service::query()->ordered()->get(['id', 'title']) as $service) {
            $options['service:'.$service->id] = __('admin.nav.services').' — '.$service->title;
        }
        foreach (Page::query()->ordered()->get(['id', 'title']) as $page) {
            $options['page:'.$page->id] = __('admin.nav.pages').' — '.$page->title;
        }

        return $options;
    }

    protected function pageSlugs(): array
    {
        return SiteCache::remember('slugs.pages', fn () => Page::query()->active()->pluck('slug', 'id')->all());
    }

    protected function serviceSlugs(): array
    {
        return SiteCache::remember('slugs.services', fn () => Service::query()->active()->pluck('slug', 'id')->all());
    }
}
