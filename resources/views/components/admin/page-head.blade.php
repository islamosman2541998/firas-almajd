@props(['title', 'subtitle' => null, 'back' => null])
<div class="a-page-head">
    <div>
        @if ($back)
            <div class="a-crumb"><a href="{{ $back }}"><i class="bi bi-arrow-{{ is_rtl() ? 'right' : 'left' }}"></i> {{ __('admin.common.back') }}</a></div>
        @endif
        <h1>{{ $title }}</h1>
        @if ($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)<div class="a-page-actions">{{ $actions }}</div>@endisset
</div>
