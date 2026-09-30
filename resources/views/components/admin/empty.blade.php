@props(['icon' => 'bi-inbox', 'text' => null])
<div class="a-empty"><i class="bi {{ $icon }}"></i>{{ $text ?? __('admin.common.empty') }}{{ $slot }}</div>
