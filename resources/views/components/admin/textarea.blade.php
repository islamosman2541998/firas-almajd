@props(['model', 'label' => null, 'rows' => 4, 'hint' => null, 'dir' => null])
<div class="a-field">
    @if ($label)<label class="a-label">{{ $label }}</label>@endif
    <textarea wire:model="{{ $model }}" rows="{{ $rows }}" @if($dir) dir="{{ $dir }}" @endif {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($model)]) }}></textarea>
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
