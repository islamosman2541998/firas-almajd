@php($px = settings()->group('pixels'))
@if ($px['gtm_id'])
<noscript><iframe height="0" src="https://www.googletagmanager.com/ns.html?id={{ $px['gtm_id'] }}" style="display:none;visibility:hidden" width="0"></iframe></noscript>
@endif
@if ($px['meta_pixel_id'])
<noscript><img alt="" height="1" src="https://www.facebook.com/tr?id={{ $px['meta_pixel_id'] }}&ev=PageView&noscript=1" style="display:none" width="1"/></noscript>
@endif
@if ($px['linkedin_partner_id'])
<noscript><img alt="" height="1" src="https://px.ads.linkedin.com/collect/?pid={{ $px['linkedin_partner_id'] }}&fmt=gif" style="display:none" width="1"/></noscript>
@endif
@if ($px['custom_body'])
{!! $px['custom_body'] !!}
@endif
