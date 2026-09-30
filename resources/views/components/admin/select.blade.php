@props(['model', 'label' => null, 'options' => [], 'placeholder' => null, 'hint' => null, 'live' => false, 'required' => false])
<div class="a-field">
    @if ($label)<label class="a-label">{{ $label }}@if ($required) <span class="text-danger">*</span>@endif</label>@endif
    <select {{ $live ? 'wire:model.live' : 'wire:model' }}="{{ $model }}" {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($model)]) }}>
        @if ($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
