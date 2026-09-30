@props(['field', 'label'])
@php($lw = \Livewire\Livewire::current())
<th>
    <button type="button" wire:click="sortBy('{{ $field }}')" @class(['a-sort', 'is-sorted' => $lw->sortField === $field])>
        {{ $label }}
        @if ($lw->sortField === $field)
            <i class="bi bi-arrow-{{ $lw->sortDirection === 'asc' ? 'up' : 'down' }}"></i>
        @else
            <i class="bi bi-arrow-down-up opacity-50"></i>
        @endif
    </button>
</th>
