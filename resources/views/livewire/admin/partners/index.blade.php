<div>
    <x-admin.page-head :title="__('admin.nav.partners')" :subtitle="__('admin.partners.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'home', 'section' => 'partners']) }}"><i class="bi bi-gear"></i> {{ __('admin.partners.section') }}</a>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.partners.create') }}</button>
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
                    <th>{{ __('admin.partners.logo') }}</th><x-admin.sort-th field="name" :label="__('admin.fields.name')" /><th>{{ __('admin.common.url') }}</th><th>{{ __('admin.common.status') }}</th><th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td><img class="a-thumb is-contain" src="{{ media_url($row->logo) }}" alt="" loading="lazy"></td>
                            <td><strong>{{ $row->name }}</strong></td>
                            <td class="ltr small">@if ($row->url)<a href="{{ $row->url }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit($row->url, 40) }}</a>@else — @endif</td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-admin.empty icon="bi-people" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.partners.edit') : __('admin.partners.create')">
            <x-admin.input model="form.name" :label="__('admin.fields.name')" required />
            <x-admin.upload model="logo" accept="image/*,.svg" :file="$logo" :current="$form['logo']" :label="__('admin.partners.logo')" :hint="__('admin.partners.logo_hint')" />
            <x-admin.input model="form.url" :label="__('admin.partners.url')" dir="ltr" placeholder="https://" :hint="__('admin.partners.url_hint')" />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
