<div>
<form wire:submit="save">
    <x-admin.page-head :title="$service ? $service->title : __('admin.services.create')" :back="route('admin.services.index')">
        <x-slot:actions>
            @if ($service)<a class="a-btn" href="{{ $service->url() }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.common.view') }}</a>@endif
            <x-admin.save-button />
        </x-slot:actions>
    </x-admin.page-head>

    <div class="a-tabs">
        @foreach (['content' => 'bi-card-text', 'scope' => 'bi-list-check', 'gallery' => 'bi-images', 'related' => 'bi-diagram-3', 'seo' => 'bi-search'] as $key => $icon)
            <button type="button" @class(['is-active' => $tab === $key]) wire:click="$set('tab', '{{ $key }}')"><i class="bi {{ $icon }}"></i> {{ __('admin.services.tabs.'.$key) }}</button>
        @endforeach
    </div>

    <div class="a-layout">
        <div>
            <div class="a-card" @if($tab !== 'content') hidden @endif>
                <div class="a-card-body">
                    <x-admin.t-input model="form.title" :label="__('admin.fields.title')" required />
                    <x-admin.t-input model="form.short_description" :label="__('admin.fields.short_description')" :hint="__('admin.services.short_hint')" textarea rows="3" />
                    <div class="a-grid">
                        <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="__('admin.fields.image')" :hint="__('admin.services.image_hint')" />
                        <x-admin.input model="form.slug" :label="__('admin.fields.slug')" dir="ltr" required :hint="__('admin.common.slug_hint')" />
                    </div>
                </div>
            </div>

            <div class="a-card" @if($tab !== 'scope') hidden @endif>
                <div class="a-card-body">
                    <x-admin.list-items path="form.scope_items" :label="__('admin.services.scope_items')" :hint="__('admin.services.scope_hint')" />
                </div>
            </div>

            @if ($tab === 'gallery' && ! $service)
                <div class="a-card"><div class="a-card-body"><div class="a-note"><i class="bi bi-info-circle"></i><span>{{ __('admin.services.save_first') }}</span></div></div></div>
            @endif

            <div class="a-card" @if($tab !== 'related') hidden @endif>
                <div class="a-card-head"><div><h2>{{ __('admin.services.related') }}</h2><p>{{ __('admin.services.related_hint') }}</p></div></div>
                <div class="a-card-body">
                    <div class="row g-2">
                        @foreach ($allServices as $item)
                            <div class="col-md-6">
                                <label class="a-swatch" style="cursor:pointer">
                                    <input type="checkbox" class="form-check-input m-0" value="{{ $item->id }}" wire:model="form.related">
                                    <span class="flex-grow-1">{{ $item->title }}</span>
                                    @unless ($item->is_active)<span class="a-badge is-muted">{{ __('admin.common.inactive') }}</span>@endunless
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="a-card" @if($tab !== 'seo') hidden @endif>
                <div class="a-card-body">
                    <x-admin.seo-fields :image="$seoImage" :current="$seo['og_image']" :url="url(app()->getLocale().'/services/'.($form['slug'] ?: '…'))" :fallback-title="tval($form['title'])" :fallback-description="tval($form['short_description'])" />
                </div>
            </div>
        </div>

        <aside class="a-layout-aside">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.common.publish') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
                    <x-admin.toggle model="form.show_on_home" :label="__('admin.services.show_on_home')" :hint="__('admin.services.show_on_home_hint')" />
                    <x-admin.save-button class="w-100" />
                </div>
            </div>
            @if ($service)
                <div class="a-card">
                    <div class="a-card-body small">
                        <dl class="a-dl" style="grid-template-columns:110px 1fr">
                            <dt>{{ __('admin.common.created_at') }}</dt><dd class="num">{{ $service->created_at?->format('Y-m-d H:i') }}</dd>
                            <dt>{{ __('admin.common.updated_at') }}</dt><dd class="num">{{ $service->updated_at?->format('Y-m-d H:i') }}</dd>
                            <dt>{{ __('admin.common.url') }}</dt><dd class="ltr"><a href="{{ $service->url() }}" target="_blank" rel="noopener">/{{ app()->getLocale() }}/services/{{ $service->slug }}</a></dd>
                        </dl>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</form>
@if ($service)
    <div @if($tab !== 'gallery') hidden @endif class="mt-3">
        <livewire:admin.media.manager owner-type="service" :owner-id="$service->id" :allow-pdf="false" :key="'media-service-'.$service->id" />
    </div>
@endif
</div>
