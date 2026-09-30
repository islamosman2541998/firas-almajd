<div>
    <x-admin.page-head :title="__('admin.nav.translations')" :subtitle="__('admin.translations.subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn" wire:click="sync"><i class="bi bi-arrow-repeat"></i> {{ __('admin.translations.sync') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    <div class="a-note mb-3"><i class="bi bi-info-circle"></i><span>{{ __('admin.translations.note') }}</span></div>
    <div class="a-card">
        <x-admin.toolbar />
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr><x-admin.sort-th field="key" :label="__('admin.translations.key')" /><th>العربية</th><th>English</th><th class="w-actions"></th></tr></thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="t-{{ $row->id }}">
                            <td class="ltr small text-muted">{{ $row->key }}</td>
                            <td dir="rtl">{{ $row->value['ar'] ?? '' }}</td>
                            <td dir="ltr">{{ $row->value['en'] ?? '' }}</td>
                            <td class="w-actions">
                                <div class="a-actions">
                                    <button type="button" class="a-btn" wire:click="edit({{ $row->id }})" title="{{ __('admin.common.edit') }}"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="a-btn" x-on:click="confirmAction(() => $wire.restore({{ $row->id }}), {icon: 'question', text: @js(__('admin.translations.restore_confirm'))})" title="{{ __('admin.translations.restore') }}"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><x-admin.empty icon="bi-translate" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="__('admin.translations.edit')">
            <div class="a-field"><label class="a-label">{{ __('admin.translations.key') }}</label><div class="form-control ltr" style="background:var(--a-table-head)">{{ $form['key'] }}</div></div>
            <x-admin.t-input model="form.value" :label="__('admin.translations.text')" textarea rows="3" stacked required />
            <small class="a-hint">{{ __('admin.translations.placeholder_hint') }}</small>
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
