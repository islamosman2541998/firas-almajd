@if ($service->gallery->whereIn('type', ['image', 'video', 'embed'])->isNotEmpty())
<section class="svc-gallery section-space"><div class="container-wide">
@if (filled(tval($data['title'])))<div class="section-heading reveal"><div>@if (filled(tval($data['eyebrow'])))<div class="eyebrow"><span>{{ tval($data['eyebrow']) }}</span></div>@endif<h2>{{ tval($data['title']) }}</h2></div></div>@endif
@include('site.partials.service-gallery-stage')
</div></section>
@endif
