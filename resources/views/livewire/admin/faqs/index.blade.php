<div>
    <x-admin.page-head :title="__('admin.nav.faqs')" :subtitle="__('admin.faqs.subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.faqs.create') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    <div class="a-card">
        <x-admin.toolbar><x-admin.status-filter /></x-admin.toolbar>
        <x-admin.bulkbar />
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr>
                    <x-admin.check-all :rows="$rows" />
                    @if ($this->canReorder())<th class="w-drag"></th>@endif
                    <th>{{ __('admin.faqs.question') }}</th><th>{{ __('admin.common.status') }}</th><th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td class="a-title-cell"><strong>{{ $row->question }}</strong><small>{{ \Illuminate\Support\Str::limit($row->answer, 120) }}</small></td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-admin.empty icon="bi-question-square" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.faqs.edit') : __('admin.faqs.create')" wide>
            <x-admin.t-input model="form.question" :label="__('admin.faqs.question')" required />
            <x-admin.t-input model="form.answer" :label="__('admin.faqs.answer')" textarea rows="5" required />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
