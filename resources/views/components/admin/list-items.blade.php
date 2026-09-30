@props(['path', 'label', 'hint' => null, 'textarea' => false])
@php($items = data_get(\Livewire\Livewire::current(), $path) ?? [])
<div class="a-field">
    <label class="a-label">{{ $label }}</label>
    @if ($hint)<small class="a-hint mb-2">{{ $hint }}</small>@endif
    @foreach ($items as $i => $item)
        <div class="a-repeater-item" wire:key="{{ $path }}-{{ $i }}-{{ count($items) }}">
            <div class="a-repeater-head">
                <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="a-actions">
                    <button type="button" class="a-btn" wire:click="moveItem('{{ $path }}', {{ $i }}, -1)" @disabled($i === 0) title="{{ __('admin.common.move_up') }}"><i class="bi bi-arrow-up"></i></button>
                    <button type="button" class="a-btn" wire:click="moveItem('{{ $path }}', {{ $i }}, 1)" @disabled($i === count($items) - 1) title="{{ __('admin.common.move_down') }}"><i class="bi bi-arrow-down"></i></button>
                    <button type="button" class="a-btn is-danger" wire:click="removeItem('{{ $path }}', {{ $i }})" title="{{ __('admin.common.remove') }}"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="a-pair">
                @foreach (locales() as $code => $info)
                    <div class="a-lang mb-2">
                        <span class="a-lang-tag">{{ strtoupper($code) }}</span>
                        @if ($textarea)
                            <textarea rows="2" class="form-control @error("$path.$i.$code") is-invalid @enderror" dir="{{ $info['dir'] }}" wire:model="{{ $path }}.{{ $i }}.{{ $code }}"></textarea>
                        @else
                            <input type="text" class="form-control @error("$path.$i.$code") is-invalid @enderror" dir="{{ $info['dir'] }}" wire:model="{{ $path }}.{{ $i }}.{{ $code }}">
                        @endif
                        @error("$path.$i.$code")<div class="a-error">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <button type="button" class="a-btn a-btn-sm" wire:click="addItem('{{ $path }}')"><i class="bi bi-plus-lg"></i> {{ __('admin.common.add_item') }}</button>
</div>
