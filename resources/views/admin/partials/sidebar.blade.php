@php
    $badges = [
        'messages' => \App\Models\ContactMessage::query()->where('status', 'new')->count(),
        'applications' => \App\Models\JobApplication::query()->where('status', 'new')->count(),
    ];
@endphp
<aside class="a-sidebar" id="adminSidebar">
    <a class="a-brand" href="{{ route('admin.dashboard') }}">
        <img src="{{ media_url(setting('dashboard.logo'), setting('general.logo')) }}" alt="{{ setting_t('general.site_name') }}" width="72" height="54">
        <span>
            <strong>{{ setting_t('general.site_name') }}</strong>
            <small>{{ setting_t('dashboard.name') }}</small>
        </span>
    </a>
    <nav class="a-nav" aria-label="{{ __('admin.nav.label') }}">
        @foreach (config('admin.nav') as $group => $items)
            @if ($group !== 'main')
                <div class="a-nav-label">{{ __('admin.nav.groups.'.$group) }}</div>
            @endif
            @foreach ($items as $item)
                @php($active = request()->routeIs($item['active'] ?? $item['route']))
                <a @class(['a-nav-link', 'is-active' => $active]) href="{{ route($item['route']) }}" @if($active) aria-current="page" @endif>
                    <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>
                    <span>{{ __('admin.nav.'.$item['label']) }}</span>
                    @if (! empty($item['badge']) && $badges[$item['badge']] > 0)
                        <em class="a-nav-badge">{{ $badges[$item['badge']] }}</em>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>
    <div class="a-sidebar-foot">
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.common.view_site') }}</a>
    </div>
</aside>
