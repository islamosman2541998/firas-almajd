@php
    $items = $service->gallery->whereIn('type', ['image', 'video', 'embed'])->values();
    $total = $items->count();
@endphp
<div class="sg reveal" data-sg>
<div class="sg-stage">
@foreach ($items as $i => $item)
<figure @class(['sg-slide', 'is-active' => $i === 0]) data-sg-slide aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
@if ($item->type === 'image')
<img alt="{{ $item->title ?: $service->title }}" @if ($i > 0) loading="lazy" @endif src="{{ $item->fileUrl() }}"/>
<button aria-label="{{ __('site.enlarge') }}" class="sg-zoom" data-gallery-alt="{{ $item->title }}" data-gallery-src="{{ $item->fileUrl() }}" type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5"/></svg></button>
@elseif ($item->type === 'video')
<div class="sg-video" data-sg-video>
<video playsinline preload="metadata" @if ($item->poster) poster="{{ media_url($item->poster) }}" @endif src="{{ $item->fileUrl() }}{{ $item->poster ? '' : '#t=0.5' }}"></video>
<button aria-label="{{ __('site.play_video') }}" class="sg-bigplay" data-v-toggle type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M8 5.5v13l10.5-6.5z"/></svg></button>
<div class="sg-controls" dir="ltr">
<button aria-label="{{ __('site.play_video') }}" class="sg-ctrl" data-v-toggle type="button"><svg aria-hidden="true" class="i-play" viewBox="0 0 24 24"><path d="M8 5.5v13l10.5-6.5z"/></svg><svg aria-hidden="true" class="i-pause" viewBox="0 0 24 24"><path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z"/></svg></button>
<span class="sg-time" data-v-time>0:00</span>
<div aria-label="{{ __('site.video_progress') }}" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" class="sg-progress" data-v-progress role="slider" tabindex="0"><span class="sg-buffer" data-v-buffer></span><span class="sg-fill" data-v-fill></span></div>
<span class="sg-time" data-v-duration>0:00</span>
<button aria-label="{{ __('site.mute') }}" class="sg-ctrl" data-v-mute type="button"><svg aria-hidden="true" class="i-sound" viewBox="0 0 24 24"><path d="M4 9.5h4L13 5v14l-5-4.5H4z"/><path class="s" d="M16 9a4.5 4.5 0 0 1 0 6M18.5 6.5a8 8 0 0 1 0 11"/></svg><svg aria-hidden="true" class="i-muted" viewBox="0 0 24 24"><path d="M4 9.5h4L13 5v14l-5-4.5H4z"/><path class="s" d="m16.5 9.5 5 5m0-5-5 5"/></svg></button>
<button aria-label="{{ __('site.fullscreen') }}" class="sg-ctrl" data-v-fs type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path class="s" d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5"/></svg></button>
</div>
</div>
@else
<div class="sg-embed" data-sg-embed="{{ $item->embedUrl() }}">@if ($thumb = $item->thumbnail())<img alt="{{ $item->title ?: $service->title }}" loading="lazy" src="{{ $thumb }}"/>@endif<button aria-label="{{ __('site.play_video') }}" class="sg-bigplay sg-play" type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M8 5.5v13l10.5-6.5z"/></svg></button></div>
@endif
@if (filled($item->title))<figcaption>{{ $item->title }}</figcaption>@endif
</figure>
@endforeach
@if ($total > 1)
<button aria-label="{{ __('site.previous') }}" class="sg-nav sg-prev" data-sg-prev type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></svg></button>
<button aria-label="{{ __('site.next') }}" class="sg-nav sg-next" data-sg-next type="button"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></svg></button>
<span class="sg-count"><bdi dir="ltr"><b data-sg-current>01</b> / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</bdi></span>
@endif
</div>
@if ($total > 1)
<div class="sg-thumbs" role="tablist">
@foreach ($items as $i => $item)
<button aria-label="{{ __('site.slide_of', ['n' => $i + 1, 'total' => $total]) }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" @class(['sg-thumb', 'is-video' => $item->type !== 'image', 'is-active' => $i === 0]) data-sg-thumb role="tab" type="button">
@if ($thumb = $item->thumbnail())<img alt="" loading="lazy" src="{{ $thumb }}"/>@elseif ($item->type === 'video')<video muted playsinline preload="metadata" src="{{ $item->fileUrl() }}#t=0.5"></video>@endif
</button>
@endforeach
</div>
@endif
</div>
<dialog class="gallery-lightbox" id="galleryLightbox"><button aria-label="{{ __('site.gallery_close') }}" class="gallery-close" type="button">×</button><img alt="" id="galleryLightboxImage" src="data:image/gif;base64,R0lGODlhAQABAAAAACw="/><div class="lightbox-video" id="galleryLightboxVideo"></div></dialog>
