@extends('layouts.site', ['bodyPage' => $page === 'home' ? 'index' : $page])

@section('content')
@if ($heading = app(\App\Services\SeoManager::class)->heading())
<h1 class="visually-hidden">{{ $heading }}</h1>
@endif
@foreach ($sections as $section)
@include($section['view'], ['data' => $section['data'], 'pageKey' => $page, 'sectionKey' => $section['key']])
@endforeach
@endsection
