@php
    $mapQuery = urlencode((string) setting('general.map_query'));
    $emails = array_filter([setting('general.email_primary'), setting('general.email_secondary')]);
@endphp
<section aria-labelledby="contact-title" class="contact section-space{{ $data['side'] === 'form' ? ' contact-form-only' : '' }}" id="contact"><div class="container-wide">@if ($data['side'] === 'form')
<h2 class="visually-hidden" id="contact-title">{{ tval($data['title']) }}</h2><div class="contact-form-center reveal"><livewire:site.contact-form :labels="$data" /></div>
@else
<div class="row g-5"><div class="col-lg-6 reveal"><h2 id="contact-title">{{ tval($data['title']) }}</h2><p class="contact-intro">{{ tval($data['intro']) }}</p><div class="contact-details">@if (setting('general.phone'))<a href="tel:{{ setting('general.phone') }}"><span>{{ tval($data['phone_label']) }}</span><strong dir="ltr">{{ setting('general.phone_display') ?: setting('general.phone') }}</strong></a>@endif @if ($emails)<div class="contact-emails"><span>{{ tval($data['email_label']) }}</span>@foreach ($emails as $email)<a href="mailto:{{ $email }}"><strong dir="ltr">{{ $email }}</strong></a>@endforeach</div>@endif @if (setting('general.cr_number'))<div><span>{{ tval($data['cr_label']) }}</span><strong dir="ltr">{{ setting('general.cr_number') }}</strong></div>@endif @if (setting('general.po_box'))<div><span>{{ tval($data['po_box_label']) }}</span><strong dir="ltr">{{ setting('general.po_box') }}</strong></div>@endif @if (filled(setting_t('general.address')))<div><span>{{ tval($data['address_label']) }}</span><strong>{!! nl2br(e(setting_t('general.address'))) !!}</strong></div>@endif</div></div>
<div class="col-lg-6 reveal">@if ($mapQuery)<div class="contact-map"><iframe allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q={{ $mapQuery }}&amp;output=embed" title="{{ __('site.map_title') }}"></iframe><a href="https://www.google.com/maps/search/?api=1&amp;query={{ $mapQuery }}" rel="noopener noreferrer" target="_blank">{{ tval($data['map_link_text']) }}</a></div>@endif</div></div>
@endif
</div></section>
