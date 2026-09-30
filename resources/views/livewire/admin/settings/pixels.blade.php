<form wire:submit="save">
    <x-admin.page-head :title="__('admin.nav.settings_pixels')" :subtitle="__('admin.settings.pixels_subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-ghost" x-on:click="confirmAction(() => $wire.resetDefaults(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.pixels.platforms') }}</h2><p>{{ __('admin.pixels.platforms_hint') }}</p></div></div>
                <div class="a-card-body">
                    <div class="a-grid">                    <x-admin.input model="data.meta_pixel_id" label="Meta (Facebook / Instagram) Pixel ID" dir="ltr" placeholder="123456789012345" :hint="__('admin.pixels.hints.meta')" />                    <x-admin.input model="data.ga4_id" label="Google Analytics 4" dir="ltr" placeholder="G-XXXXXXXXXX" :hint="__('admin.pixels.hints.ga4')" />                    <x-admin.input model="data.gtm_id" label="Google Tag Manager" dir="ltr" placeholder="GTM-XXXXXXX" :hint="__('admin.pixels.hints.gtm')" />                    <x-admin.input model="data.google_ads_id" label="Google Ads" dir="ltr" placeholder="AW-123456789" :hint="__('admin.pixels.hints.ads')" />                    <x-admin.input model="data.google_ads_lead_label" label="Google Ads — Conversion label" dir="ltr" placeholder="AbC-D_efG-h12" :hint="__('admin.pixels.hints.ads_label')" />                    <x-admin.input model="data.tiktok_pixel_id" label="TikTok Pixel ID" dir="ltr" placeholder="C1A2B3C4D5E6F7G8H9" :hint="__('admin.pixels.hints.tiktok')" />                    <x-admin.input model="data.snapchat_pixel_id" label="Snapchat Pixel ID" dir="ltr" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" :hint="__('admin.pixels.hints.snapchat')" />                    <x-admin.input model="data.x_pixel_id" label="X (Twitter) Pixel ID" dir="ltr" placeholder="o1abc" :hint="__('admin.pixels.hints.x')" />                    <x-admin.input model="data.linkedin_partner_id" label="LinkedIn Insight — Partner ID" dir="ltr" placeholder="1234567" :hint="__('admin.pixels.hints.linkedin')" />                    <x-admin.input model="data.pinterest_tag_id" label="Pinterest Tag ID" dir="ltr" placeholder="2612345678901" :hint="__('admin.pixels.hints.pinterest')" />                    <x-admin.input model="data.clarity_id" label="Microsoft Clarity" dir="ltr" placeholder="abcd1234ef" :hint="__('admin.pixels.hints.clarity')" />                    </div>
                    <x-admin.toggle model="data.track_leads" :label="__('admin.pixels.track_leads')" :hint="__('admin.pixels.track_leads_hint')" />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.pixels.custom') }}</h2><p>{{ __('admin.pixels.custom_hint') }}</p></div></div>
                <div class="a-card-body">
                    <x-admin.textarea model="data.custom_head" :label="__('admin.pixels.custom_head')" dir="ltr" rows="5" class="font-monospace small" />
                    <x-admin.textarea model="data.custom_body" :label="__('admin.pixels.custom_body')" dir="ltr" rows="4" class="font-monospace small" />
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.pixels.status') }}</h2></div>
                <div class="a-table-wrap">
                    <table class="a-table">
                        <tbody>
                            @foreach (['meta_pixel_id' => 'Meta', 'ga4_id' => 'GA4', 'gtm_id' => 'GTM', 'google_ads_id' => 'Google Ads', 'tiktok_pixel_id' => 'TikTok', 'snapchat_pixel_id' => 'Snapchat', 'x_pixel_id' => 'X', 'linkedin_partner_id' => 'LinkedIn', 'pinterest_tag_id' => 'Pinterest', 'clarity_id' => 'Clarity'] as $key => $name)
                                <tr><td>{{ $name }}</td><td class="text-end"><x-admin.status :active="filled(setting('pixels.'.$key))" :on="__('admin.pixels.connected')" :off="__('admin.pixels.not_connected')" /></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="a-card-body small text-muted">{{ __('admin.pixels.events_note') }}</div>
            </div>
        </div>
    </div>
</form>
