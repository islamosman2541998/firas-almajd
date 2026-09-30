@php($px = settings()->group('pixels'))
@php($gtag = $px['ga4_id'] ?: $px['google_ads_id'])
@if ($px['gtm_id'])
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@js($px['gtm_id']));</script>
@endif
@if ($gtag)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtag }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());@if($px['ga4_id'])gtag('config',@js($px['ga4_id']));@endif @if($px['google_ads_id'])gtag('config',@js($px['google_ads_id']));@endif</script>
@endif
@if ($px['meta_pixel_id'])
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',@js($px['meta_pixel_id']));fbq('track','PageView');</script>
@endif
@if ($px['tiktok_pixel_id'])
<script>!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};ttq.load(@js($px['tiktok_pixel_id']));ttq.page();}(window,document,'ttq');</script>
@endif
@if ($px['snapchat_pixel_id'])
<script>(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function(){a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};a.queue=[];var s='script';var r=t.createElement(s);r.async=!0;r.src=n;var u=t.getElementsByTagName(s)[0];u.parentNode.insertBefore(r,u);})(window,document,'https://sc-static.net/scevent.min.js');snaptr('init',@js($px['snapchat_pixel_id']));snaptr('track','PAGE_VIEW');</script>
@endif
@if ($px['x_pixel_id'])
<script>!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');twq('config',@js($px['x_pixel_id']));</script>
@endif
@if ($px['linkedin_partner_id'])
<script>window._linkedin_partner_id=@js($px['linkedin_partner_id']);window._linkedin_data_partner_ids=window._linkedin_data_partner_ids||[];window._linkedin_data_partner_ids.push(window._linkedin_partner_id);(function(l){if(!l){window.lintrk=function(a,b){window.lintrk.q.push([a,b])};window.lintrk.q=[]}var s=document.getElementsByTagName("script")[0];var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src="https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(b,s);})(window.lintrk);</script>
@endif
@if ($px['pinterest_tag_id'])
<script>!function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;n.queue=[],n.version="3.0";var t=document.createElement("script");t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");pintrk('load',@js($px['pinterest_tag_id']));pintrk('page');</script>
@endif
@if ($px['clarity_id'])
<script>(function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window,document,"clarity","script",@js($px['clarity_id']));</script>
@endif
<script>
window.famTrack=function(type,data){
  @if ($px['track_leads'])
  data=data||{};
  try{
    if(window.fbq)fbq('track',type==='application'?'SubmitApplication':'Lead',data);
    if(window.gtag){gtag('event',type==='application'?'submit_application':'generate_lead',data);@if($px['google_ads_id'] && $px['google_ads_lead_label'])gtag('event','conversion',{send_to:@js($px['google_ads_id'].'/'.$px['google_ads_lead_label'])});@endif}
    if(window.dataLayer)dataLayer.push({event:'fam_'+type,form:type});
    if(window.ttq)ttq.track('SubmitForm');
    if(window.snaptr)snaptr('track','SIGN_UP');
    if(window.twq)twq('event','tw-lead',{});
    if(window.lintrk&&window._linkedin_partner_id)lintrk('track',{conversion_id:window._linkedin_partner_id});
    if(window.pintrk)pintrk('track','lead');
  }catch(e){}
  @endif
};
</script>
@if ($px['custom_head'])
{!! $px['custom_head'] !!}
@endif
