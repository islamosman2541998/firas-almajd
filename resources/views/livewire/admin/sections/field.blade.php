@php($label = tval($field['label'] ?? $name))
@switch($field['type'])
    @case('repeater')
        @php($rows = data_get(\Livewire\Livewire::current(), $model) ?? [])
        <div class="a-field">
            <label class="a-label">{{ $label }}</label>
            @foreach ($rows as $i => $row)
                <div class="a-repeater-item" wire:key="{{ $model }}-{{ $i }}-{{ count($rows) }}">
                    <div class="a-repeater-head">
                        <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="a-actions">
                            <button type="button" class="a-btn" wire:click="moveItem('{{ $model }}', {{ $i }}, -1)" @disabled($i === 0)><i class="bi bi-arrow-up"></i></button>
                            <button type="button" class="a-btn" wire:click="moveItem('{{ $model }}', {{ $i }}, 1)" @disabled($i === count($rows) - 1)><i class="bi bi-arrow-down"></i></button>
                            <button type="button" class="a-btn is-danger" wire:click="removeItem('{{ $model }}', {{ $i }})"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    @foreach ($field['fields'] as $subName => $subField)
                        @include('livewire.admin.sections.field', ['name' => $subName, 'field' => $subField, 'model' => $model.'.'.$i.'.'.$subName])
                    @endforeach
                </div>
            @endforeach
            <button type="button" class="a-btn a-btn-sm" wire:click="addRow('{{ $name }}')"><i class="bi bi-plus-lg"></i> {{ __('admin.common.add_item') }}</button>
        </div>
        @break
    @case('image')
        <x-admin.upload :model="'uploads.'.$name" :file="$uploads[$name] ?? null" :current="data_get(\Livewire\Livewire::current(), $model)" :label="$label" />
        @break
    @case('link')
        <x-admin.link :model="$model" :label="$label" :options="$linkOptions" />
        @break
    @case('toggle')
        <x-admin.toggle :model="$model" :label="$label" />
        @break
    @case('number')
        <x-admin.input :model="$model" type="number" :label="$label" min="0" />
        @break
    @case('select')
        <x-admin.select :model="$model" :label="$label" :options="collect($field['options'])->map(fn ($o) => tval($o))->all()" />
        @break
    @case('color')
        <x-admin.color :model="$model" :label="$label" />
        @break
    @default
        @if (! empty($field['t']))
            <x-admin.t-input :model="$model" :label="$label" :textarea="$field['type'] === 'textarea'" rows="3" :hint="$field['type'] === 'textarea' ? __('admin.sections.newline_hint') : null" />
        @else
            <x-admin.input :model="$model" :label="$label" />
        @endif
@endswitch
