@php($user = auth()->user())
<header class="a-topbar">
    <button class="a-icon-btn a-menu-btn" type="button" data-sidebar-toggle aria-label="{{ __('admin.common.menu') }}"><i class="bi bi-list"></i></button>
    <div class="a-topbar-title">{{ $title }}</div>
    <div class="a-topbar-actions">
        <a class="a-top-link" href="{{ route('admin.locale', other_locale()) }}" title="{{ __('admin.common.switch_language') }}">
            <i class="bi bi-translate"></i><span>{{ config('site.locales.'.other_locale().'.name') }}</span>
        </a>
        <a class="a-icon-btn d-none d-sm-grid" href="{{ route('home', ['locale' => app()->getLocale()]) }}" target="_blank" rel="noopener" title="{{ __('admin.common.view_site') }}"><i class="bi bi-globe2"></i></a>
        <livewire:admin.notifications.bell />
        <div class="dropdown">
            <button class="a-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="a-avatar">{{ $user->initials() }}</span>
                <span class="a-user-name d-none d-md-inline">{{ $user->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end a-dropdown">
                <li class="px-3 py-2 small text-muted" dir="ltr">{{ $user->email }}</li>
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person"></i> {{ __('admin.nav.profile') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.general') }}"><i class="bi bi-sliders"></i> {{ __('admin.nav.settings_general') }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf
                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> {{ __('admin.auth.logout') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
