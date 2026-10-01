@extends('layouts.site', ['bodyPage' => 'service'])

@php
    // With a gallery, it sits beside the service scope in one section instead of below it.
    $keys = array_column($sections, 'key');
    $withGallery = in_array('scope', $keys, true) && in_array('gallery', $keys, true)
        && $service->gallery->whereIn('type', ['image', 'video', 'embed'])->isNotEmpty();
@endphp

@section('content')
<h1 class="visually-hidden">{{ $service->title }}</h1>
@foreach ($sections as $section)
@continue($withGallery && $section['key'] === 'gallery')
@include($section['view'], ['data' => $section['data'], 'pageKey' => 'service', 'sectionKey' => $section['key'], 'service' => $service, 'withGallery' => $withGallery && $section['key'] === 'scope'])
@endforeach
@endsection
