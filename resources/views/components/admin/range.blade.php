@props(['model', 'label', 'min' => 0, 'max' => 100, 'step' => 1, 'unit' => '%', 'live' => false])
<div class="a-field" x-data="{ v: $wire.entangle('{{ $model }}', {{ $live ? 'true' : 'false' }}) }">
    <label class="a-label">{{ $label }}</label>
    <div class="a-range">
        <input type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" x-model.number="v">
        <output x-text="(v ?? 0) + '{{ $unit }}'"></output>
    </div>
</div>
