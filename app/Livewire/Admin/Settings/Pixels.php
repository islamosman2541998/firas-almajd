<?php

namespace App\Livewire\Admin\Settings;

class Pixels extends SettingsPage
{
    protected function group(): string
    {
        return 'pixels';
    }

    protected function rules(): array
    {
        $id = ['nullable', 'string', 'max:60', 'regex:/^[A-Za-z0-9_\-]+$/'];

        return [
            'data.meta_pixel_id' => $id,
            'data.ga4_id' => ['nullable', 'regex:/^G-[A-Z0-9]+$/i'],
            'data.gtm_id' => ['nullable', 'regex:/^GTM-[A-Z0-9]+$/i'],
            'data.google_ads_id' => ['nullable', 'regex:/^AW-[0-9]+$/i'],
            'data.google_ads_lead_label' => $id,
            'data.tiktok_pixel_id' => $id,
            'data.snapchat_pixel_id' => $id,
            'data.x_pixel_id' => $id,
            'data.linkedin_partner_id' => $id,
            'data.pinterest_tag_id' => $id,
            'data.clarity_id' => $id,
            'data.track_leads' => ['boolean'],
            'data.custom_head' => ['nullable', 'string', 'max:20000'],
            'data.custom_body' => ['nullable', 'string', 'max:20000'],
        ];
    }

    protected function beforeSave(): void
    {
        foreach ($this->data as $key => $value) {
            if (is_string($value) && ! str_starts_with($key, 'custom_')) {
                $this->data[$key] = trim($value);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.pixels')->title(__('admin.nav.settings_pixels'));
    }
}
