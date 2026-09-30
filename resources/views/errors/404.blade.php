@php
    // Errors can happen before the locale middleware runs: read it from the URL.
    $segment = request()->segment(1);
    if (array_key_exists((string) $segment, config('site.locales'))) {
        app()->setLocale($segment);
        \Illuminate\Support\Facades\URL::defaults(['locale' => $segment]);
    } else {
        \Illuminate\Support\Facades\URL::defaults(['locale' => app()->getLocale()]);
    }
    app(\App\Services\SeoManager::class)->set('title', __('site.not_found_title'))->set('noindex', true);
@endphp
@extends('layouts.site', ['bodyPage' => 'error'])

@section('content')
<section class="error-page section-space"><div class="container-wide"><p class="error-code">404</p><h1>{{ __('site.not_found_title') }}</h1><p>{{ __('site.not_found_text') }}</p><a class="button button-gold" href="{{ lroute('home') }}"><span>{{ __('site.back_home') }}</span><span aria-hidden="true" class="arrow"><x-site.arrow /></span></a></div></section>
@endsection
