@php
    $slides = app(\App\Services\SiteRepository::class)->sliders();
    $multiple = $slides->count() > 1;
@endphp
@if ($slides->isNotEmpty())
@if ($multiple)
@push('vendor-styles')
<link href="{{ asset_v('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet"/>
@endpush
@push('vendor-scripts')
<script src="{{ asset_v('vendor/swiper/swiper-bundle.min.js') }}" defer></script>
@endpush
@endif
<section aria-labelledby="hero-title" @class(['hero', 'hero-slider' => $multiple]) id="home" @if($multiple) aria-roledescription="carousel" data-hero-slider data-autoplay="{{ $data['autoplay'] ? 1 : 0 }}" data-delay="{{ max(1500, (int) $data['delay']) }}" data-effect="{{ $data['effect'] }}" data-loop="{{ $data['loop'] ? 1 : 0 }}" @endif>
<div @class(['hero-swiper swiper' => $multiple, 'hero-single' => ! $multiple])>
<div @class(['swiper-wrapper' => $multiple, 'hero-track' => ! $multiple])>
@foreach ($slides as $index => $slide)
@php
    $button = array_filter([
        '--hero-btn-bg' => $slide->button_bg,
        '--hero-btn-color' => $slide->button_color,
        '--hero-btn-hover' => $slide->button_hover_bg,
    ]);
    $style = collect($button)->map(fn ($v, $k) => "{$k}:{$v}")->implode(';');
    $poster = media_url($slide->image);
@endphp
<div @class(['hero-slide', 'swiper-slide' => $multiple]) @if($multiple) role="group" aria-roledescription="slide" aria-label="{{ __('site.slide_of', ['n' => $index + 1, 'total' => $slides->count()]) }}" @endif>
@if ($slide->isVideo())
<video aria-hidden="true" autoplay class="hero-image" loop muted playsinline @if($poster) poster="{{ $poster }}" @endif preload="{{ $index === 0 ? 'auto' : 'none' }}"><source src="{{ media_url($slide->video) }}" type="video/mp4"/></video>
@elseif ($poster)
<img alt="{{ $slide->title }}" class="hero-image" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif height="941" src="{{ $poster }}" width="1672"/>
@endif
<div class="hero-shade" @if($slide->overlay_opacity !== 100) style="opacity:{{ $slide->overlay_opacity / 100 }}" @endif></div><div aria-hidden="true" class="hero-grid"></div>
<div class="container-wide hero-content">
@if (filled($slide->title))
<h2 class="hero-title" @if($index === 0) id="hero-title" @endif><span>{{ $slide->title }}</span></h2>
@endif
@if (filled($slide->description))
<p>{{ $slide->description }}</p>
@endif
@if ($slide->show_button && filled($slide->button_text))
<div class="hero-buttons"><a class="button button-gold{{ $style ? ' hero-custom-button' : '' }}" href="{{ link_url($slide->button_url) }}" @if($style) style="{{ $style }}" @endif @if($slide->button_new_tab) target="_blank" rel="noopener" @endif><span>{{ $slide->button_text }}</span><span class="arrow"><x-site.arrow /></span></a></div>
@endif
</div>
</div>
@endforeach
</div>
@if ($multiple)
@if ($data['arrows'])
<div class="hero-arrows container-wide">
<button aria-label="{{ __('site.previous') }}" class="hero-arrow hero-prev" type="button"><svg aria-hidden="true" fill="none" height="22" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewBox="0 0 24 24" width="22"><path d="M5 12h14m-6-6 6 6-6 6"></path></svg></button>
<button aria-label="{{ __('site.next') }}" class="hero-arrow hero-next" type="button"><svg aria-hidden="true" fill="none" height="22" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewBox="0 0 24 24" width="22"><path d="M5 12h14m-6-6 6 6-6 6"></path></svg></button>
</div>
@endif
@if ($data['pagination'])
<div class="hero-pagination"></div>
@endif
@endif
</div>
</section>
@endif
