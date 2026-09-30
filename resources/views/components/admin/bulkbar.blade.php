@props(['activate' => true])
@php($lw = \Livewire\Livewire::current())
@if (count($lw->selected))
    <div class="a-bulkbar">
        <span>{{ __('admin.common.selected', ['count' => count($lw->selected)]) }}</span>
        {{ $slot }}
        @if ($activate)
            <button type="button" class="a-btn a-btn-sm" wire:click="bulkActive(true)"><i class="bi bi-eye"></i> {{ __('admin.common.activate') }}</button>
            <button type="button" class="a-btn a-btn-sm" wire:click="bulkActive(false)"><i class="bi bi-eye-slash"></i> {{ __('admin.common.deactivate') }}</button>
        @endif
        <button type="button" class="a-btn a-btn-sm a-btn-danger" x-on:click="confirmAction(() => $wire.bulkDelete())"><i class="bi bi-trash3"></i> {{ __('admin.common.delete') }}</button>
        <button type="button" class="a-btn a-btn-sm a-btn-ghost" wire:click="$set('selected', [])">{{ __('admin.common.clear_selection') }}</button>
    </div>
@endif
