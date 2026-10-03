<form wire:submit="save">
    <x-admin.page-head :title="__('admin.nav.settings_general')" :subtitle="__('admin.settings.general_subtitle')">
        <x-slot:actions>
            
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.identity') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.site_name" :label="__('admin.settings.site_name')" required />
                    <x-admin.t-input model="data.company_name" :label="__('admin.settings.company_name')" />
                    <x-admin.t-input model="data.tagline" :label="__('admin.settings.tagline')" :hint="__('admin.settings.tagline_hint')" />
                    <x-admin.t-input model="data.logo_alt" :label="__('admin.settings.logo_alt')" />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.contact') }}</h2></div>
                <div class="a-card-body">
                    <div class="a-grid">
                        <x-admin.input model="data.phone" :label="__('admin.settings.phone')" dir="ltr" :hint="__('admin.settings.phone_hint')" />
                        <x-admin.input model="data.phone_display" :label="__('admin.settings.phone_display')" dir="ltr" />
                        <x-admin.input model="data.whatsapp" :label="__('admin.settings.whatsapp')" dir="ltr" :hint="__('admin.settings.whatsapp_hint')" />
                        <x-admin.input model="data.cr_number" :label="__('admin.settings.cr_number')" dir="ltr" />
                        <x-admin.input model="data.po_box" :label="__('admin.settings.po_box')" dir="ltr" />
                        <x-admin.input model="data.email_primary" type="email" :label="__('admin.settings.email_primary')" dir="ltr" />
                        <x-admin.input model="data.email_secondary" type="email" :label="__('admin.settings.email_secondary')" dir="ltr" />
                    </div>
                    <x-admin.t-input model="data.address" :label="__('admin.settings.address')" textarea rows="2" />
                    <x-admin.input model="data.map_query" :label="__('admin.settings.map_query')" dir="ltr" :hint="__('admin.settings.map_query_hint')" live />
                    @if (filled($data['map_query']))
                        <iframe class="w-100 border" style="height:220px;border-color:var(--a-border)!important" loading="lazy" src="https://www.google.com/maps?q={{ urlencode($data['map_query']) }}&output=embed" title="map"></iframe>
                    @endif
                </div>
            </div>
            <div class="a-card" id="header">
                <div class="a-card-head"><h2>{{ __('admin.settings.header_button') }}</h2><x-admin.toggle model="data.header_button_show" :label="__('admin.common.show')" /></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.header_button_text" :label="__('admin.settings.button_text')" />
                    <x-admin.link model="data.header_button_url" :label="__('admin.settings.button_link')" :options="$linkOptions" />
                </div>
            </div>
            <div class="a-card" id="whatsapp-float">
                <div class="a-card-head"><div><h2>{{ __('admin.settings.whatsapp_float') }}</h2><p>{{ __('admin.settings.whatsapp_float_hint') }}</p></div><x-admin.toggle model="data.whatsapp_float_show" :label="__('admin.common.show')" /></div>
                <div class="a-card-body">
                    <x-admin.select model="data.whatsapp_float_side" :label="__('admin.settings.whatsapp_float_side')" :options="['right' => __('admin.settings.side_right'), 'left' => __('admin.settings.side_left')]" />
                    <x-admin.t-input model="data.whatsapp_float_message" :label="__('admin.settings.whatsapp_float_message')" :hint="__('admin.settings.whatsapp_float_message_hint')" textarea rows="2" />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.settings.whatsapp_templates') }}</h2><p>{{ __('admin.settings.whatsapp_templates_hint') }}</p></div></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.whatsapp_contact_template" :label="__('admin.settings.contact_template')" textarea rows="6" />
                    <x-admin.t-input model="data.whatsapp_career_template" :label="__('admin.settings.career_template')" textarea rows="6" />
                    <x-admin.input model="data.notify_email" type="email" :label="__('admin.settings.notify_email')" dir="ltr" :hint="__('admin.settings.notify_email_hint')" />
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.logos') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.upload model="uploads.logo" :file="$uploads['logo'] ?? null" :current="$data['logo']" :label="__('admin.settings.logo')" dark :hint="__('admin.settings.logo_hint')" />
                    <x-admin.upload model="uploads.footer_logo" :file="$uploads['footer_logo'] ?? null" :current="$data['footer_logo']" :label="__('admin.settings.footer_logo')" dark />
                    <x-admin.upload model="uploads.favicon" :file="$uploads['favicon'] ?? null" :current="$data['favicon']" :label="__('admin.settings.favicon')" :hint="__('admin.settings.favicon_hint')" />
                    <x-admin.toggle model="data.loader_enabled" :label="__('admin.settings.loader_enabled')" :hint="__('admin.settings.loader_hint')" />
                    <x-admin.upload model="uploads.loader_logo" :file="$uploads['loader_logo'] ?? null" :current="$data['loader_logo']" :label="__('admin.settings.loader_logo')" dark />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.footer') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.footer_social_title" :label="__('admin.settings.social_title')" stacked />
                    <x-admin.t-input model="data.copyright" :label="__('admin.settings.copyright')" stacked />
                    <label class="a-label">{{ __('admin.settings.social') }}</label>
                    @foreach ($data['social'] as $i => $social)
                        <div class="a-repeater-item" wire:key="social-{{ $i }}-{{ count($data['social']) }}">
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <select class="form-select" wire:model="data.social.{{ $i }}.platform" style="max-width:160px">
                                    @foreach (config('site.social_platforms') as $key => $platform)<option value="{{ $key }}">{{ $platform['label'] }}</option>@endforeach
                                </select>
                                <div class="form-check form-switch m-0"><input class="form-check-input" type="checkbox" wire:model="data.social.{{ $i }}.enabled" title="{{ __('admin.common.show') }}"></div>
                                <div class="a-actions ms-auto">
                                    <button type="button" class="a-btn" wire:click="moveItem('data.social', {{ $i }}, -1)" @disabled($i === 0)><i class="bi bi-arrow-up"></i></button>
                                    <button type="button" class="a-btn is-danger" wire:click="removeItem('data.social', {{ $i }})"><i class="bi bi-x-lg"></i></button>
                                </div>
                            </div>
                            <input type="url" class="form-control mb-2 @error('data.social.'.$i.'.url') is-invalid @enderror" dir="ltr" wire:model="data.social.{{ $i }}.url" placeholder="https://">
                            @error('data.social.'.$i.'.url')<div class="a-error mb-2">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <button type="button" class="a-btn a-btn-sm" wire:click="addSocial"><i class="bi bi-plus-lg"></i> {{ __('admin.common.add_item') }}</button>
                    <small class="a-hint">{{ __('admin.settings.social_hint') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="a-sticky-actions"><x-admin.save-button /></div>
</form>
