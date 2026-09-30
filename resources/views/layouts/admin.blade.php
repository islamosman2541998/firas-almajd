@php
    $rtl = is_rtl();
    $theme = setting('theme_admin');
    $dashName = setting_t('dashboard.name');
@endphp
<!DOCTYPE html>
<html dir="{{ $rtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? $title.' — ' : '' }}{{ $dashName }}</title>
<link rel="icon" href="{{ media_url(setting('dashboard.favicon'), setting('general.favicon')) }}">
<link rel="preload" href="{{ asset('assets/site/fonts/cairo-2.ttf') }}" as="font" type="font/ttf" crossorigin>
<link rel="stylesheet" href="{{ asset_v('assets/site/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset_v('vendor/bootstrap/'.($rtl ? 'bootstrap.rtl.min.css' : 'bootstrap.min.css')) }}">
<link rel="stylesheet" href="{{ asset_v('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset_v('vendor/notyf/notyf.min.css') }}">
<link rel="stylesheet" href="{{ asset_v('vendor/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset_v('assets/admin/css/admin.css') }}">
@include('admin.partials.theme-vars', ['theme' => $theme])
@stack('styles')
</head>
<body class="a-body">
<div class="a-shell" id="adminShell">
    @include('admin.partials.sidebar')
    <div class="a-main">
        @include('admin.partials.topbar', ['title' => $title ?? null])
        <main class="a-content">
            {{ $slot }}
        </main>
    </div>
</div>
<div class="a-backdrop" data-sidebar-close></div>
<script src="{{ asset_v('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset_v('vendor/notyf/notyf.min.js') }}"></script>
<script src="{{ asset_v('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
@php
    $adminI18n = [
        'confirm_title' => __('admin.confirm.title'),
        'confirm_text' => __('admin.confirm.text'),
        'confirm_yes' => __('admin.confirm.yes'),
        'confirm_cancel' => __('admin.confirm.cancel'),
        'rtl' => $rtl,
    ];
@endphp
<script>
window.AdminI18n = @json($adminI18n);
window.AdminFlash = @json(session('toast'));
</script>
<script src="{{ asset_v('assets/admin/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
