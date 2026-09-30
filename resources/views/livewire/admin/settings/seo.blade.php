<form wire:submit="save">
    <x-admin.page-head :title="__('admin.nav.settings_seo')" :subtitle="__('admin.settings.seo_subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-ghost" x-on:click="confirmAction(() => $wire.resetDefaults(), {text: @js(__('admin.settings.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.settings.reset') }}</button>
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.settings.search_appearance') }}</h2><p>{{ __('admin.settings.search_appearance_hint') }}</p></div></div>
                <div class="a-card-body">
                    <x-admin.t-input model="data.site_name" :label="__('admin.settings.seo_site_name')" :hint="__('admin.settings.seo_site_name_hint')" required live />
                    <x-admin.t-input model="data.alternate_name" :label="__('admin.settings.alternate_name')" />
                    <x-admin.t-input model="data.default_description" :label="__('admin.settings.default_description')" :hint="__('admin.settings.default_description_hint')" textarea rows="3" max="320" live />
                    <x-admin.t-input model="data.default_keywords" :label="__('admin.settings.default_keywords')" :hint="__('admin.seo.keywords_hint')" />
                    <div class="a-grid">
                        <x-admin.input model="data.title_separator" :label="__('admin.settings.title_separator')" dir="ltr" />
                        <x-admin.select model="data.organization_type" :label="__('admin.settings.organization_type')" :options="['GeneralContractor' => 'GeneralContractor', 'HomeAndConstructionBusiness' => 'HomeAndConstructionBusiness', 'LocalBusiness' => 'LocalBusiness', 'ProfessionalService' => 'ProfessionalService', 'Corporation' => 'Corporation', 'Organization' => 'Organization']" :hint="__('admin.settings.organization_type_hint')" />
                    </div>
                    <div class="a-grid">
                        <x-admin.upload model="uploads.search_logo" :file="$uploads['search_logo'] ?? null" :current="$data['search_logo']" :label="__('admin.settings.search_logo')" :hint="__('admin.settings.search_logo_hint')" />
                        <x-admin.upload model="uploads.og_image" :file="$uploads['og_image'] ?? null" :current="$data['og_image']" :label="__('admin.seo.og_image')" :hint="__('admin.seo.og_image_hint')" />
                    </div>
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><div><h2>{{ __('admin.settings.verification') }}</h2><p>{{ __('admin.settings.verification_hint') }}</p></div></div>
                <div class="a-card-body">
                    <x-admin.input model="data.google_verification" label="Google Search Console" dir="ltr" placeholder='<meta name="google-site-verification" content="…">' />
                    <x-admin.input model="data.bing_verification" label="Bing Webmaster" dir="ltr" />
                    <x-admin.input model="data.yandex_verification" label="Yandex" dir="ltr" />
                    <div class="a-grid">
                        <x-admin.input model="data.twitter_site" :label="__('admin.settings.twitter_site')" dir="ltr" placeholder="@firasalmajd" />
                        <x-admin.input model="data.geo_placename" :label="__('admin.settings.geo_placename')" dir="ltr" />
                        <x-admin.input model="data.geo_region" :label="__('admin.settings.geo_region')" dir="ltr" placeholder="SA-01" />
                    </div>
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.settings.crawling') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.toggle model="data.indexing" :label="__('admin.settings.indexing')" :hint="__('admin.settings.indexing_hint')" />
                    <x-admin.toggle model="data.sitemap_enabled" :label="__('admin.settings.sitemap_enabled')" />
                    <x-admin.textarea model="data.robots_extra" :label="__('admin.settings.robots_extra')" dir="ltr" rows="3" :hint="__('admin.settings.robots_extra_hint')" />
                    <div class="d-flex flex-wrap gap-2">
                        <a class="a-btn a-btn-sm" href="{{ route('sitemap') }}" target="_blank" rel="noopener"><i class="bi bi-diagram-3"></i> sitemap.xml</a>
                        <a class="a-btn a-btn-sm" href="{{ route('robots') }}" target="_blank" rel="noopener"><i class="bi bi-robot"></i> robots.txt</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="a-card" style="position:sticky;top:80px">
                <div class="a-card-head"><h2>{{ __('admin.seo.google_preview') }}</h2></div>
                <div class="a-card-body" x-data="{ lang: '{{ app()->getLocale() }}' }">
                    <div class="d-flex gap-2 mb-3">
                        @foreach (locales() as $code => $info)
                            <button type="button" class="a-btn a-btn-sm" :class="lang === '{{ $code }}' && 'a-btn-primary'" x-on:click="lang = '{{ $code }}'">{{ $info['name'] }}</button>
                        @endforeach
                    </div>
                    <div class="a-serp" :dir="lang === 'ar' ? 'rtl' : 'ltr'">
                        <div class="a-serp-site">
                            <img src="{{ media_url($data['search_logo'] ?: setting('general.favicon')) }}" alt="">
                            <div><strong x-text="$wire.data.site_name[lang] || $wire.data.site_name.ar"></strong><small>{{ url('/') }}/<span x-text="lang"></span></small></div>
                        </div>
                        <h4 x-text="@js(setting('seo.pages.home.title')).ar && lang === 'ar' ? @js(tval(setting('seo.pages.home.title'), 'ar')) : @js(tval(setting('seo.pages.home.title'), 'en'))"></h4>
                        <p x-text="($wire.data.default_description[lang] || '').slice(0, 160)"></p>
                    </div>
                    <p class="a-hint mt-3">{{ __('admin.settings.preview_hint') }}</p>
                    <a class="a-btn a-btn-sm" href="{{ route('admin.sections', ['page' => 'home', 'section' => 'seo']) }}"><i class="bi bi-pencil"></i> {{ __('admin.settings.edit_home_seo') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
