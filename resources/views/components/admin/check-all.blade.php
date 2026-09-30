@props(['rows'])
@php($ids = collect($rows->items())->pluck('id')->map(fn ($id) => (string) $id)->all())
@php($lw = \Livewire\Livewire::current())
<th class="w-check">
    <input type="checkbox" class="form-check-input" wire:click="toggleSelectPage(@js($ids))" @checked($ids && ! array_diff($ids, array_map('strval', $lw->selected))) aria-label="{{ __('admin.common.select_all') }}">
</th>
