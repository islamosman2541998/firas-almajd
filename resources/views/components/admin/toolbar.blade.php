@props(['export' => true, 'placeholder' => null])
@php($lw = \Livewire\Livewire::current())
<div class="a-toolbar">
    <div class="a-search">
        <i class="bi bi-search"></i>
        <input type="search" class="form-control" wire:model.live.debounce.350ms="search" placeholder="{{ $placeholder ?? __('admin.common.search') }}">
    </div>
    {{ $slot }}
    <div class="a-toolbar-end">
        @if ($lw->search !== '' || $lw->status !== '' || (property_exists($lw, 'filters') && array_filter($lw->filters)))
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" wire:click="resetFilters"><i class="bi bi-x-circle"></i> {{ __('admin.common.reset_filters') }}</button>
        @endif
        <select class="form-select" wire:model.live="perPage" aria-label="{{ __('admin.common.per_page') }}" style="min-width:80px">
            @foreach ([10, 15, 25, 50, 100] as $n)<option value="{{ $n }}">{{ $n }}</option>@endforeach
        </select>
        @if ($export)
            <button type="button" class="a-btn" wire:click="export" wire:loading.attr="disabled" wire:target="export">
                <span wire:loading.remove wire:target="export"><i class="bi bi-file-earmark-spreadsheet"></i></span>
                <span wire:loading wire:target="export" class="spin"></span>
                {{ __('admin.common.export') }}
            </button>
        @endif
    </div>
</div>
