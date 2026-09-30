@php($rtl = is_rtl())
<!DOCTYPE html>
<html dir="{{ $rtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>{{ tval($login['title']) }} — {{ setting_t('general.site_name') }}</title>
<link rel="icon" href="{{ media_url(setting('dashboard.favicon'), setting('general.favicon')) }}">
<link rel="stylesheet" href="{{ asset_v('assets/site/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset_v('assets/admin/css/admin.css') }}">
</head>
<body class="auth-body">
<a class="auth-lang" href="{{ route('admin.locale', other_locale()) }}">{{ config('site.locales.'.other_locale().'.name') }}</a>
@component('admin.auth.card', ['login' => $login])
    <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
        @csrf
        <div class="auth-field">
            <label for="email">{{ __('admin.fields.email') }}</label>
            <input class="auth-input" id="email" name="email" type="email" value="{{ old('email') }}" dir="ltr" autocomplete="username" required autofocus>
            @error('email')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="auth-field">
            <label for="password">{{ __('admin.fields.password') }}</label>
            <input class="auth-input" id="password" name="password" type="password" dir="ltr" autocomplete="current-password" required>
            @error('password')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        @if ($login['show_remember'])
            <label class="auth-remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> {{ __('admin.auth.remember') }}</label>
        @endif
        <button class="auth-button" type="submit"><span>{{ tval($login['button_text']) }}</span><span aria-hidden="true">{{ $rtl ? '←' : '→' }}</span></button>
    </form>
@endcomponent
</body>
</html>
