<?php

namespace App\Livewire\Admin\Concerns;

/**
 * Bilingual list fields (form.{field} = [['ar' => .., 'en' => ..], ...])
 * and generic repeaters (rows of fields).
 */
trait WithListItems
{
    public function addItem(string $path, array $row = []): void
    {
        $items = data_get($this, $path) ?? [];
        $items[] = $row ?: ['ar' => '', 'en' => ''];
        data_set($this, $path, array_values($items));
    }

    public function removeItem(string $path, int $index): void
    {
        $items = data_get($this, $path) ?? [];
        unset($items[$index]);
        data_set($this, $path, array_values($items));
        $this->resetValidation();
    }

    public function moveItem(string $path, int $index, int $direction): void
    {
        $items = array_values(data_get($this, $path) ?? []);
        $target = $index + $direction;
        if (! isset($items[$index], $items[$target])) {
            return;
        }
        [$items[$index], $items[$target]] = [$items[$target], $items[$index]];
        data_set($this, $path, $items);
    }

    /** Clean a list before saving: trim and drop rows without any text. */
    protected function cleanList(array $items): array
    {
        return collect($items)
            ->map(fn ($row) => array_map(fn ($v) => is_string($v) ? trim($v) : $v, (array) $row))
            ->filter(fn ($row) => collect($row)->filter(fn ($v) => is_string($v) && $v !== '')->isNotEmpty())
            ->values()
            ->all();
    }
}
