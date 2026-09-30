<div>
    <x-admin.page-head :title="__('admin.nav.services')" :subtitle="__('admin.services.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('admin.sections', ['page' => 'service']) }}"><i class="bi bi-layout-text-window"></i> {{ __('admin.services.page_sections') }}</a>
            <a class="a-btn a-btn-primary" href="{{ route('admin.services.create') }}"><i class="bi bi-plus-lg"></i> {{ __('admin.services.create') }}</a>
        </x-slot:actions>
    </x-admin.page-head>

    <div class="a-card">
        <x-admin.toolbar>
            <x-admin.status-filter />
            <select class="form-select" wire:model.live="filters.home">
                <option value="">{{ __('admin.services.home_filter') }}</option>
                <option value="yes">{{ __('admin.services.on_home') }}</option>
                <option value="no">{{ __('admin.services.not_on_home') }}</option>
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
                    <x-admin.sort-th field="slug" :label="__('admin.fields.slug')" />
                    <th>{{ __('admin.services.scope_items') }}</th>
                    <th>{{ __('admin.services.show_on_home') }}</th>
                    <th>{{ __('admin.common.status') }}</th>
                    <x-admin.sort-th field="updated_at" :label="__('admin.common.updated_at')" />
                    <th class="w-actions"></th>
                </tr></thead>
                <tbody @if($this->canReorder()) wire:sort="reorder" @endif>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" wire:sort:item="{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            @if ($this->canReorder())<td><i class="bi bi-grip-vertical a-drag" wire:sort:handle></i></td>@endif
                            <td><img class="a-thumb" src="{{ media_url($row->image) }}" alt="" loading="lazy"></td>
                            <td class="a-title-cell"><strong>{{ $row->title }}</strong><small>{{ $row->getTranslation('title', app()->getLocale() === 'ar' ? 'en' : 'ar', false) }}</small></td>
                            <td class="ltr text-muted small">{{ $row->slug }}</td>
                            <td class="num">{{ count($row->scope_items ?? []) }}</td>
                            <td><x-admin.status :active="$row->show_on_home" :action="'toggleHome('.$row->id.')'" :on="__('admin.common.yes')" :off="__('admin.common.no')" /></td>
                            <td><x-admin.status :active="$row->is_active" :action="'toggleActive('.$row->id.')'" /></td>
                            <td class="small text-muted num">{{ $row->updated_at?->format(setting('dashboard.date_format', 'Y-m-d')) }}</td>
                            <td class="w-actions"><x-admin.row-actions :view="$row->url()" :edit-url="route('admin.services.edit', $row)" :delete="'delete('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="10"><x-admin.empty icon="bi-bricks" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
