@props(['edit' => null, 'editUrl' => null, 'delete' => null, 'view' => null])
<div class="a-actions">
    {{ $slot }}
    @if ($view)<a class="a-btn" href="{{ $view }}" target="_blank" rel="noopener" title="{{ __('admin.common.view') }}"><i class="bi bi-box-arrow-up-right"></i></a>@endif
    @if ($editUrl)<a class="a-btn" href="{{ $editUrl }}" title="{{ __('admin.common.edit') }}"><i class="bi bi-pencil"></i></a>@endif
    @if ($edit)<button type="button" class="a-btn" wire:click="{{ $edit }}" title="{{ __('admin.common.edit') }}"><i class="bi bi-pencil"></i></button>@endif
    @if ($delete)<button type="button" class="a-btn is-danger" x-on:click="confirmAction(() => $wire.{{ $delete }})" title="{{ __('admin.common.delete') }}"><i class="bi bi-trash3"></i></button>@endif
</div>
