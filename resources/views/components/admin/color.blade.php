@props(['model', 'label', 'nullable' => false, 'live' => false, 'hint' => null])
<div class="a-field" x-data="{ v: $wire.entangle('{{ $model }}', {{ $live ? 'true' : 'false' }}) }">
    <label class="a-label">{{ $label }}</label>
    <div class="a-color">
        <input type="color" :value="v || '#000000'" x-on:input="v = $event.target.value" aria-label="{{ $label }}">
        <input type="text" class="form-control @error($model) is-invalid @enderror" x-model.lazy="v" maxlength="9" placeholder="{{ $nullable ? __('admin.common.default') : '#000000' }}">
        @if ($nullable)
            <button type="button" class="a-btn a-btn-sm a-btn-ghost" x-on:click="v = ''" title="{{ __('admin.common.default') }}"><i class="bi bi-arrow-counterclockwise"></i></button>
        @endif
    </div>
    @error($model)<div class="a-error">{{ $message }}</div>@enderror
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
