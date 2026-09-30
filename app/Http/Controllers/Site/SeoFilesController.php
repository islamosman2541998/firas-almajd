<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Support\SiteCache;
use Illuminate\Http\Response;

class SeoFilesController extends Controller
{
    public function sitemap(): Response
    {
        abort_unless(setting('seo.sitemap_enabled', true), 404);

        $xml = SiteCache::remember('sitemap.xml', function () {
            $locales = array_keys(config('site.locales'));
            $entries = [];

            $add = function (callable $url, $updated = null, string $priority = '0.7') use (&$entries, $locales) {
                $alternates = [];
                foreach ($locales as $locale) {
                    $alternates[$locale] = $url($locale);
                }
                foreach ($locales as $locale) {
                    $entries[] = ['loc' => $alternates[$locale], 'alternates' => $alternates, 'lastmod' => $updated, 'priority' => $priority];
                }
            };

            foreach (config('site.routes') as $route => $page) {
                if (setting("seo.pages.{$page}.noindex")) {
                    continue;
                }
                $add(fn ($l) => route($route, ['locale' => $l]), null, $page === 'home' ? '1.0' : '0.8');
            }

            Service::query()->active()->ordered()->with('seo')->get()
                ->reject(fn ($s) => $s->seo?->noindex)
                ->each(fn ($s) => $add(fn ($l) => route('services.show', ['locale' => $l, 'service' => $s->slug]), $s->updated_at));

            Page::query()->active()->ordered()->with('seo')->get()
                ->reject(fn ($p) => $p->seo?->noindex)
                ->each(fn ($p) => $add(fn ($l) => route('pages.show', ['locale' => $l, 'page' => $p->slug]), $p->updated_at, '0.6'));

            return view('site.seo.sitemap', ['entries' => $entries])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = ['User-agent: *'];

        if (! setting('seo.indexing', true)) {
            $lines[] = 'Disallow: /';
        } else {
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /livewire';
            $lines[] = 'Allow: /';
        }

        if (filled(setting('seo.robots_extra'))) {
            $lines[] = trim((string) setting('seo.robots_extra'));
        }

        if (setting('seo.sitemap_enabled', true)) {
            $lines[] = '';
            $lines[] = 'Sitemap: '.route('sitemap');
        }

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
