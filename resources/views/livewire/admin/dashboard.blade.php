<div>
    @if (setting('dashboard.show_welcome'))
        <div class="a-welcome">
            <div>
                <h2>{{ __('admin.dashboard.welcome', ['name' => auth()->user()->name]) }}</h2>
                <p>{{ now()->translatedFormat(is_rtl() ? 'l، j F Y' : 'l, j F Y') }} · {{ __('admin.dashboard.open_jobs', ['count' => $openJobs]) }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="a-btn" href="{{ route('admin.sections') }}"><i class="bi bi-layout-text-window"></i> {{ __('admin.nav.sections') }}</a>
                <a class="a-btn" href="{{ route('home', ['locale' => app()->getLocale()]) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.common.view_site') }}</a>
            </div>
        </div>
    @endif

    <div class="a-stats">
        @foreach ($stats as $stat)
            <a class="a-stat" href="{{ route($stat['route']) }}">
                <span><i class="bi {{ $stat['icon'] }}"></i> {{ $stat['label'] }}</span>
                <strong>{{ number_format($stat['value']) }}</strong>
                @isset($stat['hot'])
                    <small @class(['is-hot' => $stat['hot'] > 0])>{{ __('admin.dashboard.new_count', ['count' => $stat['hot']]) }}</small>
                @endisset
            </a>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="a-card">
                <div class="a-card-head">
                    <div><h2>{{ __('admin.dashboard.activity') }}</h2><p>{{ __('admin.dashboard.last_14_days') }}</p></div>
                    <div class="a-legend"><span><i style="background:var(--a-accent)"></i>{{ __('admin.nav.messages') }}</span><span><i style="background:var(--a-primary)"></i>{{ __('admin.nav.applications') }}</span></div>
                </div>
                <div class="a-card-body">
                    <div class="a-bars">
                        @foreach ($chart as $day)
                            <div title="{{ $day['label'] }} — {{ $day['messages'] }} / {{ $day['applications'] }}">
                                <div class="d-flex gap-1 align-items-end w-100 justify-content-center" style="height:100%">
                                    <span style="height:{{ $day['messages'] / $chartMax * 100 }}%"></span>
                                    <span class="is-b" style="height:{{ $day['applications'] / $chartMax * 100 }}%"></span>
                                </div>
                                <small class="num">{{ $day['label'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="a-card h-100">
                <div class="a-card-head"><h2>{{ __('admin.dashboard.quick_actions') }}</h2></div>
                <div class="a-quick">
                    <a href="{{ route('admin.sliders.create') }}"><i class="bi bi-plus-square"></i>{{ __('admin.sliders.create') }}</a>
                    <a href="{{ route('admin.services.create') }}"><i class="bi bi-plus-square"></i>{{ __('admin.services.create') }}</a>
                    <a href="{{ route('admin.pages.create') }}"><i class="bi bi-plus-square"></i>{{ __('admin.pages.create') }}</a>
                    <a href="{{ route('admin.menu') }}"><i class="bi bi-list-nested"></i>{{ __('admin.nav.menu') }}</a>
                    <a href="{{ route('admin.settings.seo') }}"><i class="bi bi-search"></i>{{ __('admin.nav.settings_seo') }}</a>
                    <a href="{{ route('admin.settings.general') }}"><i class="bi bi-sliders"></i>{{ __('admin.nav.settings_general') }}</a>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.dashboard.latest_messages') }}</h2><a class="a-btn a-btn-sm" href="{{ route('admin.messages.index') }}">{{ __('admin.common.view_all') }}</a></div>
                <div class="a-table-wrap">
                    <table class="a-table">
                        <tbody>
                            @forelse ($messages as $message)
                                <tr @class(['is-unread' => $message->status === 'new'])>
                                    <td class="a-title-cell"><a href="{{ route('admin.messages.index', ['open' => $message->id]) }}"><strong>{{ $message->name }}</strong><small>{{ $message->service?->title ?? '—' }}</small></a></td>
                                    <td class="text-muted small text-end">{{ $message->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td><x-admin.empty icon="bi-envelope-open" /></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.dashboard.latest_applications') }}</h2><a class="a-btn a-btn-sm" href="{{ route('admin.applications.index') }}">{{ __('admin.common.view_all') }}</a></div>
                <div class="a-table-wrap">
                    <table class="a-table">
                        <tbody>
                            @forelse ($applications as $application)
                                <tr @class(['is-unread' => $application->status === 'new'])>
                                    <td class="a-title-cell"><a href="{{ route('admin.applications.index', ['open' => $application->id]) }}"><strong>{{ $application->name }}</strong><small>{{ $application->job?->title ?? '—' }}</small></a></td>
                                    <td class="text-muted small text-end">{{ $application->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td><x-admin.empty icon="bi-person-badge" /></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
