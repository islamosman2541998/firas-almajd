<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * sort_order + is_active conventions shared by content models.
 */
trait Sortable
{
    public static function bootSortable(): void
    {
        static::creating(function ($model) {
            if (! $model->sort_order) {
                $model->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('is_active'), true);
    }

    /**
     * Persist a new order from a list of ids (drag & drop).
     */
    public static function reorder(array $ids): void
    {
        foreach (array_values($ids) as $index => $id) {
            static::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }
        \App\Support\SiteCache::flush();
    }

    /**
     * Move one item to a zero-based position among its siblings.
     */
    public static function moveTo(int|string $id, int $position, ?Builder $scope = null): void
    {
        $ids = ($scope ?? static::query())->ordered()->pluck('id')->reject(fn ($value) => (string) $value === (string) $id)->values()->all();
        array_splice($ids, max(0, $position), 0, [$id]);
        static::reorder($ids);
    }
}
