@props(['target' => 'save', 'label' => null, 'type' => 'submit'])
<button type="{{ $type }}" {{ $attributes->class(['a-btn a-btn-primary']) }} wire:loading.attr="disabled" wire:target="{{ $target }}">
    <span wire:loading.remove wire:target="{{ $target }}"><i class="bi bi-check2"></i></span>
    <span wire:loading wire:target="{{ $target }}" class="spin"></span>
    {{ $label ?? __('admin.common.save') }}
</button>
