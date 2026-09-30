<div>
    <x-admin.page-head :title="__('admin.nav.gallery')" :subtitle="__('admin.gallery.subtitle')">
        <x-slot:actions>
            <label class="a-btn" x-data="{ up: false }" x-on:livewire-upload-start="up = true" x-on:livewire-upload-finish="up = false" x-on:livewire-upload-error="up = false">
                <span x-show="!up"><i class="bi bi-cloud-arrow-up"></i></span><span x-show="up" x-cloak class="spin"></span>
                {{ __('admin.gallery.bulk_upload') }}
                <input type="file" multiple accept="image/*,video/mp4,video/webm,video/quicktime" wire:model="bulk" hidden>
            </label>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.gallery.create') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    @error('bulk.*')<div class="a-note mb-3" style="border-color:var(--a-danger)"><i class="bi bi-exclamation-triangle"></i>{{ $message }}</div>@enderror
    <div class="a-card">
        <x-admin.toolbar>
            <x-admin.status-filter />
            <select class="form-select" wire:model.live="filters.type"><option value="">{{ __('admin.sliders.all_types') }}</option><option value="image">{{ __('admin.sliders.types.image') }}</option><option value="video">{{ __('admin.sliders.types.video') }}</option></select>
            <select class="form-select" wire:model.live="filters.layout"><option value="">{{ __('admin.gallery.all_layouts') }}</option>@foreach (['normal', 'wide', 'tall'] as $l)<option value="{{ $l }}">{{ __('admin.media.layouts.'.$l) }}</option>@endforeach</select>
        </x-admin.toolbar>
        <x-admin.bulkbar />
        <div class="a-card-body">
            @if ($rows->isEmpty())
                <x-admin.empty icon="bi-grid-3x3-gap" />
            @else
                @if ($this->canReorder())<p class="a-hint mt-0 mb-3"><i class="bi bi-arrows-move"></i> {{ __('admin.common.drag_hint') }}</p>@endif
                <div class="a-media-grid" @if($this->canReorder()) wire:sort="reorder" @endif>
                    @foreach ($rows as $row)
                        <div @class(['a-media', 'is-off' => ! $row->is_active]) wire:key="g-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <div class="a-media-thumb">
                                <span class="a-media-type">@if ($row->isVideo())<i class="bi bi-play-fill"></i> {{ __('admin.sliders.types.video') }} · @endif{{ __('admin.media.layouts.'.$row->layout) }}</span>
                                @if ($this->canReorder())<span class="a-media-handle" wire:sort:handle><i class="bi bi-arrows-move"></i></span>@endif
                                @if ($row->image)
                                    <img src="{{ media_url($row->image) }}" alt="" loading="lazy">
                                @elseif ($row->isVideo())
                                    <video src="{{ media_url($row->video) }}#t=0.5" muted preload="metadata"></video>
                                @endif
                            </div>
                            <div class="a-media-body">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" value="{{ $row->id }}" wire:model.live="selected">
                                    <strong class="flex-grow-1">{{ $row->title ?: __('admin.common.untitled') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" />
                                    <x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.gallery.edit') : __('admin.gallery.create')">
            <div class="a-field">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" id="g-type-image" value="image" wire:model.live="form.type">
                    <label class="a-btn" for="g-type-image"><i class="bi bi-image"></i> {{ __('admin.sliders.types.image') }}</label>
                    <input type="radio" class="btn-check" id="g-type-video" value="video" wire:model.live="form.type">
                    <label class="a-btn" for="g-type-video"><i class="bi bi-camera-video"></i> {{ __('admin.sliders.types.video') }}</label>
                </div>
            </div>
            @if ($form['type'] === 'video')
                <x-admin.upload model="video" kind="video" accept="video/mp4,video/webm,video/quicktime" :file="$video" :current="$form['video']" :label="__('admin.sliders.video')" :hint="__('admin.sliders.video_hint')" />
                <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="__('admin.gallery.cover')" :hint="__('admin.gallery.cover_hint')" remove="removeImage" />
            @else
                <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="__('admin.fields.image')" />
            @endif
            <x-admin.t-input model="form.title" :label="__('admin.fields.title')" :hint="__('admin.gallery.title_hint')" stacked />
            <x-admin.select model="form.layout" :label="__('admin.media.layout')" :options="['normal' => __('admin.media.layouts.normal'), 'wide' => __('admin.media.layouts.wide'), 'tall' => __('admin.media.layouts.tall')]" :hint="__('admin.media.layout_hint')" />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
