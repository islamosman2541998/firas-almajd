<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('admin_locale')
            ?? $request->user()?->locale
            ?? setting('dashboard.default_locale', 'ar');

        if (! array_key_exists($locale, config('site.locales'))) {
            $locale = 'ar';
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
