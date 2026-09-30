@php($faqs = app(\App\Services\SiteRepository::class)->faqs())
@if ($faqs->isNotEmpty())
<section class="faq-section section-space"><div class="container-wide faq-layout"><div class="reveal"><div class="eyebrow"><span>{{ tval($data['eyebrow']) }}</span></div><h2>{{ tval($data['title']) }}</h2><p>{{ tval($data['intro']) }}</p></div><div class="faq-list reveal">@foreach ($faqs as $faq)<details class="faq-item"><summary><span>{{ $faq->question }}</span><span aria-hidden="true">+</span></summary><p>{!! nl2br(e($faq->answer)) !!}</p></details>@endforeach</div></div></section>
@endif
