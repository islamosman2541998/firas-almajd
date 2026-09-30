<?php

namespace App\Livewire\Admin\Settings;

use App\Support\LinkResolver;

class General extends SettingsPage
{
    protected function group(): string
    {
        return 'general';
    }

    protected function translatable(): array
    {
        return ['site_name', 'company_name', 'tagline', 'logo_alt', 'address', 'header_button_text', 'footer_social_title', 'copyright', 'whatsapp_contact_template', 'whatsapp_career_template'];
    }

    protected function images(): array
    {
        return [
            'logo' => ['branding', 600, false],
            'footer_logo' => ['branding', 600, false],
            'loader_logo' => ['branding', 800, false],
            'favicon' => ['branding', 512, false],
        ];
    }

    protected function rules(): array
    {
        return [
            'data.site_name.ar' => ['required', 'string', 'max:120'],
            'data.site_name.en' => ['nullable', 'string', 'max:120'],
            'data.company_name.*' => ['nullable', 'string', 'max:160'],
            'data.tagline.*' => ['nullable', 'string', 'max:160'],
            'data.logo_alt.*' => ['nullable', 'string', 'max:160'],
            'data.address.*' => ['nullable', 'string', 'max:400'],
            'data.header_button_text.*' => ['nullable', 'string', 'max:40'],
            'data.footer_social_title.*' => ['nullable', 'string', 'max:80'],
            'data.copyright.*' => ['nullable', 'string', 'max:200'],
            'data.whatsapp_contact_template.*' => ['nullable', 'string', 'max:1000'],
            'data.whatsapp_career_template.*' => ['nullable', 'string', 'max:1000'],
            'data.loader_enabled' => ['boolean'],
            'data.header_button_show' => ['boolean'],
            'data.header_button_url' => ['nullable', 'string', 'max:255'],
            'data.phone' => ['nullable', 'string', 'max:30'],
            'data.phone_display' => ['nullable', 'string', 'max:30'],
            'data.whatsapp' => ['nullable', 'string', 'max:30'],
            'data.email_primary' => ['nullable', 'email', 'max:190'],
            'data.email_secondary' => ['nullable', 'email', 'max:190'],
            'data.notify_email' => ['nullable', 'email', 'max:190'],
            'data.cr_number' => ['nullable', 'string', 'max:40'],
            'data.map_query' => ['nullable', 'string', 'max:255'],
            'data.social' => ['array'],
            'data.social.*.platform' => ['required', 'in:'.implode(',', array_keys(config('site.social_platforms')))],
            'data.social.*.url' => ['nullable', 'url', 'max:255'],
            'data.social.*.enabled' => ['boolean'],
        ];
    }

    public function addSocial(): void
    {
        $this->data['social'][] = ['platform' => 'youtube', 'url' => '', 'enabled' => true];
    }

    public function render()
    {
        return view('livewire.admin.settings.general', ['linkOptions' => app(LinkResolver::class)->options()])
            ->title(__('admin.nav.settings_general'));
    }
}
