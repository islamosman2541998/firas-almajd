<div>
    <x-admin.page-head :title="__('admin.nav.settings_theme')" :subtitle="__('admin.theme.subtitle')" />
    <div class="a-tabs">
        <button type="button" @class(['is-active' => $tab === 'admin']) wire:click="$set('tab', 'admin')"><i class="bi bi-window-sidebar"></i> {{ __('admin.theme.admin') }}</button>
        <button type="button" @class(['is-active' => $tab === 'site']) wire:click="$set('tab', 'site')"><i class="bi bi-globe2"></i> {{ __('admin.theme.site') }}</button>
    </div>

    @if ($tab === 'admin')
        <form wire:submit="saveAdmin">
            <div class="a-card">
                <div class="a-card-head">
                    <div><h2>{{ __('admin.theme.admin') }}</h2><p>{{ __('admin.theme.admin_hint') }}</p></div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="small text-muted align-self-center">{{ __('admin.theme.presets') }}:</span>
                        @foreach (['brand', 'light', 'stone'] as $preset)
                            <button type="button" class="a-btn a-btn-sm" wire:click="applyPreset('{{ $preset }}')">{{ __('admin.theme.preset_names.'.$preset) }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="a-card-body">
                    @foreach (['sidebar' => ['sidebar_bg', 'sidebar_text', 'sidebar_heading', 'sidebar_active_bg', 'sidebar_active_text'], 'surface' => ['topbar_bg', 'topbar_text', 'body_bg', 'card_bg', 'border', 'table_head_bg', 'text', 'muted'], 'actions' => ['accent', 'accent_text', 'primary', 'primary_text'], 'states' => ['success', 'danger', 'warning', 'info']] as $group => $keys)
                        <div class="a-form-section">
                            <h3>{{ __('admin.theme.groups.'.$group) }}</h3>
                            <div class="a-swatches">
                                @foreach ($keys as $key)
                                    <div class="a-swatch">
                                        <div><x-admin.color :model="'admin.'.$key" :label="__('admin.theme.colors.'.$key)" /></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div class="a-form-section">
                        <h3>{{ __('admin.theme.groups.layout') }}</h3>
                        <div class="a-grid-3">
                            <x-admin.range model="admin.radius" :label="__('admin.theme.radius')" max="16" unit="px" />
                            <x-admin.range model="admin.font_size" :label="__('admin.theme.font_size')" min="12" max="17" unit="px" />
                            <x-admin.range model="admin.sidebar_width" :label="__('admin.theme.sidebar_width')" min="220" max="320" unit="px" />
                        </div>
                    </div>
                </div>
                <div class="a-card-foot">
                    <button type="button" class="a-btn a-btn-ghost me-auto" x-on:click="confirmAction(() => $wire.resetAdmin(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
                    <x-admin.save-button target="saveAdmin" />
                </div>
            </div>
        </form>
    @else
        <form wire:submit="saveSite">
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.theme.site') }}</h2><p>{{ __('admin.theme.site_hint') }}</p></div></div>
                <div class="a-card-body">
                    <div class="a-swatches">
                        @foreach (array_keys(config('settings.theme_site')) as $key)
                            <div class="a-swatch"><div><x-admin.color :model="'site.'.$key" :label="__('admin.theme.site_colors.'.$key)" live /></div></div>
                        @endforeach
                    </div>
                    <div class="a-preview-box mt-4" style="background:{{ $site['paper'] }}">
                        <div style="background:{{ $site['ink'] }};color:#fff;padding:16px 22px;display:flex;justify-content:space-between;align-items:center;font-size:13px">
                            <span style="color:{{ $site['gold_light'] }};font-weight:800">{{ setting_t('general.site_name') }}</span>
                            <span style="background:{{ $site['gold'] }};color:{{ $site['ink'] }};padding:8px 14px;font-weight:700">{{ setting_t('general.header_button_text') }} ↗</span>
                        </div>
                        <div style="padding:22px;color:{{ $site['ink'] }}">
                            <strong style="font-size:20px">{{ __('admin.theme.sample_title') }}</strong>
                            <p style="color:{{ $site['muted'] }};margin:8px 0 14px;font-size:13px">{{ __('admin.theme.sample_text') }}</p>
                            <div style="border-top:1px solid {{ $site['line'] }};padding-top:12px;font-size:13px;font-weight:700">{{ __('admin.theme.sample_link') }} <span style="color:{{ $site['gold'] }}">↗</span></div>
                        </div>
                    </div>
                </div>
                <div class="a-card-foot">
                    <button type="button" class="a-btn a-btn-ghost me-auto" x-on:click="confirmAction(() => $wire.resetSite(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
                    <x-admin.save-button target="saveSite" />
                </div>
            </div>
        </form>
    @endif
</div>
