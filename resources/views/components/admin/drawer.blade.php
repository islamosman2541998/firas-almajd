@props(['title', 'close' => 'closeForm', 'wide' => false])
<div x-data x-init="$nextTick(() => $el.querySelector('.a-drawer-body input:not([type=file]):not([type=checkbox]), .a-drawer-body textarea')?.focus())" x-on:keydown.escape.window="$wire.{{ $close }}()">
    <div class="a-drawer-backdrop" wire:click="{{ $close }}"></div>
    <aside @class(['a-drawer', 'is-wide' => $wide]) role="dialog" aria-modal="true" aria-label="{{ $title }}">
        <div class="a-drawer-head">
            <h2>{{ $title }}</h2>
            <button type="button" class="a-icon-btn" wire:click="{{ $close }}" aria-label="{{ __('admin.common.close') }}"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="a-drawer-body">{{ $slot }}</div>
        @isset($footer)<div class="a-drawer-foot">{{ $footer }}</div>@endisset
    </aside>
</div>
