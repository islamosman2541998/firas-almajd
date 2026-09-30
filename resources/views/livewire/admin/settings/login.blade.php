<form wire:submit="save">
    <x-admin.page-head :title="__('admin.nav.settings_login')" :subtitle="__('admin.settings.login_subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-ghost" x-on:click="confirmAction(() => $wire.resetDefaults(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.login.background') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.upload model="uploads.background_image" :file="$uploads['background_image'] ?? null" :current="$data['background_image']" :label="__('admin.login.background_image')" />
                    <div class="a-grid">
                        <x-admin.range model="data.background_opacity" :label="__('admin.login.background_opacity')" live />
                        <x-admin.range model="data.background_blur" :label="__('admin.login.background_blur')" max="20" unit="px" live />
                        <x-admin.color model="data.overlay_color" :label="__('admin.login.overlay_color')" live />
                        <x-admin.range model="data.overlay_opacity" :label="__('admin.login.overlay_opacity')" live />
                    </div>
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.login.card') }}</h2></div>
                <div class="a-card-body">
                    <div class="a-grid">
                        <x-admin.color model="data.card_bg" :label="__('admin.login.card_bg')" live />
                        <x-admin.range model="data.card_opacity" :label="__('admin.login.card_opacity')" live />
                        <x-admin.range model="data.card_blur" :label="__('admin.login.card_blur')" max="30" unit="px" live />
                        <x-admin.range model="data.card_radius" :label="__('admin.login.radius')" max="40" unit="px" live />
                        <x-admin.color model="data.card_border_color" :label="__('admin.login.border_color')" live />
                        <x-admin.range model="data.card_border_width" :label="__('admin.login.border_width')" max="10" unit="px" live />
                        <x-admin.range model="data.card_width" :label="__('admin.login.card_width')" min="320" max="640" unit="px" live />
                        <x-admin.color model="data.card_text" :label="__('admin.login.text_color')" live />
                        <x-admin.color model="data.card_muted" :label="__('admin.login.muted_color')" live />
                        <x-admin.color model="data.label_color" :label="__('admin.login.label_color')" live />
                    </div>
                    <x-admin.toggle model="data.show_logo" :label="__('admin.login.show_logo')" live />
                    <div class="a-grid">
                        <x-admin.upload model="uploads.logo" :file="$uploads['logo'] ?? null" :current="$data['logo']" :label="__('admin.settings.logo')" dark />
                        <x-admin.range model="data.logo_width" :label="__('admin.login.logo_width')" min="40" max="400" unit="px" live />
                    </div>
                    <x-admin.t-input model="data.title" :label="__('admin.fields.title')" live />
                    <x-admin.t-input model="data.subtitle" :label="__('admin.login.subtitle')" live />
                    <x-admin.t-input model="data.footer_text" :label="__('admin.login.footer_text')" />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.login.inputs_button') }}</h2></div>
                <div class="a-card-body">
                    <div class="a-grid">
                        <x-admin.color model="data.input_bg" :label="__('admin.login.input_bg')" live />
                        <x-admin.range model="data.input_bg_opacity" :label="__('admin.login.input_bg_opacity')" live />
                        <x-admin.color model="data.input_text" :label="__('admin.login.input_text')" live />
                        <x-admin.color model="data.input_border" :label="__('admin.login.input_border')" live />
                        <x-admin.color model="data.input_focus" :label="__('admin.login.input_focus')" live />
                        <x-admin.range model="data.input_radius" :label="__('admin.login.radius')" max="30" unit="px" live />
                        <x-admin.color model="data.button_bg" :label="__('admin.login.button_bg')" live />
                        <x-admin.color model="data.button_color" :label="__('admin.login.button_color')" live />
                        <x-admin.color model="data.button_hover_bg" :label="__('admin.login.button_hover')" live />
                        <x-admin.range model="data.button_radius" :label="__('admin.login.radius')" max="30" unit="px" live />
                    </div>
                    <x-admin.t-input model="data.button_text" :label="__('admin.login.button_text')" live />
                    <x-admin.toggle model="data.show_remember" :label="__('admin.login.show_remember')" live />
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div style="position:sticky;top:80px">
                <div class="d-flex justify-content-between align-items-center mb-2"><strong class="small">{{ __('admin.common.preview') }}</strong><a class="a-btn a-btn-sm" href="{{ route('admin.login') }}" target="_blank" rel="noopener" onclick="return false" title="{{ __('admin.login.preview_note') }}"><i class="bi bi-info-circle"></i></a></div>
                @php
                    $bgUpload = $uploads['background_image'] ?? null;
                    $bgUrl = $bgUpload && method_exists($bgUpload, 'isPreviewable') && $bgUpload->isPreviewable() ? $bgUpload->temporaryUrl() : media_url($data['background_image']);
                    $logoUpload = $uploads['logo'] ?? null;
                    $logoUrl = $logoUpload && method_exists($logoUpload, 'isPreviewable') && $logoUpload->isPreviewable() ? $logoUpload->temporaryUrl() : media_url($data['logo']);
                @endphp
                <div class="a-login-preview" style="min-height:560px">
                    @include('admin.auth.card', ['login' => $data, 'preview' => true, 'bgUrl' => $bgUrl, 'logoUrl' => $logoUrl])
                </div>
            </div>
        </div>
    </div>
</form>
