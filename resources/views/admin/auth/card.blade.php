@php
    $l = $login;
    $preview = $preview ?? false;
    $bgUrl = $bgUrl ?? media_url($l['background_image']);
    $logoUrl = $logoUrl ?? media_url($l['logo']);
    $scope = $preview ? '.a-login-preview' : 'body';
@endphp
<style>
{{ $scope }} .auth-bg{background-image:{{ $bgUrl ? 'url('.$bgUrl.')' : 'none' }};opacity:{{ $l['background_opacity'] / 100 }};filter:blur({{ (int) $l['background_blur'] }}px);transform:scale({{ $l['background_blur'] ? 1.05 : 1 }})}
{{ $scope }} .auth-overlay{background:{{ hex_rgba($l['overlay_color'], $l['overlay_opacity']) }}}
{{ $scope }} .auth-card{max-width:{{ (int) $l['card_width'] }}px;background:{{ hex_rgba($l['card_bg'], $l['card_opacity']) }};color:{{ $l['card_text'] }};border:{{ (int) $l['card_border_width'] }}px solid {{ $l['card_border_color'] }};border-width:{{ (int) $l['card_border_width'] }}px 0 0;border-radius:{{ (int) $l['card_radius'] }}px;-webkit-backdrop-filter:blur({{ (int) $l['card_blur'] }}px);backdrop-filter:blur({{ (int) $l['card_blur'] }}px);box-shadow:0 30px 60px -25px rgba(0,0,0,.55)}
{{ $scope }} .auth-card>p,{{ $scope }} .auth-foot{color:{{ $l['card_muted'] }}}
{{ $scope }} .auth-card label{color:{{ $l['label_color'] }}}
{{ $scope }} .auth-input{background:{{ hex_rgba($l['input_bg'], $l['input_bg_opacity']) }};color:{{ $l['input_text'] }};border-color:{{ $l['input_border'] }};border-radius:{{ (int) $l['input_radius'] }}px}
{{ $scope }} .auth-input:focus{border-color:{{ $l['input_focus'] }};box-shadow:0 0 0 3px {{ hex_rgba($l['input_focus'], 25) }}}
{{ $scope }} .auth-button{background:{{ $l['button_bg'] }};color:{{ $l['button_color'] }};border-radius:{{ (int) $l['button_radius'] }}px}
{{ $scope }} .auth-button:hover{background:{{ $l['button_hover_bg'] }}}
{{ $scope }} .auth-remember{color:{{ $l['card_muted'] }}}
{{ $scope }} .auth-remember input{accent-color:{{ $l['button_bg'] }}}
@if ($preview)
.a-login-preview .auth-bg,.a-login-preview .auth-overlay{position:absolute}
.a-login-preview .auth-card{font-family:'Cairo',Arial,sans-serif;margin:24px}
@endif
</style>
<div class="auth-bg" aria-hidden="true"></div>
<div class="auth-overlay" aria-hidden="true"></div>
<div class="auth-card">
    @if ($l['show_logo'] && $logoUrl)
        <img class="auth-logo" src="{{ $logoUrl }}" alt="{{ setting_t('general.site_name') }}" style="width:{{ (int) $l['logo_width'] }}px">
    @endif
    <h1>{{ tval($l['title']) }}</h1>
    <p>{{ tval($l['subtitle']) }}</p>
    @if ($preview)
        <div class="auth-field"><label>{{ __('admin.fields.email') }}</label><input class="auth-input" type="text" value="admin@example.com" dir="ltr" readonly tabindex="-1"></div>
        <div class="auth-field"><label>{{ __('admin.fields.password') }}</label><input class="auth-input" type="password" value="password" dir="ltr" readonly tabindex="-1"></div>
        @if ($l['show_remember'])<div class="auth-remember"><input type="checkbox" checked tabindex="-1"> {{ __('admin.auth.remember') }}</div>@endif
        <button class="auth-button" type="button" tabindex="-1"><span>{{ tval($l['button_text']) }}</span><span aria-hidden="true">{{ is_rtl() ? '←' : '→' }}</span></button>
    @else
        {{ $slot ?? '' }}
    @endif
    @if (filled(tval($l['footer_text'])))<div class="auth-foot">{{ tval($l['footer_text']) }}</div>@endif
</div>
