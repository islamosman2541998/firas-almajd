<div>
    <x-admin.page-head :title="__('admin.nav.notifications')">
        <x-slot:actions>
            <button type="button" class="a-btn" wire:click="markAllRead"><i class="bi bi-check2-all"></i> {{ __('admin.notifications.mark_all') }}</button>
            <button type="button" class="a-btn a-btn-danger" x-on:click="confirmAction(() => $wire.clearRead())"><i class="bi bi-trash3"></i> {{ __('admin.notifications.clear_read') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    <div class="a-card">
        <div class="a-tabs px-3 mb-0">
            @foreach (['' => 'all', 'unread' => 'unread', 'message' => 'messages', 'application' => 'applications'] as $value => $label)
                <button type="button" @class(['is-active' => $filter === $value]) wire:click="$set('filter', '{{ $value }}')">{{ __('admin.notifications.filters.'.$label) }}</button>
            @endforeach
        </div>
        @forelse ($items as $item)
            <div @class(['a-notify-item', 'is-unread' => ! $item->read_at]) wire:key="n-{{ $item->id }}">
                <i class="bi {{ $item->data['icon'] ?? 'bi-bell' }}"></i>
                <span class="flex-grow-1">
                    <a href="{{ $item->data['url'] ?? '#' }}" wire:click="markRead('{{ $item->id }}')">
                        @if (($item->data['kind'] ?? '') === 'application')
                            {{ __('admin.notifications.application_line', ['name' => $item->data['name'] ?? '', 'job' => tval($item->data['job'] ?? '')]) }}
                        @else
                            {{ __('admin.notifications.message_line', ['name' => $item->data['name'] ?? '']) }}
                        @endif
                    </a>
                    @if (! empty($item->data['excerpt']))<small>{{ $item->data['excerpt'] }}</small>@endif
                    <small>{{ $item->created_at->format('Y-m-d H:i') }} · {{ $item->created_at->diffForHumans() }}</small>
                </span>
                <div class="a-actions">
                    @unless ($item->read_at)<button type="button" class="a-btn" wire:click="markRead('{{ $item->id }}')" title="{{ __('admin.notifications.mark_read') }}"><i class="bi bi-check2"></i></button>@endunless
                    <button type="button" class="a-btn is-danger" wire:click="delete('{{ $item->id }}')" title="{{ __('admin.common.delete') }}"><i class="bi bi-trash3"></i></button>
                </div>
            </div>
        @empty
            <x-admin.empty icon="bi-bell-slash" :text="__('admin.notifications.empty')" />
        @endforelse
        @if ($items->hasPages())<div class="a-table-foot">{{ $items->links() }}</div>@endif
    </div>
</div>
