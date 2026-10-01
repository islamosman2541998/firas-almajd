@php
    $nav = app(\App\Support\NavBuilder::class)->items('header');
    $other = other_locale();
@endphp
<header class="site-header" id="header">
<div class="container-wide header-inner">
<a aria-label="{{ setting_t('general.site_name') }}" class="brand" href="{{ lroute('home') }}"><img alt="{{ setting_t('general.logo_alt') }}" class="stacked-logo" height="109" src="{{ media_url(setting('general.logo')) }}" width="145"/></a>
<nav aria-label="{{ __('site.main_nav') }}" class="desktop-nav">
@foreach ($nav as $i => $item)
@if ($item['children'])
<div class="nav-dropdown{{ $item['open'] ? ' is-current' : '' }}">
@if ($item['url'])
<a @class(['nav-dropdown-toggle', 'active' => $item['open']]) href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif @if($item['new_tab']) target="_blank" rel="noopener" @endif aria-haspopup="true" aria-expanded="false">{{ $item['title'] }}<span aria-hidden="true" class="nav-caret"></span></a>
@else
<button @class(['nav-dropdown-toggle', 'active' => $item['open']]) type="button" aria-haspopup="true" aria-expanded="false">{{ $item['title'] }}<span aria-hidden="true" class="nav-caret"></span></button>
@endif
<div class="nav-dropdown-menu">
@foreach ($item['children'] as $child)
<a @class(['is-current' => $child['active']]) href="{{ $child['url'] ?? '#' }}" @if($child['active']) aria-current="page" @endif @if($child['new_tab']) target="_blank" rel="noopener" @endif>{{ $child['title'] }}</a>
@endforeach
</div>
</div>
@else
<a @class(['active' => $item['active']]) href="{{ $item['url'] ?? '#' }}" @if($item['active']) aria-current="page" @endif @if($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
@endif
@endforeach
</nav>
<div class="header-actions">
<a aria-label="{{ __('site.switch_language') }}" class="language-button" href="{{ switch_locale_url($other) }}" hreflang="{{ $other }}" id="languageToggle"><span class="globe">◎</span> <span id="languageText">{{ config("site.locales.{$other}.short") }}</span></a>
@if (setting('general.header_button_show'))
<a class="button button-gold header-contact" href="{{ link_url(setting('general.header_button_url')) }}"><span>{{ setting_t('general.header_button_text') }}</span><span class="arrow"><x-site.arrow /></span></a>
@endif
<button aria-controls="mobileNav" aria-expanded="false" aria-label="{{ __('site.open_menu') }}" class="menu-toggle" data-bs-target="#mobileNav" data-bs-toggle="collapse"><span></span><span></span></button>
</div>
</div>
<nav aria-label="{{ __('site.mobile_nav') }}" class="collapse mobile-nav" id="mobileNav"><div class="container-wide">
@foreach ($nav as $i => $item)
@if ($item['children'])
<div class="mobile-group">
<div class="mobile-group-head">
@if ($item['url'])
<a @class(['active' => $item['open']]) href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif>{{ $item['title'] }}</a>
@else
<span>{{ $item['title'] }}</span>
@endif
<button aria-controls="mobileSub{{ $i }}" aria-expanded="{{ $item['open'] ? 'true' : 'false' }}" aria-label="{{ $item['title'] }}" class="mobile-sub-toggle{{ $item['open'] ? '' : ' collapsed' }}" data-bs-target="#mobileSub{{ $i }}" data-bs-toggle="collapse" type="button"></button>
</div>
<div class="collapse mobile-subnav{{ $item['open'] ? ' show' : '' }}" id="mobileSub{{ $i }}">
@foreach ($item['children'] as $child)
<a @class(['active' => $child['active']]) href="{{ $child['url'] ?? '#' }}" @if($child['active']) aria-current="page" @endif @if($child['new_tab']) target="_blank" rel="noopener" @endif>{{ $child['title'] }}</a>
@endforeach
</div>
</div>
@else
<a @class(['active' => $item['active']]) href="{{ $item['url'] ?? '#' }}" @if($item['active']) aria-current="page" @endif @if($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
@endif
@endforeach
@if (setting('general.header_button_show'))
<a class="button button-gold mobile-contact" href="{{ link_url(setting('general.header_button_url')) }}"><span>{{ setting_t('general.header_button_text') }}</span><span class="arrow"><x-site.arrow /></span></a>
@endif
</div></nav>
</header>
