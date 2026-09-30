<form wire:submit="save">
    <x-admin.page-head :title="__('admin.nav.settings_dashboard')" :subtitle="__('admin.settings.dashboard_subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-ghost" x-on:click="confirmAction(() => $wire.resetDefaults(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.dashboard_general') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.name" :label="__('admin.settings.dashboard_name')" required />
                    <div class="a-grid">
                        <x-admin.input model="data.per_page" type="number" min="5" max="100" :label="__('admin.settings.per_page')" />
                        <x-admin.select model="data.default_locale" :label="__('admin.settings.default_locale')" :options="collect(locales())->map(fn ($l) => $l['name'])->all()" />
                        <x-admin.input model="data.notifications_poll" type="number" min="10" max="600" :label="__('admin.settings.notifications_poll')" :hint="__('admin.settings.notifications_poll_hint')" />
                        <x-admin.select model="data.date_format" :label="__('admin.settings.date_format')" :options="['Y-m-d' => now()->format('Y-m-d'), 'd/m/Y' => now()->format('d/m/Y'), 'm/d/Y' => now()->format('m/d/Y'), 'd-m-Y' => now()->format('d-m-Y')]" />
                    </div>
                    <x-admin.toggle model="data.show_welcome" :label="__('admin.settings.show_welcome')" />
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.logos') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.upload model="uploads.logo" :file="$uploads['logo'] ?? null" :current="$data['logo']" :label="__('admin.settings.dashboard_logo')" dark />
                    <x-admin.upload model="uploads.favicon" :file="$uploads['favicon'] ?? null" :current="$data['favicon']" :label="__('admin.settings.favicon')" />
                    <a class="a-btn a-btn-sm" href="{{ route('admin.settings.theme') }}"><i class="bi bi-palette"></i> {{ __('admin.nav.settings_theme') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
