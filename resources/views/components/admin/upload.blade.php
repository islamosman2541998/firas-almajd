@props(['model', 'label' => null, 'file' => null, 'current' => null, 'accept' => 'image/*', 'hint' => null, 'dark' => false, 'remove' => null, 'kind' => 'image'])
@php
    $id = 'up_'.str_replace(['.', '-'], '_', $model);
    $previewable = $file && method_exists($file, 'isPreviewable') && $file->isPreviewable();
@endphp
<div class="a-field" x-data="{ p: 0, up: false }" x-on:livewire-upload-start="up = true" x-on:livewire-upload-finish="up = false; p = 0" x-on:livewire-upload-error="up = false" x-on:livewire-upload-progress="p = $event.detail.progress">
    @if ($label)<label class="a-label">{{ $label }}</label>@endif
    <div class="a-upload">
        <div @class(['a-upload-preview', 'is-dark' => $dark])>
            @if ($previewable && $kind === 'video')
                <video src="{{ $file->temporaryUrl() }}" muted></video>
            @elseif ($previewable)
                <img src="{{ $file->temporaryUrl() }}" alt="">
            @elseif ($file)
                <i class="bi bi-file-earmark-check"></i>
            @elseif ($current && $kind === 'video')
                <video src="{{ media_url($current) }}" muted preload="metadata"></video>
            @elseif ($current && $kind === 'pdf')
                <i class="bi bi-file-earmark-pdf"></i>
            @elseif ($current)
                <img src="{{ media_url($current) }}" alt="" loading="lazy">
            @else
                <i class="bi bi-{{ $kind === 'video' ? 'camera-video' : ($kind === 'pdf' ? 'file-earmark-pdf' : 'image') }}"></i>
            @endif
        </div>
        <div class="a-upload-body">
            <span class="a-upload-name">{{ $file ? $file->getClientOriginalName() : ($current ? basename($current) : __('admin.upload.none')) }}</span>
            @if ($hint)<span>{{ $hint }}</span>@endif
            <div class="a-upload-actions">
                <label class="a-btn a-btn-sm" for="{{ $id }}"><i class="bi bi-upload"></i> {{ $current || $file ? __('admin.upload.replace') : __('admin.upload.choose') }}</label>
                <input type="file" id="{{ $id }}" wire:model="{{ $model }}" accept="{{ $accept }}">
                @if ($file)
                    <button type="button" class="a-btn a-btn-sm a-btn-ghost" wire:click="$set('{{ $model }}', null)">{{ __('admin.upload.cancel') }}</button>
                @elseif ($current && $remove)
                    <button type="button" class="a-btn a-btn-sm a-btn-danger" wire:click="{{ $remove }}">{{ __('admin.upload.remove') }}</button>
                @endif
            </div>
            <div class="a-progress" x-show="up" x-cloak><span :style="`width:${p}%`"></span></div>
        </div>
    </div>
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
</div>
