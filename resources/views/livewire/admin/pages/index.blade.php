<div>
    <x-admin.page-head :title="__('admin.nav.pages')" :subtitle="__('admin.pages.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'page']) }}"><i class="bi bi-gear"></i> {{ __('admin.pages.labels') }}</a>
            <a class="a-btn a-btn-primary" href="{{ route('admin.pages.create') }}"><i class="bi bi-plus-lg"></i> {{ __('admin.pages.create') }}</a>
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
                    <th>{{ __('admin.fields.image') }}</th>
                    <th>{{ __('admin.fields.title') }}</th>
                    <x-admin.sort-th field="slug" :label="__('admin.fields.slug')" />
                    <th>{{ __('admin.pages.media_count') }}</th>
                    <th>{{ __('admin.nav.menu') }}</th>
                    <th>{{ __('admin.common.status') }}</th>
                    <th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td>@if ($row->image)<img class="a-thumb" src="{{ media_url($row->image) }}" alt="" loading="lazy">@else<span class="a-thumb d-inline-grid" style="place-items:center"><i class="bi bi-file-earmark"></i></span>@endif</td>
                            <td class="a-title-cell"><strong>{{ $row->title }}</strong><small>{{ \Illuminate\Support\Str::limit($row->description, 70) }}</small></td>
                            <td class="ltr small text-muted">{{ $row->slug }}</td>
                            <td class="num">{{ $row->media_count }}</td>
                            <td>
                                @if (in_array($row->id, $inMenu))
                                    <span class="a-badge is-success">{{ __('admin.pages.in_menu') }}</span>
                                @else
                                    <button type="button" class="a-btn a-btn-sm" wire:click="addToMenu({{ $row->id }})"><i class="bi bi-plus"></i> {{ __('admin.pages.add_to_menu') }}</button>
                                @endif
                            </td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="w-actions"><x-admin.row-actions :view="$row->url()" :edit-url="route('admin.pages.edit', $row)" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="9"><x-admin.empty icon="bi-file-earmark-richtext" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
