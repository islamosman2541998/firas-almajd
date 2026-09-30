<div>
    <x-admin.page-head :title="__('admin.nav.users')" :subtitle="__('admin.users.subtitle')">
        <x-slot:actions><button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.users.create') }}</button></x-slot:actions>
    </x-admin.page-head>
    <div class="a-card">
        <x-admin.toolbar><x-admin.status-filter /></x-admin.toolbar>
        <x-admin.bulkbar :activate="false" />
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr>
                    <x-admin.check-all :rows="$rows" />
                    <x-admin.sort-th field="name" :label="__('admin.fields.name')" /><x-admin.sort-th field="email" :label="__('admin.fields.email')" /><th>{{ __('admin.common.language') }}</th><x-admin.sort-th field="last_login_at" :label="__('admin.users.last_login')" /><th>{{ __('admin.common.status') }}</th><th class="w-actions"></th>
                </tr></thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>@if ($row->id !== auth()->id())<input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected">@endif</td>
                            <td><div class="d-flex align-items-center gap-2"><span class="a-avatar" style="width:30px;height:30px;font-size:12px">{{ $row->initials() }}</span><strong>{{ $row->name }}</strong>@if ($row->id === auth()->id())<span class="a-badge is-info no-dot">{{ __('admin.users.you') }}</span>@endif</div></td>
                            <td class="ltr small">{{ $row->email }}</td>
                            <td>{{ strtoupper($row->locale) }}</td>
                            <td class="small text-muted num">{{ $row->last_login_at?->diffForHumans() ?? '—' }}</td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="$row->id !== auth()->id() ? 'delete('.$row->id.')' : null" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-admin.empty icon="bi-shield-lock" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.users.edit') : __('admin.users.create')">
            <x-admin.input model="form.name" :label="__('admin.fields.name')" required />
            <x-admin.input model="form.email" type="email" :label="__('admin.fields.email')" dir="ltr" required />
            <x-admin.input model="form.password" type="password" :label="__('admin.fields.password')" dir="ltr" autocomplete="new-password" :required="! $editingId" :hint="$editingId ? __('admin.users.password_keep') : __('admin.users.password_rule')" />
            <x-admin.select model="form.locale" :label="__('admin.users.locale')" :options="collect(locales())->map(fn ($l) => $l['name'])->all()" />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
