<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RootController extends Controller
{
    /** "/" → the visitor's last language, else the default one. */
    public function __invoke(Request $request): RedirectResponse
    {
        $locale = $request->cookie('site_locale');

        if (! array_key_exists((string) $locale, config('site.locales'))) {
            $locale = config('site.default_locale');
        }

        return redirect()->route('home', ['locale' => $locale], 302);
    }
}
