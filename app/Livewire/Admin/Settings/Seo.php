<?php

namespace App\Livewire\Admin\Settings;

class Seo extends SettingsPage
{
    protected function group(): string
    {
        return 'seo';
    }

    protected function excluded(): array
    {
        return ['pages'];
    }

    protected function translatable(): array
    {
        return ['site_name', 'alternate_name', 'default_description', 'default_keywords'];
    }

    protected function images(): array
    {
        return ['og_image' => ['seo', 1200, true], 'search_logo' => ['seo', 512, false]];
    }

    protected function rules(): array
    {
        return [
            'data.site_name.ar' => ['required', 'string', 'max:80'],
            'data.site_name.en' => ['nullable', 'string', 'max:80'],
            'data.alternate_name.*' => ['nullable', 'string', 'max:120'],
            'data.default_description.*' => ['nullable', 'string', 'max:320'],
            'data.default_keywords.*' => ['nullable', 'string', 'max:255'],
            'data.title_separator' => ['required', 'string', 'max:5'],
            'data.twitter_site' => ['nullable', 'string', 'max:40'],
            'data.organization_type' => ['required', 'in:Organization,Corporation,LocalBusiness,GeneralContractor,HomeAndConstructionBusiness,ProfessionalService'],
            'data.google_verification' => ['nullable', 'string', 'max:120'],
            'data.bing_verification' => ['nullable', 'string', 'max:120'],
            'data.yandex_verification' => ['nullable', 'string', 'max:120'],
            'data.indexing' => ['boolean'],
            'data.sitemap_enabled' => ['boolean'],
            'data.robots_extra' => ['nullable', 'string', 'max:2000'],
            'data.geo_region' => ['nullable', 'string', 'max:20'],
            'data.geo_placename' => ['nullable', 'string', 'max:80'],
        ];
    }

    protected function beforeSave(): void
    {
        foreach (['google_verification', 'bing_verification', 'yandex_verification'] as $key) {
            // Accept a full <meta ... content="..."> tag and keep only the token.
            if (preg_match('/content=["\']([^"\']+)["\']/', (string) ($this->data[$key] ?? ''), $m)) {
                $this->data[$key] = $m[1];
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.seo')->title(__('admin.nav.settings_seo'));
    }
}
