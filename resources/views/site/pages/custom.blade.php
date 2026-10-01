@extends('layouts.site', ['bodyPage' => 'page'])

@section('content')
<h1 class="visually-hidden">{{ $page->title }}</h1>
@if (filled($page->description))
<section class="custom-page-body section-space"><div class="container-wide"><div class="custom-page-text reveal">{!! nl2br(e($page->description)) !!}</div></div></section>
@endif
@include('site.sections.page-gallery', ['data' => $labels, 'items' => $page->gallery])
@endsection
