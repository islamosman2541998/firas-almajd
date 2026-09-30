@props(['model', 'label' => null, 'type' => 'text', 'required' => false, 'hint' => null, 'dir' => null, 'live' => false])
<div class="a-field">
    @if ($label)<label class="a-label" for="f_{{ str_replace('.', '_', $model) }}">{{ $label }}@if ($required) <span class="text-danger">*</span>@endif</label>@endif
    <input id="f_{{ str_replace('.', '_', $model) }}" type="{{ $type }}" {{ $live ? 'wire:model.live.debounce.400ms' : 'wire:model' }}="{{ $model }}" @if($dir) dir="{{ $dir }}" @endif {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($model)]) }}>
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
