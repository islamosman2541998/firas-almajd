@php
    $items = $items ?? collect();
    $visuals = $items->whereIn('type', ['image', 'video', 'embed']);
    $files = $items->where('type', 'pdf');
@endphp
@if ($visuals->isNotEmpty())
<section class="portfolio-section section-space page-gallery"><div class="container-wide">@if (filled(tval($data['gallery_title'])))<div class="section-heading reveal"><div><h2>{{ tval($data['gallery_title']) }}</h2></div></div>@endif<div class="portfolio-grid">@foreach ($visuals as $item)
@if ($item->type === 'image')
<button class="portfolio-item{{ $item->layout !== 'normal' ? ' '.$item->layout : '' }} reveal" data-gallery-alt="{{ $item->title }}" data-gallery-src="{{ $item->fileUrl() }}" type="button"><img alt="{{ $item->title }}" loading="lazy" src="{{ $item->fileUrl() }}"/>@if (filled($item->title))<span><span>{{ $item->title }}</span></span>@endif</button>
@else
<button class="portfolio-item portfolio-video{{ $item->layout !== 'normal' ? ' '.$item->layout : '' }} reveal" data-video-src="{{ $item->type === 'video' ? $item->fileUrl() : $item->embedUrl() }}" data-video-type="{{ $item->type }}" type="button">@if ($thumb = $item->thumbnail())<img alt="{{ $item->title }}" loading="lazy" src="{{ $thumb }}"/>@elseif ($item->type === 'video')<video muted playsinline preload="metadata" src="{{ $item->fileUrl() }}#t=0.5"></video>@endif<i aria-hidden="true" class="play-badge"></i>@if (filled($item->title))<span><span>{{ $item->title }}</span></span>@endif</button>
@endif
@endforeach</div></div></section>
<dialog class="gallery-lightbox" id="galleryLightbox"><button aria-label="{{ __('site.gallery_close') }}" class="gallery-close" type="button">×</button><img alt="" id="galleryLightboxImage" src="data:image/gif;base64,R0lGODlhAQABAAAAACw="/><div class="lightbox-video" id="galleryLightboxVideo"></div></dialog>
@endif
@if ($files->isNotEmpty())
<section class="certificates-section section-space page-files"><div class="container-wide">@if (filled(tval($data['files_title'])))<div class="section-heading reveal"><div><h2>{{ tval($data['files_title']) }}</h2></div></div>@endif<div class="file-list">@foreach ($files as $index => $file)<div class="file-card reveal"><span class="file-icon" aria-hidden="true">PDF</span><div class="file-copy"><h3>{{ $file->title ?: basename($file->path) }}</h3><small dir="ltr">{{ $file->humanSize() }}</small></div><div class="file-actions"><button class="dark-link" data-pdf-src="{{ $file->fileUrl() }}" data-pdf-title="{{ $file->title }}" type="button"><span>{{ tval($data['open_file']) }}</span><span aria-hidden="true" class="arrow"><x-site.arrow /></span></button><a download href="{{ $file->fileUrl() }}">{{ tval($data['download_file']) }}</a></div></div>@endforeach</div></div></section>
<dialog aria-label="PDF" class="certificate-dialog pdf-dialog"><button aria-label="{{ __('site.close') }}" class="certificate-close" type="button">×</button><iframe src="about:blank" title="PDF"></iframe></dialog>
@endif
