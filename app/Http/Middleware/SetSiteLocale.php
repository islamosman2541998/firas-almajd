<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetSiteLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! array_key_exists((string) $locale, config('site.locales'))) {
            abort(404);
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Route parameters are passed to controllers by name; the locale is global state.
        $request->route()->forgetParameter('locale');

        $response = $next($request);

        if ($request->cookie('site_locale') !== $locale) {
            $response->headers->setCookie(cookie()->forever('site_locale', $locale));
        }

        return $response;
    }
}
