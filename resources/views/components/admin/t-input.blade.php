@props(['model', 'label', 'textarea' => false, 'rows' => 3, 'required' => false, 'hint' => null, 'stacked' => false, 'live' => false, 'max' => null])
@php($wire = $live ? 'wire:model.live.debounce.400ms' : 'wire:model')
<div class="a-field">
    <label class="a-label">{{ $label }}@if ($required) <span class="text-danger">*</span>@endif</label>
    <div @class(['a-pair', 'is-stacked' => $stacked])>
        @foreach (locales() as $code => $info)
            <div class="a-lang">
                <span class="a-lang-tag">{{ strtoupper($code) }}</span>
                @if ($textarea)
                    <textarea {{ $wire }}="{{ $model }}.{{ $code }}" rows="{{ $rows }}" dir="{{ $info['dir'] }}" @if($max) maxlength="{{ $max }}" @endif @class(['form-control', 'is-invalid' => $errors->has("$model.$code")])></textarea>
                @else
                    <input type="text" {{ $wire }}="{{ $model }}.{{ $code }}" dir="{{ $info['dir'] }}" @if($max) maxlength="{{ $max }}" @endif @class(['form-control', 'is-invalid' => $errors->has("$model.$code")])>
                @endif
                @error("$model.$code")<div class="a-error">{{ $message }}</div>@enderror
            </div>
        @endforeach
    </div>
    @if ($hint)<small class="a-hint">{{ $hint }}</small>@endif
</div>
