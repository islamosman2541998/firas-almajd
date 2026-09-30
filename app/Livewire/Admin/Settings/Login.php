<?php

namespace App\Livewire\Admin\Settings;

class Login extends SettingsPage
{
    protected function group(): string
    {
        return 'login';
    }

    protected function translatable(): array
    {
        return ['title', 'subtitle', 'button_text', 'footer_text'];
    }

    protected function images(): array
    {
        return ['background_image' => ['login', 2200, true], 'logo' => ['login', 600, false]];
    }

    protected function rules(): array
    {
        $color = ['required', 'hex_color'];
        $pct = ['required', 'integer', 'between:0,100'];

        return [
            'data.title.*' => ['nullable', 'string', 'max:80'],
            'data.subtitle.*' => ['nullable', 'string', 'max:160'],
            'data.button_text.*' => ['nullable', 'string', 'max:40'],
            'data.footer_text.*' => ['nullable', 'string', 'max:160'],
            'data.background_opacity' => $pct,
            'data.overlay_color' => $color,
            'data.overlay_opacity' => $pct,
            'data.background_blur' => ['required', 'integer', 'between:0,20'],
            'data.show_logo' => ['boolean'],
            'data.logo_width' => ['required', 'integer', 'between:40,400'],
            'data.card_bg' => $color,
            'data.card_opacity' => $pct,
            'data.card_blur' => ['required', 'integer', 'between:0,30'],
            'data.card_border_color' => $color,
            'data.card_border_width' => ['required', 'integer', 'between:0,10'],
            'data.card_radius' => ['required', 'integer', 'between:0,40'],
            'data.card_width' => ['required', 'integer', 'between:320,640'],
            'data.card_text' => $color,
            'data.card_muted' => $color,
            'data.label_color' => $color,
            'data.input_bg' => $color,
            'data.input_bg_opacity' => $pct,
            'data.input_text' => $color,
            'data.input_border' => $color,
            'data.input_focus' => $color,
            'data.input_radius' => ['required', 'integer', 'between:0,30'],
            'data.button_bg' => $color,
            'data.button_color' => $color,
            'data.button_hover_bg' => $color,
            'data.button_radius' => ['required', 'integer', 'between:0,30'],
            'data.show_remember' => ['boolean'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.login')->title(__('admin.nav.settings_login'));
    }
}
