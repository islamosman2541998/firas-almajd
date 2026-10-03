@php
    $profileUrl = filled($data['button_url'] ?? null)
        ? link_url($data['button_url'])
        : \App\Models\Page::query()->where('slug', 'company-profile')->where('is_active', true)->first()?->url();
@endphp
@if ($profileUrl)
<section class="profile-cta"><div class="container-wide"><div class="profile-cta-card reveal">
<div class="profile-cta-copy">@if (filled(tval($data['eyebrow'])))<div class="eyebrow"><span>{{ tval($data['eyebrow']) }}</span></div>@endif<h2>{{ tval($data['title']) }}</h2>@if (filled(tval($data['text'])))<p>{{ tval($data['text']) }}</p>@endif</div>
<a class="profile-btn" href="{{ $profileUrl }}"><span aria-hidden="true" class="profile-btn-icon"><svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h4"/></svg></span><span class="profile-btn-text">{{ tval($data['button_text']) }}</span><span aria-hidden="true" class="profile-btn-arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span></a>
</div></div></section>
@endif
