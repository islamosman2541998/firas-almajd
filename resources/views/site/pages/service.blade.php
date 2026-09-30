@extends('layouts.site', ['bodyPage' => 'service'])

@section('content')
<h1 class="visually-hidden">{{ $service->title }}</h1>
@foreach ($sections as $section)
@include($section['view'], ['data' => $section['data'], 'pageKey' => 'service', 'sectionKey' => $section['key'], 'service' => $service])
@endforeach
@endsection
