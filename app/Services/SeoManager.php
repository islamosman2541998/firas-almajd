<?php

namespace App\Services;

use App\Models\SeoMeta;
use Illuminate\Support\Str;

/**
 * Collects the SEO data of the current page; rendered by site/partials/seo.
 */
class SeoManager
{
    protected array $data = [
        'title' => null,
        'full_title' => false,
        'description' => null,
        'keywords' => null,
        'image' => null,
        'canonical' => null,
        'noindex' => false,
        'type' => 'website',
        'heading' => null,
    ];

    protected array $breadcrumbs = [];

    protected array $schemas = [];

    /** Static page SEO from the settings (seo.pages.{key}). */
    public function forPage(string $key): static
    {
        $page = (array) setting("seo.pages.{$key}", []);

        $this->data['title'] = tval($page['title'] ?? null) ?: null;
        $this->data['full_title'] = true;
        $this->data['description'] = tval($page['description'] ?? null) ?: null;
        $this->data['keywords'] = tval($page['keywords'] ?? null) ?: null;
        $this->data['image'] = $page['og_image'] ?? null;
        $this->data['heading'] = tval($page['heading'] ?? null) ?: null;
        $this->data['noindex'] = (bool) ($page['noindex'] ?? false);

        return $this;
    }

    /** Model SEO (seo_metas) with fallbacks from the model itself. */
    public function forModel(?SeoMeta $meta, string $title, ?string $description = null, ?string $image = null): static
    {
        $this->data['title'] = ($meta ? $meta->getTranslation('meta_title', app()->getLocale(), false) : null) ?: $title;
        $this->data['full_title'] = filled($meta?->getTranslation('meta_title', app()->getLocale(), false));
        $this->data['description'] = ($meta ? $meta->getTranslation('meta_description', app()->getLocale(), false) : null) ?: $description;
        $this->data['keywords'] = $meta ? $meta->getTranslation('meta_keywords', app()->getLocale(), false) : null;
        $this->data['image'] = $meta?->og_image ?: $image;
        $this->data['canonical'] = $meta?->canonical_url;
        $this->data['noindex'] = (bool) $meta?->noindex;
        $this->data['heading'] = $title;

        return $this;
    }

    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function breadcrumb(string $name, string $url): static
    {
        $this->breadcrumbs[] = ['name' => $name, 'url' => $url];

        return $this;
    }

    public function schema(array $schema): static
    {
        $this->schemas[] = $schema;

        return $this;
    }

    public function title(): string
    {
        $siteName = setting_t('seo.site_name') ?: setting_t('general.site_name');
        $title = trim((string) $this->data['title']);

        if ($title === '') {
            return $siteName;
        }

        if ($this->data['full_title'] || Str::contains($title, $siteName)) {
            return $title;
        }

        return $siteName.(setting('seo.title_separator') ?: ' | ').$title;
    }

    public function description(): string
    {
        $description = $this->data['description'] ?: setting_t('seo.default_description');

        return Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $description))), 300, '…');
    }

    public function keywords(): ?string
    {
        return $this->data['keywords'] ?: (setting_t('seo.default_keywords') ?: null);
    }

    public function image(): ?string
    {
        return media_url($this->data['image'] ?: setting('seo.og_image'));
    }

    public function canonical(): string
    {
        return $this->data['canonical'] ?: url()->current();
    }

    public function robots(): string
    {
        if (! setting('seo.indexing', true) || $this->data['noindex']) {
            return 'noindex, nofollow';
        }

        return 'index, follow, max-image-preview:large, max-snippet:-1';
    }

    /** hreflang alternates: [locale => url] + x-default. */
    public function alternates(): array
    {
        $alternates = [];
        foreach (array_keys(locales()) as $locale) {
            $alternates[$locale] = strtok(switch_locale_url($locale), '?');
        }
        $alternates['x-default'] = $alternates[config('site.default_locale')] ?? reset($alternates);

        return $alternates;
    }

    public function heading(): ?string
    {
        return $this->data['heading'];
    }

    public function type(): string
    {
        return $this->data['type'];
    }

    /** JSON-LD graph for the page. */
    public function jsonLd(): array
    {
        $home = lroute('home');
        $graph = [];
        $isHome = request()->routeIs('home');

        $socials = collect((array) setting('general.social'))->pluck('url')->filter()->values()->all();

        $organization = array_filter([
            '@type' => setting('seo.organization_type') ?: 'Organization',
            '@id' => $home.'#organization',
            'name' => setting_t('general.company_name'),
            'alternateName' => setting_t('seo.alternate_name') ?: null,
            'url' => $home,
            'logo' => media_url(setting('seo.search_logo') ?: setting('general.logo')),
            'image' => $this->image(),
            'email' => setting('general.email_primary') ?: null,
            'telephone' => setting('general.phone') ?: null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => str_replace("\n", ', ', setting_t('general.address')),
                'addressLocality' => setting('seo.geo_placename') ?: null,
                'addressCountry' => 'SA',
            ],
            'sameAs' => $socials ?: null,
        ]);

        if ($isHome) {
            $graph[] = $organization;
            $graph[] = [
                '@type' => 'WebSite',
                '@id' => $home.'#website',
                'url' => $home,
                'name' => setting_t('seo.site_name') ?: setting_t('general.site_name'),
                'alternateName' => setting_t('seo.alternate_name') ?: null,
                'inLanguage' => app()->getLocale(),
                'publisher' => ['@id' => $home.'#organization'],
            ];
        }

        if ($this->breadcrumbs) {
            $items = [['name' => __('site.breadcrumb_home'), 'url' => $home], ...$this->breadcrumbs];
            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($items)->values()->map(fn ($item, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ])->all(),
            ];
        }

        foreach ($this->schemas as $schema) {
            $graph[] = $schema;
        }

        return $graph ? ['@context' => 'https://schema.org', '@graph' => array_map(fn ($node) => array_filter($node, fn ($v) => $v !== null), $graph)] : [];
    }
}
