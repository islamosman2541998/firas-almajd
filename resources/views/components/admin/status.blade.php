@props(['active', 'action' => null, 'on' => null, 'off' => null])
@if ($action)
    <button type="button" wire:click="{{ $action }}" @class(['a-badge', 'is-success' => $active, 'is-muted' => ! $active]) title="{{ __('admin.common.toggle_status') }}">{{ $active ? ($on ?? __('admin.common.active')) : ($off ?? __('admin.common.inactive')) }}</button>
@else
    <span @class(['a-badge', 'is-success' => $active, 'is-muted' => ! $active])>{{ $active ? ($on ?? __('admin.common.active')) : ($off ?? __('admin.common.inactive')) }}</span>
@endif
