<?php

namespace App\Livewire\Admin\Settings;

use App\Livewire\Admin\Concerns\WithToast;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Colours & design: dashboard theme (theme_admin) and the site brand
 * colours (theme_site). Each can be reset to its default.
 */
class Theme extends Component
{
    use WithToast;

    public array $admin = [];

    public array $site = [];

    #[Url(except: 'admin')]
    public string $tab = 'admin';

    public function mount(): void
    {
        $this->admin = settings()->group('theme_admin');
        $this->site = settings()->group('theme_site');
    }

    protected function colorRules(string $key, array $values): array
    {
        return collect($values)->reject(fn ($v, $k) => in_array($k, ['radius', 'font_size', 'sidebar_width'], true))
            ->mapWithKeys(fn ($v, $k) => ["{$key}.{$k}" => ['required', 'hex_color']])->all();
    }

    public function saveAdmin()
    {
        $this->validate([
            ...$this->colorRules('admin', config('settings.theme_admin')),
            'admin.radius' => ['required', 'integer', 'between:0,16'],
            'admin.font_size' => ['required', 'integer', 'between:12,17'],
            'admin.sidebar_width' => ['required', 'integer', 'between:220,320'],
        ]);
        settings()->put('theme_admin', $this->admin);
        $this->flashToast(__('admin.common.saved'));

        return $this->redirectRoute('admin.settings.theme', ['tab' => 'admin']);
    }

    public function saveSite(): void
    {
        $this->validate($this->colorRules('site', config('settings.theme_site')));
        settings()->put('theme_site', $this->site);
        $this->saved();
    }

    public function resetAdmin()
    {
        settings()->forget('theme_admin');
        $this->flashToast(__('admin.settings.reset_done'));

        return $this->redirectRoute('admin.settings.theme', ['tab' => 'admin']);
    }

    public function resetSite(): void
    {
        settings()->forget('theme_site');
        $this->site = settings()->group('theme_site');
        $this->toast(__('admin.settings.reset_done'));
    }

    public function applyPreset(string $preset): void
    {
        $presets = [
            'brand' => config('settings.theme_admin'),
            'light' => ['sidebar_bg' => '#f7f5f0', 'sidebar_text' => '#4d4740', 'sidebar_heading' => '#9a9186', 'sidebar_active_bg' => '#ece7dc', 'sidebar_active_text' => '#231f20', 'topbar_bg' => '#ffffff', 'body_bg' => '#fbfaf7'],
            'stone' => ['sidebar_bg' => '#2b2f2c', 'sidebar_text' => '#c3c7c1', 'sidebar_heading' => '#7f857d', 'sidebar_active_bg' => '#363b37', 'sidebar_active_text' => '#dfbd5b', 'body_bg' => '#eff0ec', 'primary' => '#2b2f2c'],
        ];
        $this->admin = array_replace($this->admin, $presets[$preset] ?? []);
    }

    public function render()
    {
        return view('livewire.admin.settings.theme')->title(__('admin.nav.settings_theme'));
    }
}
