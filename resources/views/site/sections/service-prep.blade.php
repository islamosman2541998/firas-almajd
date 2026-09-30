@if ($service->items('prep_items'))
<section class="request-prep section-space"><div class="container-wide prep-grid"><div><div class="eyebrow"><span>{{ tval($data['eyebrow']) }}</span></div><h2>{{ ml($data['title']) }}</h2><p>{{ tval($data['text']) }}</p></div><div><ul>@foreach ($service->items('prep_items') as $item)<li><span>{{ $item }}</span></li>@endforeach</ul><a class="dark-link" href="{{ lroute('contact', ['service' => $service->slug]) }}"><span>{{ tval($data['link_text']) }}</span><span aria-hidden="true" class="arrow"><x-site.arrow /></span></a></div></div></section>
@endif
