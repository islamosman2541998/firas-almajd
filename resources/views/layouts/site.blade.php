@php
    $locale = app()->getLocale();
    $siteTheme = setting('theme_site');
    $siteThemeDefaults = config('settings.theme_site');
@endphp
<!DOCTYPE html>
<html dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" lang="{{ $locale }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="{{ $siteTheme['ink'] ?? '#231f20' }}" name="theme-color"/>
@include('site.partials.seo')
<link href="{{ media_url(setting('general.favicon')) }}" rel="icon" type="image/png"/>
<link href="{{ media_url(setting('general.favicon')) }}" rel="apple-touch-icon"/>
<link href="{{ asset('assets/site/fonts/cairo-2.ttf') }}" rel="preload" as="font" type="font/ttf" crossorigin/>
<link href="{{ asset_v('assets/site/fonts/fonts.css') }}" rel="stylesheet"/>
<link href="{{ asset_v('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet"/>
<link href="{{ asset_v('vendor/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet"/>
<link href="{{ asset_v('vendor/fontawesome/css/brands.min.css') }}" rel="stylesheet"/>
@stack('vendor-styles')
<link href="{{ asset_v('assets/site/css/styles.css') }}" rel="stylesheet"/>
<link href="{{ asset_v('assets/site/css/pages.css') }}" rel="stylesheet"/>
<link href="{{ asset_v('assets/site/css/site.css') }}" rel="stylesheet"/>
@if (array_diff_assoc(array_intersect_key($siteTheme, $siteThemeDefaults), $siteThemeDefaults))
<style>:root{--ink:{{ $siteTheme['ink'] }};--gold:{{ $siteTheme['gold'] }};--gold-light:{{ $siteTheme['gold_light'] }};--paper:{{ $siteTheme['paper'] }};--muted:{{ $siteTheme['muted'] }};--line:{{ $siteTheme['line'] }}}</style>
@endif
@stack('styles')
@include('site.partials.pixels-head')
</head>
<body data-page="{{ $bodyPage ?? 'page' }}" id="top">
@include('site.partials.pixels-body')
@if (($bodyPage ?? null) === 'index' && setting('general.loader_enabled'))
<div aria-label="{{ __('site.loading') }}" class="site-loader" id="siteLoader" role="status"><img alt="{{ setting_t('general.site_name') }}" height="240" src="{{ media_url(setting('general.loader_logo'), setting('general.logo')) }}" width="320"/></div>
@endif
<a class="skip-link" href="#main">{{ __('site.skip') }}</a>
@include('site.partials.header')
<main id="main">
@yield('content')
</main>
@include('site.partials.footer')
<script src="{{ asset_v('vendor/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
@stack('vendor-scripts')
<script src="{{ asset_v('assets/site/js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
