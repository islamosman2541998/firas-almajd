<div>
    <x-admin.page-head :title="__('admin.nav.messages')" :subtitle="__('admin.messages.subtitle')" />
    <div class="a-card">
        <x-admin.toolbar>
            <select class="form-select" wire:model.live="status"><option value="">{{ __('admin.common.all_statuses') }}</option>@foreach (\App\Models\ContactMessage::STATUSES as $s)<option value="{{ $s }}">{{ __('admin.messages.statuses.'.$s) }}</option>@endforeach</select>
            <select class="form-select" wire:model.live="filters.service"><option value="">{{ __('admin.messages.all_services') }}</option>@foreach ($services as $id => $title)<option value="{{ $id }}">{{ $title }}</option>@endforeach</select>
            <input type="date" class="form-control" style="width:auto" wire:model.live="filters.from" title="{{ __('admin.common.from') }}">
            <input type="date" class="form-control" style="width:auto" wire:model.live="filters.to" title="{{ __('admin.common.to') }}">
        </x-admin.toolbar>
        <x-admin.bulkbar :activate="false">
            @foreach (\App\Models\ContactMessage::STATUSES as $s)
                <button type="button" class="a-btn a-btn-sm" wire:click="bulkStatus('{{ $s }}')">{{ __('admin.messages.statuses.'.$s) }}</button>
            @endforeach
        </x-admin.bulkbar>
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr>
                    <x-admin.check-all :rows="$rows" />
                    <x-admin.sort-th field="name" :label="__('admin.fields.name')" /><th>{{ __('admin.fields.phone') }}</th><th>{{ __('admin.messages.service') }}</th><th>{{ __('admin.messages.message') }}</th><th>{{ __('admin.common.status') }}</th><x-admin.sort-th field="created_at" :label="__('admin.common.date')" /><th class="w-actions"></th>
                </tr></thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" @class(['is-unread' => $row->status === 'new'])>
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td class="a-title-cell"><strong>{{ $row->name }}</strong><small class="ltr">{{ $row->email }}</small></td>
                            <td class="ltr small"><a href="tel:{{ $row->phone }}">{{ $row->phone }}</a></td>
                            <td class="small">{{ $row->service?->title ?? '—' }}</td>
                            <td class="small text-muted" style="max-width:280px">{{ \Illuminate\Support\Str::limit($row->message, 70) }}</td>
                            <td>@include('livewire.admin.partials.inbox-status', ['status' => $row->status, 'group' => 'messages'])</td>
                            <td class="small text-muted num" title="{{ $row->created_at }}">{{ $row->created_at->diffForHumans() }}</td>
                            <td class="w-actions"><x-admin.row-actions :delete="'delete('.$row->id.')'"><button type="button" class="a-btn" wire:click="view({{ $row->id }})" title="{{ __('admin.common.view') }}"><i class="bi bi-eye"></i></button></x-admin.row-actions></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-admin.empty icon="bi-envelope-open" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>

    @if ($viewing)
        <x-admin.drawer :title="__('admin.messages.view')" close="closeView">
            <dl class="a-dl mb-4">
                <dt>{{ __('admin.fields.name') }}</dt><dd><strong>{{ $viewing->name }}</strong></dd>
                <dt>{{ __('admin.fields.phone') }}</dt><dd class="ltr"><a href="tel:{{ $viewing->phone }}">{{ $viewing->phone }}</a></dd>
                <dt>{{ __('admin.fields.email') }}</dt><dd class="ltr">@if ($viewing->email)<a href="mailto:{{ $viewing->email }}">{{ $viewing->email }}</a>@else — @endif</dd>
                <dt>{{ __('admin.messages.service') }}</dt><dd>{{ $viewing->service?->title ?? '—' }}</dd>
                <dt>{{ __('admin.common.language') }}</dt><dd>{{ config('site.locales.'.$viewing->locale.'.name') }}</dd>
                <dt>{{ __('admin.common.date') }}</dt><dd class="num">{{ $viewing->created_at->format('Y-m-d H:i') }}</dd>
            </dl>
            <label class="a-label">{{ __('admin.messages.message') }}</label>
            <div class="a-message-box mb-4">{{ $viewing->message }}</div>
            <label class="a-label">{{ __('admin.common.status') }}</label>
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach (\App\Models\ContactMessage::STATUSES as $s)
                    <button type="button" @class(['a-btn a-btn-sm', 'a-btn-primary' => $viewing->status === $s]) wire:click="setStatus({{ $viewing->id }}, '{{ $s }}')">{{ __('admin.messages.statuses.'.$s) }}</button>
                @endforeach
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="a-btn a-btn-accent" href="https://wa.me/{{ preg_replace('/\D+/', '', $viewing->phone) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                <a class="a-btn" href="tel:{{ $viewing->phone }}"><i class="bi bi-telephone"></i> {{ __('admin.common.call') }}</a>
                @if ($viewing->email)<a class="a-btn" href="mailto:{{ $viewing->email }}"><i class="bi bi-reply"></i> {{ __('admin.common.reply_email') }}</a>@endif
            </div>
            <x-slot:footer>
                <button type="button" class="a-btn a-btn-danger me-auto" x-on:click="confirmAction(() => { $wire.delete({{ $viewing->id }}); $wire.closeView() })"><i class="bi bi-trash3"></i> {{ __('admin.common.delete') }}</button>
                <button type="button" class="a-btn" wire:click="closeView">{{ __('admin.common.close') }}</button>
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
