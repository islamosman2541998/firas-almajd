<div class="position-relative" wire:poll.{{ $interval }}s x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false">
    <button type="button" class="a-icon-btn" x-on:click="open = !open" :aria-expanded="open" aria-label="{{ __('admin.nav.notifications') }}">
        <i class="bi bi-bell"></i>
        @if ($count)<span class="a-dot">{{ $count > 99 ? '99+' : $count }}</span>@endif
    </button>
    <div class="dropdown-menu dropdown-menu-end a-dropdown a-notify-menu" :class="open && 'show'" style="inset-inline-end:0;inset-inline-start:auto;top:calc(100% + 8px)">
        <div class="a-notify-head">
            <span>{{ __('admin.nav.notifications') }}</span>
            @if ($count)<button type="button" wire:click="markAllRead">{{ __('admin.notifications.mark_all') }}</button>@endif
        </div>
        <div class="a-notify-list">
            @forelse ($items as $item)
                <a href="#" wire:click.prevent="open('{{ $item->id }}')" @class(['a-notify-item', 'is-unread' => ! $item->read_at])>
                    <i class="bi {{ $item->data['icon'] ?? 'bi-bell' }}"></i>
                    <span>
                        @if (($item->data['kind'] ?? '') === 'application')
                            {{ __('admin.notifications.application_line', ['name' => $item->data['name'] ?? '', 'job' => tval($item->data['job'] ?? '')]) }}
                        @else
                            {{ __('admin.notifications.message_line', ['name' => $item->data['name'] ?? '']) }}
                        @endif
                        <small>{{ $item->created_at->diffForHumans() }}</small>
                    </span>
                </a>
            @empty
                <x-admin.empty icon="bi-bell-slash" :text="__('admin.notifications.empty')" />
            @endforelse
        </div>
        <a class="a-notify-foot" href="{{ route('admin.notifications') }}">{{ __('admin.common.view_all') }}</a>
    </div>
</div>
