<div>
    <x-admin.page-head :title="__('admin.nav.projects')" :subtitle="__('admin.projects.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'projects']) }}"><i class="bi bi-layout-text-window"></i> {{ __('admin.common.page_texts') }}</a>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.projects.create') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    <div class="a-card">
        <x-admin.toolbar>
            <x-admin.status-filter />
            <select class="form-select" wire:model.live="filters.service"><option value="">{{ __('admin.projects.all_services') }}</option>@foreach ($services as $id => $title)<option value="{{ $id }}">{{ $title }}</option>@endforeach</select>
        </x-admin.toolbar>
        <x-admin.bulkbar />
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr>
                    <x-admin.check-all :rows="$rows" />
                    @if ($this->canReorder())<th class="w-drag"></th>@endif
                    <th>{{ __('admin.fields.image') }}</th><th>{{ __('admin.fields.title') }}</th><th>{{ __('admin.projects.location') }}</th><th>{{ __('admin.projects.service') }}</th><th>{{ __('admin.common.status') }}</th><th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td><img class="a-thumb" src="{{ media_url($row->image) }}" alt="" loading="lazy"></td>
                            <td class="a-title-cell"><strong>{{ $row->title }}</strong><small>{{ $row->category }}</small></td>
                            <td>{{ $row->location ?: '—' }}</td>
                            <td class="small">{{ $row->service?->title ?? '—' }}</td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-admin.empty icon="bi-buildings" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.projects.edit') : __('admin.projects.create')">
            <x-admin.t-input model="form.title" :label="__('admin.fields.title')" required stacked />
            <x-admin.t-input model="form.category" :label="__('admin.projects.category')" :hint="__('admin.projects.category_hint')" stacked />
            <x-admin.t-input model="form.location" :label="__('admin.projects.location')" />
            <x-admin.select model="form.service_id" :label="__('admin.projects.service')" :options="$services" :placeholder="__('admin.common.none')" />
            <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="__('admin.fields.image')" />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
