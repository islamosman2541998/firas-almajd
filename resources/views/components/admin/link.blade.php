@props(['model', 'label' => null, 'options' => [], 'hint' => null])
<div class="a-field" x-data="{ v: $wire.entangle('{{ $model }}'), keys: @js(array_map('strval', array_keys($options))), mode: 'pick' }" x-init="mode = (v === null || v === '' || keys.includes(v)) ? 'pick' : 'custom'">
    @if ($label)<label class="a-label">{{ $label }}</label>@endif
    <select class="form-select" x-on:change="if ($event.target.value === '__custom') { mode = 'custom'; v = '' } else { mode = 'pick'; v = $event.target.value }">
        <option value="">{{ __('admin.link.none') }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" :selected="mode === 'pick' && v === @js((string) $value)">{{ $text }}</option>
        @endforeach
        <option value="__custom" :selected="mode === 'custom'">{{ __('admin.link.custom') }}</option>
    </select>
    <input type="text" class="form-control mt-2" dir="ltr" x-show="mode === 'custom'" x-cloak x-model.lazy="v" placeholder="https://…  /  #section  /  /path">
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
