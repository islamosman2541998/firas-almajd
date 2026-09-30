<?php

namespace App\Livewire\Admin\Settings;

class Dashboard extends SettingsPage
{
    protected function group(): string
    {
        return 'dashboard';
    }

    protected function translatable(): array
    {
        return ['name'];
    }

    protected function images(): array
    {
        return ['logo' => ['branding', 400, false], 'favicon' => ['branding', 256, false]];
    }

    protected function rules(): array
    {
        return [
            'data.name.ar' => ['required', 'string', 'max:80'],
            'data.name.en' => ['nullable', 'string', 'max:80'],
            'data.per_page' => ['required', 'integer', 'between:5,100'],
            'data.default_locale' => ['required', 'in:'.implode(',', array_keys(config('site.locales')))],
            'data.notifications_poll' => ['required', 'integer', 'between:10,600'],
            'data.date_format' => ['required', 'in:Y-m-d,d/m/Y,m/d/Y,d-m-Y'],
            'data.show_welcome' => ['boolean'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.dashboard')->title(__('admin.nav.settings_dashboard'));
    }
}
