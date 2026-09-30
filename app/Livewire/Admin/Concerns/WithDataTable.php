<?php

namespace App\Livewire\Admin\Concerns;

use App\Exports\TableExport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Shared behaviour of every dashboard list: live search, filters kept in
 * the URL, sorting, pagination, bulk actions, drag ordering and Excel export.
 *
 * The component provides:
 *   modelClass(): string       — the Eloquent model
 *   baseQuery(): Builder       — query with search + filters applied
 *   exportColumns(): array     — [heading => Closure(row)]
 *   $sortable (array)          — columns allowed in sortBy()
 */
trait WithDataTable
{
    use WithPagination, WithToast;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $sortField = '';

    #[Url(except: 'asc')]
    public string $sortDirection = 'asc';

    public int $perPage = 15;

    public array $selected = [];

    abstract protected function modelClass(): string;

    abstract protected function baseQuery(): Builder;

    abstract protected function exportColumns(): array;

    public function mountWithDataTable(): void
    {
        $this->perPage = admin_per_page();
    }

    public function updatedWithDataTable($property): void
    {
        if (in_array(explode('.', $property)[0], ['search', 'status', 'perPage', ...$this->filterProperties()], true)) {
            $this->resetPage();
            $this->selected = [];
        }
    }

    /** Extra filter properties that reset pagination when changed. */
    protected function filterProperties(): array
    {
        return property_exists($this, 'filters') ? ['filters'] : [];
    }

    public function sortBy(string $field): void
    {
        if (! in_array($field, $this->sortable ?? [], true)) {
            return;
        }

        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'status', 'selected');
        if (property_exists($this, 'filters')) {
            $this->filters = array_map(fn () => '', $this->filters);
        }
        $this->resetPage();
    }

    protected function applyStatus(Builder $query, string $column = 'is_active'): Builder
    {
        return $query->when($this->status !== '', fn ($q) => $q->where($column, $this->status === 'active'));
    }

    protected function sortedQuery(): Builder
    {
        $query = $this->baseQuery();

        if ($this->sortField !== '') {
            return $query->reorder()->orderBy($this->sortField, $this->sortDirection === 'desc' ? 'desc' : 'asc');
        }

        return $query;
    }

    protected function rows(): LengthAwarePaginator
    {
        return $this->sortedQuery()->paginate($this->perPage);
    }

    /** Drag & drop is only meaningful on the default order without filters. */
    public function canReorder(): bool
    {
        return $this->sortField === '' && $this->search === '' && $this->status === ''
            && (! property_exists($this, 'filters') || ! array_filter($this->filters))
            && method_exists($this->modelClass(), 'reorder');
    }

    public function reorder(int|string $id, int $position): void
    {
        $model = $this->modelClass();
        $offset = ($this->getPage() - 1) * $this->perPage;
        $model::moveTo($id, $offset + $position, $this->reorderScope());
        $this->toast(__('admin.common.order_saved'));
    }

    protected function reorderScope(): ?Builder
    {
        return null;
    }

    public function toggleActive(int $id, string $column = 'is_active'): void
    {
        $record = $this->modelClass()::findOrFail($id);
        $record->update([$column => ! $record->{$column}]);
        $this->toast(__('admin.common.updated'));
    }

    public function delete(int $id): void
    {
        $record = $this->modelClass()::findOrFail($id);
        $this->beforeDelete($record);
        $record->delete();
        $this->selected = array_values(array_diff($this->selected, [(string) $id, $id]));
        $this->toast(__('admin.common.deleted'));
    }

    protected function beforeDelete(Model $record): void {}

    public function bulkDelete(): void
    {
        $records = $this->modelClass()::query()->whereKey($this->selected)->get();
        $records->each(function (Model $record) {
            $this->beforeDelete($record);
            $record->delete();
        });
        $this->toast(__('admin.common.bulk_deleted', ['count' => $records->count()]));
        $this->selected = [];
    }

    public function bulkActive(bool $active): void
    {
        $count = $this->modelClass()::query()->whereKey($this->selected)->update(['is_active' => $active]);
        \App\Support\SiteCache::flush();
        $this->toast(__('admin.common.bulk_updated', ['count' => $count]));
        $this->selected = [];
    }

    /** Select or clear every row of the current page. */
    public function toggleSelectPage(array $ids): void
    {
        $ids = array_map('strval', $ids);
        $this->selected = count(array_intersect($ids, $this->selected)) === count($ids)
            ? array_values(array_diff($this->selected, $ids))
            : array_values(array_unique([...$this->selected, ...$ids]));
    }

    public function export()
    {
        $name = str(class_basename($this->modelClass()))->plural()->snake().'-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new TableExport($this->sortedQuery(), $this->exportColumns()), $name);
    }

    /** Helpers for export columns. */
    protected function both(Model $row, string $attribute): array
    {
        return [$row->getTranslation($attribute, 'ar', false), $row->getTranslation($attribute, 'en', false)];
    }

    protected function yesNo(bool $value): string
    {
        return $value ? __('admin.common.yes') : __('admin.common.no');
    }

    protected function searchTranslatable(Builder $query, array $columns): Builder
    {
        $term = '%'.trim($this->search).'%';

        return $query->where(function (Builder $q) use ($columns, $term) {
            foreach ($columns as $column) {
                if (str_contains($column, '.') || ! $this->isTranslatable($column)) {
                    $q->orWhere($column, 'like', $term);

                    continue;
                }
                foreach (array_keys(config('site.locales')) as $locale) {
                    $q->orWhere("{$column}->{$locale}", 'like', $term);
                }
            }
        });
    }

    protected function isTranslatable(string $column): bool
    {
        $model = $this->modelClass();

        return in_array($column, (new $model)->translatable ?? [], true);
    }
}
