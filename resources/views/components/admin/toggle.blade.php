@props(['model', 'label', 'hint' => null, 'live' => false])
<div class="a-field">
    <div class="form-check form-switch m-0">
        <input class="form-check-input" type="checkbox" role="switch" id="t_{{ str_replace('.', '_', $model) }}" {{ $live ? 'wire:model.live' : 'wire:model' }}="{{ $model }}">
        <label class="form-check-label" for="t_{{ str_replace('.', '_', $model) }}">{{ $label }}</label>
    </div>
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
