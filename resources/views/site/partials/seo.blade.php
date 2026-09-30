@php
    $seo = app(\App\Services\SeoManager::class);
    $title = $seo->title();
    $description = $seo->description();
    $image = $seo->image();
    $alternates = $seo->alternates();
    $jsonLd = $seo->jsonLd();
@endphp
<title>{{ $title }}</title>
<meta content="{{ $description }}" name="description"/>
@if ($keywords = $seo->keywords())
<meta content="{{ $keywords }}" name="keywords"/>
@endif
<meta content="{{ $seo->robots() }}" name="robots"/>
<link href="{{ $seo->canonical() }}" rel="canonical"/>
@foreach ($alternates as $hreflang => $href)
<link href="{{ $href }}" hreflang="{{ $hreflang }}" rel="alternate"/>
@endforeach
<meta content="{{ setting_t('seo.site_name') ?: setting_t('general.site_name') }}" property="og:site_name"/>
<meta content="{{ $seo->type() }}" property="og:type"/>
<meta content="{{ $title }}" property="og:title"/>
<meta content="{{ $description }}" property="og:description"/>
<meta content="{{ $seo->canonical() }}" property="og:url"/>
<meta content="{{ config('site.locales.'.app()->getLocale().'.og') }}" property="og:locale"/>
@foreach (locales() as $code => $info)
@if ($code !== app()->getLocale())
<meta content="{{ $info['og'] }}" property="og:locale:alternate"/>
@endif
@endforeach
@if ($image)
<meta content="{{ $image }}" property="og:image"/>
<meta content="{{ $title }}" property="og:image:alt"/>
@endif
<meta content="{{ $image ? 'summary_large_image' : 'summary' }}" name="twitter:card"/>
<meta content="{{ $title }}" name="twitter:title"/>
<meta content="{{ $description }}" name="twitter:description"/>
@if ($image)
<meta content="{{ $image }}" name="twitter:image"/>
@endif
@if ($twitter = setting('seo.twitter_site'))
<meta content="{{ '@'.ltrim($twitter, '@') }}" name="twitter:site"/>
@endif
@if ($v = setting('seo.google_verification'))
<meta content="{{ $v }}" name="google-site-verification"/>
@endif
@if ($v = setting('seo.bing_verification'))
<meta content="{{ $v }}" name="msvalidate.01"/>
@endif
@if ($v = setting('seo.yandex_verification'))
<meta content="{{ $v }}" name="yandex-verification"/>
@endif
@if ($region = setting('seo.geo_region'))
<meta content="{{ $region }}" name="geo.region"/>
<meta content="{{ setting('seo.geo_placename') }}" name="geo.placename"/>
@endif
@if ($jsonLd)
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif
