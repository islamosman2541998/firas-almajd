@extends('layouts.site', ['bodyPage' => 'page'])

@section('content')
<section @class(['page-intro', 'with-photo' => $page->image])>
@if ($page->image)
<img alt="{{ $page->title }}" class="intro-image" fetchpriority="high" src="{{ media_url($page->image) }}"/>
<div aria-hidden="true" class="intro-overlay"></div>
@endif
<div class="container-wide">
<nav aria-label="breadcrumb" class="breadcrumb-nav"><a class="breadcrumb-home" href="{{ lroute('home') }}">{{ __('site.breadcrumb_home') }}</a><span aria-hidden="true">/</span><span>{{ $page->title }}</span></nav>
<div class="eyebrow"><span></span><span>{{ setting_t('general.site_name') }}</span></div>
<h1>{{ $page->title }}</h1>
</div>
</section>
@if (filled($page->description))
<section class="custom-page-body section-space"><div class="container-wide"><div class="custom-page-text reveal">{!! nl2br(e($page->description)) !!}</div></div></section>
@endif
@include('site.sections.page-gallery', ['data' => $labels, 'items' => $page->gallery])
@endsection
