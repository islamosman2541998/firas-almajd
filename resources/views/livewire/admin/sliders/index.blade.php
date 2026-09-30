<div>
    <x-admin.page-head :title="__('admin.nav.sliders')" :subtitle="__('admin.sliders.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'home']) }}"><i class="bi bi-gear"></i> {{ __('admin.sliders.settings') }}</a>
            <a class="a-btn a-btn-primary" href="{{ route('admin.sliders.create') }}"><i class="bi bi-plus-lg"></i> {{ __('admin.sliders.create') }}</a>
        </x-slot:actions>
    </x-admin.page-head>

    <div class="a-card">
        <x-admin.toolbar>
            <x-admin.status-filter />
            <select class="form-select" wire:model.live="filters.media_type">
                <option value="">{{ __('admin.sliders.all_types') }}</option>
                <option value="image">{{ __('admin.sliders.types.image') }}</option>
                <option value="video">{{ __('admin.sliders.types.video') }}</option>
            </select>
        </x-admin.toolbar>
        <x-admin.bulkbar />
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr>
                    <x-admin.check-all :rows="$rows" />
                    @if ($this->canReorder())<th class="w-drag"></th>@endif
                    <th>{{ __('admin.fields.image') }}</th>
                    <th>{{ __('admin.fields.title') }}</th>
                    <th>{{ __('admin.sliders.button') }}</th>
                    <th>{{ __('admin.common.status') }}</th>
                    <th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td>
                                @if ($row->isVideo())
                                    <span class="a-badge is-info no-dot"><i class="bi bi-camera-video"></i> {{ __('admin.sliders.types.video') }}</span>
                                @else
                                    <img class="a-thumb" src="{{ media_url($row->image) }}" alt="" loading="lazy">
                                @endif
                            </td>
                            <td class="a-title-cell"><strong>{{ $row->title ?: '—' }}</strong><small>{{ \Illuminate\Support\Str::limit($row->description, 80) }}</small></td>
                            <td>@if ($row->show_button && $row->button_text)<span class="a-badge no-dot" @if($row->button_bg) style="background:{{ $row->button_bg }};color:{{ $row->button_color ?: '#231f20' }};border-color:{{ $row->button_bg }}" @endif>{{ $row->button_text }}</span>@else — @endif</td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :edit-url="route('admin.sliders.edit', $row)" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-admin.empty icon="bi-images" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
