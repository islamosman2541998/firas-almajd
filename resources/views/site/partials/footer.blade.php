@php($platforms = config('site.social_platforms'))
<footer class="site-footer expanded-footer"><div class="container-wide"><div class="footer-columns footer-columns-simple"><div class="footer-brand"><a class="brand" href="{{ lroute('home') }}"><img alt="{{ setting_t('general.logo_alt') }}" class="stacked-logo" height="135" loading="lazy" src="{{ media_url(setting('general.footer_logo'), setting('general.logo')) }}" width="180"/></a><p>{{ setting_t('general.tagline') }}</p></div><div class="footer-social"><h2>{{ setting_t('general.footer_social_title') }}</h2><div class="social-icons">
@foreach ((array) setting('general.social') as $social)
@continue(empty($social['enabled']) || ! isset($platforms[$social['platform'] ?? '']))
@php($platform = $platforms[$social['platform']])
@if (filled($social['url'] ?? null))
<a aria-label="{{ $platform['label'] }}" class="social-icon" href="{{ $social['url'] }}" rel="noopener noreferrer" target="_blank" title="{{ $platform['label'] }}"><i aria-hidden="true" class="fa-brands {{ $platform['icon'] }}"></i></a>
@else
<span aria-label="{{ $platform['label'] }}" class="social-icon" role="img" title="{{ $platform['label'] }}"><i aria-hidden="true" class="fa-brands {{ $platform['icon'] }}"></i></span>
@endif
@endforeach
</div></div></div><div class="footer-bottom"><span>© <span id="year">{{ now()->year }}</span> <span>{{ setting_t('general.copyright') }}</span></span></div></div></footer>
