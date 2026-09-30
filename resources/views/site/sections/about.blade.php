<section aria-labelledby="about-title" class="about section-space" id="about">
<div class="container-wide"><div class="row g-5 align-items-center">
<div class="col-lg-6 about-copy reveal"><h2 id="about-title">{{ tval($data['title']) }}</h2><p class="lead-copy">{{ tval($data['lead']) }}</p><p class="body-copy">{!! nl2br(e(tval($data['body']))) !!}</p>@if (! empty($data['principles']))<div class="about-principles">@foreach ($data['principles'] as $principle)<div><span class="mini-line"></span><h3>{{ tval($principle['title'] ?? '') }}</h3><p>{{ tval($principle['text'] ?? '') }}</p></div>@endforeach</div>@endif</div>
<div class="col-lg-6 reveal"><figure class="about-visual">@if ($data['image'])<img alt="{{ tval($data['image_alt']) }}" height="1402" loading="lazy" src="{{ media_url($data['image']) }}" width="1122"/>@endif<div aria-hidden="true" class="image-corner"></div>@if (filled(tval($data['caption'])))<figcaption><span>{{ tval($data['caption']) }}</span></figcaption>@endif</figure></div>
</div></div>
</section>
