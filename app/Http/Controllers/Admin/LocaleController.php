<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('site.locales')), 404);

        $request->session()->put('admin_locale', $locale);
        $request->user()?->forceFill(['locale' => $locale])->saveQuietly();

        return back();
    }
}
