<div>
    <x-admin.page-head :title="__('admin.nav.certificates')" :subtitle="__('admin.certificates.subtitle')">
        <x-slot:actions>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.certificates.create') }}</button>
        </x-slot:actions>
    </x-admin.page-head>
    <div class="a-card">
        <x-admin.toolbar><x-admin.status-filter /></x-admin.toolbar>
        <x-admin.bulkbar />
        <div class="a-card-body">
            @if ($rows->isEmpty())
                <x-admin.empty icon="bi-patch-check" />
            @else
                <div class="a-media-grid" @if($this->canReorder()) wire:sort="reorder" @endif>
                    @foreach ($rows as $index => $row)
                        <div @class(['a-media', 'is-off' => ! $row->is_active]) wire:key="c-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <div class="a-media-thumb" style="aspect-ratio:3/4;background:#e9e5db">
                                @if ($row->file)<span class="a-media-type">PDF</span>@endif
                                @if ($this->canReorder())<span class="a-media-handle" wire:sort:handle><i class="bi bi-arrows-move"></i></span>@endif
                                <img src="{{ media_url($row->image) }}" alt="" loading="lazy" style="object-fit:contain;padding:10px">
                            </div>
                            <div class="a-media-body">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input m-0" value="{{ $row->id }}" wire:model.live="selected">
                                    <strong class="flex-grow-1">{{ $row->title ?: __('admin.certificates.number', ['n' => $rows->firstItem() + $index]) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" />
                                    <x-admin.row-actions :edit="'edit('.$row->id.')'" :delete="'delete('.$row->id.')'" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
    @if ($formOpen)
        <x-admin.drawer :title="$editingId ? __('admin.certificates.edit') : __('admin.certificates.create')">
            <x-admin.upload model="image" accept="image/*,.svg" :file="$image" :current="$form['image']" :label="__('admin.certificates.image')" :hint="__('admin.certificates.image_hint')" />
            <x-admin.upload model="file" kind="pdf" accept="application/pdf" :file="$file" :current="$form['file']" :label="__('admin.certificates.file')" :hint="__('admin.certificates.file_hint')" remove="removeFile" />
            <x-admin.t-input model="form.title" :label="__('admin.fields.title')" :hint="__('admin.certificates.title_hint')" stacked />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
