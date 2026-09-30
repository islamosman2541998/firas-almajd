<div>
<form wire:submit="save">
    <x-admin.page-head :title="$page ? $page->title : __('admin.pages.create')" :back="route('admin.pages.index')">
        <x-slot:actions>
            @if ($page)<a class="a-btn" href="{{ $page->url() }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.common.view') }}</a>@endif
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>

    <div class="a-tabs">
        @foreach (['content' => 'bi-card-text', 'gallery' => 'bi-collection-play', 'seo' => 'bi-search'] as $key => $icon)
            <button type="button" @class(['is-active' => $tab === $key]) wire:click="$set('tab', '{{ $key }}')"><i class="bi {{ $icon }}"></i> {{ __('admin.pages.tabs.'.$key) }}</button>
        @endforeach
    </div>

    <div class="a-layout">
        <div>
            <div class="a-card" @if($tab !== 'content') hidden @endif>
                <div class="a-card-body">
                    <x-admin.t-input model="form.title" :label="__('admin.fields.title')" required />
                    <x-admin.t-input model="form.description" :label="__('admin.fields.description')" :hint="__('admin.pages.description_hint')" textarea rows="8" />
                    <div class="a-grid">
                        <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="__('admin.pages.cover')" :hint="__('admin.pages.cover_hint')" remove="removeImage" />
                        <x-admin.input model="form.slug" :label="__('admin.fields.slug')" dir="ltr" required :hint="__('admin.common.slug_hint')" />
                    </div>
                </div>
            </div>
            <div class="a-card" @if($tab !== 'seo') hidden @endif>
                <div class="a-card-body">
                    <x-admin.seo-fields :image="$seoImage" :current="$seo['og_image']" :url="url(app()->getLocale().'/p/'.($form['slug'] ?: '…'))" :fallback-title="tval($form['title'])" :fallback-description="tval($form['description'])" />
                </div>
            </div>
            @if ($tab === 'gallery' && ! $page)
                <div class="a-card"><div class="a-card-body"><div class="a-note"><i class="bi bi-info-circle"></i><span>{{ __('admin.pages.save_first') }}</span></div></div></div>
            @endif
        </div>
        <aside class="a-layout-aside">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.common.publish') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
                    <x-admin.save-button class="w-100" />
                </div>
            </div>
        </aside>
    </div>
</form>
@if ($page)
    <div @if($tab !== 'gallery') hidden @endif class="mt-3">
        <livewire:admin.media.manager owner-type="page" :owner-id="$page->id" :key="'media-page-'.$page->id" />
    </div>
@endif
</div>
