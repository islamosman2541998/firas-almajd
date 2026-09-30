@props(['model' => 'seo', 'image' => null, 'current' => null, 'url' => '', 'fallbackTitle' => '', 'fallbackDescription' => ''])
<div class="a-note mb-3"><i class="bi bi-info-circle"></i><span>{{ __('admin.seo.model_note') }}</span></div>
<x-admin.t-input :model="$model.'.meta_title'" :label="__('admin.seo.meta_title')" :hint="__('admin.seo.meta_title_hint')" max="120" />
<x-admin.t-input :model="$model.'.meta_description'" :label="__('admin.seo.meta_description')" :hint="__('admin.seo.meta_description_hint')" textarea rows="3" max="320" />
<x-admin.t-input :model="$model.'.meta_keywords'" :label="__('admin.seo.meta_keywords')" :hint="__('admin.seo.keywords_hint')" />
<div class="a-grid">
    <x-admin.upload model="seoImage" :file="$image" :current="$current" :label="__('admin.seo.og_image')" :hint="__('admin.seo.og_image_hint')" remove="removeSeoImage" />
    <div>
        <x-admin.input :model="$model.'.canonical_url'" :label="__('admin.seo.canonical')" dir="ltr" placeholder="https://" :hint="__('admin.seo.canonical_hint')" />
        <x-admin.toggle :model="$model.'.noindex'" :label="__('admin.seo.noindex')" :hint="__('admin.seo.noindex_hint')" />
    </div>
</div>
<label class="a-label mt-2">{{ __('admin.seo.google_preview') }}</label>
<div class="a-serp" x-data="{ lang: '{{ app()->getLocale() }}' }">
    <div class="d-flex gap-2 mb-2">
        @foreach (locales() as $code => $info)
            <button type="button" class="a-btn a-btn-sm" :class="lang === '{{ $code }}' && 'a-btn-primary'" x-on:click="lang = '{{ $code }}'">{{ strtoupper($code) }}</button>
        @endforeach
    </div>
    <div :dir="lang === 'ar' ? 'rtl' : 'ltr'">
        <div class="a-serp-site">
            <img src="{{ media_url(setting('seo.search_logo') ?: setting('general.favicon')) }}" alt="">
            <div><strong x-text="lang === 'ar' ? @js(setting_t('seo.site_name', 'ar')) : @js(setting_t('seo.site_name', 'en'))"></strong><small>{{ $url }}</small></div>
        </div>
        <h4 x-text="($wire.{{ $model }}.meta_title[lang] || @js($fallbackTitle))"></h4>
        <p x-text="(($wire.{{ $model }}.meta_description[lang] || @js($fallbackDescription) || '').slice(0, 160))"></p>
    </div>
</div>
