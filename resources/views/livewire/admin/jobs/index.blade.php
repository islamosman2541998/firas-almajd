<div>
    <x-admin.page-head :title="__('admin.nav.jobs')" :subtitle="__('admin.jobs.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'careers']) }}"><i class="bi bi-layout-text-window"></i> {{ __('admin.jobs.form_texts') }}</a>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.jobs.create') }}</button>
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
                    <th>{{ __('admin.fields.title') }}</th><th>{{ __('admin.jobs.type') }}</th><th>{{ __('admin.jobs.location') }}</th><x-admin.sort-th field="applications_count" :label="__('admin.nav.applications')" /><th>{{ __('admin.common.status') }}</th><th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td class="a-title-cell"><strong>{{ $row->title }}</strong><small>{{ \Illuminate\Support\Str::limit($row->description, 70) }}</small></td>
                            <td>{{ $row->employment_type ?: '—' }}</td>
                            <td>{{ $row->location ?: '—' }}</td>
                            <td><a class="a-badge no-dot" href="{{ route('admin.applications.index', ['filters' => ['job' => $row->id]]) }}"><span class="num">{{ $row->applications_count }}</span>@if ($row->new_applications_count)<span class="text-danger">+{{ $row->new_applications_count }}</span>@endif</a></td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-admin.empty icon="bi-briefcase" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.jobs.edit') : __('admin.jobs.create')" wide>
            <x-admin.t-input model="form.title" :label="__('admin.fields.title')" required />
            <x-admin.t-input model="form.description" :label="__('admin.fields.description')" textarea rows="3" />
            <x-admin.t-input model="form.employment_type" :label="__('admin.jobs.type')" />
            <x-admin.t-input model="form.location" :label="__('admin.jobs.location')" />
            <x-admin.toggle model="form.is_active" :label="__('admin.jobs.open')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
