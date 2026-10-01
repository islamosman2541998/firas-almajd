<div class="a-card">
    <div class="a-card-head">
        <div><h2>{{ __('admin.media.title') }}</h2><p>{{ __('admin.media.hint') }}</p></div>
        <span class="a-badge no-dot num">{{ $items->count() }}</span>
    </div>
    <div class="a-card-body">
        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <label class="a-dropzone" x-data="{ over: false, p: 0, up: false }" :class="over && 'is-over'"
                    x-on:dragover.prevent="over = true" x-on:dragleave="over = false" x-on:drop="over = false"
                    x-on:livewire-upload-start="up = true" x-on:livewire-upload-finish="up = false" x-on:livewire-upload-error="up = false" x-on:livewire-upload-progress="p = $event.detail.progress">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>{{ __('admin.media.drop') }}</strong>
                    <span>{{ $allowPdf ? __('admin.media.types') : __('admin.media.types_visual') }}</span>
                    <input type="file" multiple wire:model="uploads" accept="image/*,video/mp4,video/webm{{ $allowPdf ? ',application/pdf' : '' }}">
                    <div class="a-progress w-100" x-show="up" x-cloak><span :style="`width:${p}%`"></span></div>
                </label>
                @error('uploads.*')<div class="a-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-lg-5">
                <label class="a-label">{{ __('admin.media.embed_url') }}</label>
                <div class="d-flex gap-2">
                    <input type="url" class="form-control @error('embedUrl') is-invalid @enderror" dir="ltr" wire:model="embedUrl" placeholder="https://www.youtube.com/watch?v=…" wire:keydown.enter.prevent="addEmbed">
                    <button type="button" class="a-btn" wire:click="addEmbed"><i class="bi bi-plus-lg"></i></button>
                </div>
                @error('embedUrl')<div class="a-error">{{ $message }}</div>@enderror
                <small class="a-hint">{{ __('admin.media.embed_hint') }}</small>
            </div>
        </div>

        @if ($items->isEmpty())
            <x-admin.empty icon="bi-collection-play" :text="__('admin.media.empty')" />
        @else
            <div class="a-media-grid" wire:sort="reorder">
                @foreach ($items as $item)
                    <div class="a-media" wire:key="media-{{ $item->id }}" wire:sort:item="{{ $item->id }}">
                        <div class="a-media-thumb">
                            <span class="a-media-type">{{ strtoupper($item->type === 'embed' ? 'video' : $item->type) }}</span>
                            <span class="a-media-handle" wire:sort:handle><i class="bi bi-arrows-move"></i></span>
                            @if ($thumb = $item->thumbnail())
                                <img src="{{ $thumb }}" alt="" loading="lazy">
                            @elseif ($item->type === 'video')
                                <video src="{{ $item->fileUrl() }}#t=0.5" muted preload="metadata"></video>
                            @elseif ($item->type === 'pdf')
                                <i class="bi bi-file-earmark-pdf"></i>
                            @else
                                <i class="bi bi-play-btn"></i>
                            @endif
                        </div>
                        <div class="a-media-body">
                            <strong>{{ $item->title ?: ($item->path ? basename($item->path) : $item->url) }}</strong>
                            <small class="text-muted">{{ __('admin.media.layouts.'.$item->layout) }}@if ($item->size) · <span dir="ltr">{{ $item->humanSize() }}</span>@endif</small>
                            <div class="a-actions">
                                <button type="button" class="a-btn" wire:click="edit({{ $item->id }})" title="{{ __('admin.common.edit') }}"><i class="bi bi-pencil"></i></button>
                                @if ($item->path)<a class="a-btn" href="{{ $item->fileUrl() }}" target="_blank" rel="noopener" title="{{ __('admin.common.view') }}"><i class="bi bi-box-arrow-up-right"></i></a>@endif
                                <button type="button" class="a-btn is-danger" x-on:click="confirmAction(() => $wire.delete({{ $item->id }}))" title="{{ __('admin.common.delete') }}"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($editingId)
        <x-admin.drawer :title="__('admin.media.edit')">
            <x-admin.t-input model="form.title" :label="__('admin.fields.title')" stacked />
            <x-admin.select model="form.layout" :label="__('admin.media.layout')" :options="['normal' => __('admin.media.layouts.normal'), 'wide' => __('admin.media.layouts.wide'), 'tall' => __('admin.media.layouts.tall')]" :hint="__('admin.media.layout_hint')" />
            @if ($form['type'] === 'embed')
                <x-admin.input model="form.url" :label="__('admin.media.embed_url')" dir="ltr" />
            @endif
            @if (in_array($form['type'], ['video', 'embed']))
                <x-admin.upload model="poster" :file="$poster" :current="$form['poster']" :label="__('admin.media.poster')" />
            @endif
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
