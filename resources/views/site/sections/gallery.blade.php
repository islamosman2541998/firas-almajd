@php($items = app(\App\Services\SiteRepository::class)->gallery())
<section class="portfolio-section section-space"><div class="container-wide"><div class="portfolio-grid">@foreach ($items as $item)
@if ($item->isVideo())
<button class="portfolio-item portfolio-video{{ $item->layout !== 'normal' ? ' '.$item->layout : '' }} reveal" data-video-src="{{ media_url($item->video) }}" data-video-type="video" type="button" aria-label="{{ $item->title ?: __('site.play_video') }}">@if ($item->image)<img alt="{{ $item->title }}" loading="lazy" src="{{ media_url($item->image) }}"/>@else<video muted playsinline preload="metadata" src="{{ media_url($item->video) }}#t=0.5"></video>@endif<i aria-hidden="true" class="play-badge"></i>@if (filled($item->title))<span><span>{{ $item->title }}</span></span>@endif</button>
@elseif ($item->image)
<button class="portfolio-item{{ $item->layout !== 'normal' ? ' '.$item->layout : '' }} reveal" data-gallery-alt="{{ $item->title }}" data-gallery-src="{{ media_url($item->image) }}" type="button"><img alt="{{ $item->title }}" loading="lazy" src="{{ media_url($item->image) }}"/>@if (filled($item->title))<span><span>{{ $item->title }}</span></span>@endif</button>
@endif
@endforeach</div></div></section><dialog class="gallery-lightbox" id="galleryLightbox"><button aria-label="{{ __('site.gallery_close') }}" class="gallery-close" type="button">×</button><img alt="" id="galleryLightboxImage" src="data:image/gif;base64,R0lGODlhAQABAAAAACw="/><div class="lightbox-video" id="galleryLightboxVideo"></div></dialog>
