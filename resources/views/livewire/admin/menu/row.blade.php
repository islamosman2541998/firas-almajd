@php
    $target = match ($item->type) {
        'route' => $routes[$item->route_name] ?? $item->route_name,
        'service' => $serviceTitles[$item->linkable_id] ?? __('admin.menu.missing'),
        'page' => $pageTitles[$item->linkable_id] ?? __('admin.menu.missing'),
        'url' => $item->url,
        default => __('admin.menu.types.none'),
    };
@endphp
<div class="a-tree-row">
    <i class="bi bi-grip-vertical a-drag" wire:sort:handle></i>
    <div class="a-title-cell">
        <strong>{{ $item->title }} @if ($item->new_tab)<i class="bi bi-box-arrow-up-right small text-muted"></i>@endif</strong>
        <small>{{ $target }}</small>
    </div>
    <span class="a-type-chip d-none d-md-inline">{{ __('admin.menu.types.'.$item->type) }}</span>
    <x-admin.status :active="$item->is_active" :action="'toggleActive('.$item->id.')'" />
    <div class="a-actions">
        @unless ($child)
            <button type="button" class="a-btn" wire:click="openQuick({{ $item->id }})" title="{{ __('admin.menu.quick_title') }}"><i class="bi bi-ui-checks"></i></button>
            <button type="button" class="a-btn" wire:click="create({{ $item->id }})" title="{{ __('admin.menu.add_child') }}"><i class="bi bi-node-plus"></i></button>
        @endunless
        <button type="button" class="a-btn" wire:click="edit({{ $item->id }})" title="{{ __('admin.common.edit') }}"><i class="bi bi-pencil"></i></button>
        <button type="button" class="a-btn is-danger" x-on:click="confirmAction(() => $wire.delete({{ $item->id }}), {text: @js($item->children->isNotEmpty() ? __('admin.menu.delete_children') : __('admin.confirm.text'))})" title="{{ __('admin.common.delete') }}"><i class="bi bi-trash3"></i></button>
    </div>
</div>
