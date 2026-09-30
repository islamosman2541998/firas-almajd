<?php

use App\Services\SettingsService;
use App\Support\LinkResolver;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('settings')) {
    function settings(): SettingsService
    {
        return app(SettingsService::class);
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return settings()->get($key, $default);
    }
}

if (! function_exists('setting_t')) {
    /** Translated setting value for the current locale. */
    function setting_t(string $key, ?string $locale = null): string
    {
        return settings()->trans($key, $locale);
    }
}

if (! function_exists('tval')) {
    /**
     * Pick the current-locale string from an ['ar' => .., 'en' => ..] value,
     * falling back to the other locale when empty.
     */
    function tval(mixed $value, ?string $locale = null): string
    {
        if (! is_array($value)) {
            return (string) ($value ?? '');
        }

        $locale ??= app()->getLocale();
        if (filled($value[$locale] ?? null)) {
            return (string) $value[$locale];
        }

        foreach ($value as $fallback) {
            if (is_string($fallback) && $fallback !== '') {
                return $fallback;
            }
        }

        return '';
    }
}

if (! function_exists('ml')) {
    /** Escape multi-line text and convert new lines to <br>. */
    function ml(mixed $value): HtmlString
    {
        return new HtmlString(nl2br(e(tval($value)), false));
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback ? media_url($fallback) : null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        if (Str::startsWith($path, ['assets/', 'vendor/'])) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('asset_v')) {
    /** Public asset URL with a file-modification version for cache busting. */
    function asset_v(string $path): string
    {
        static $versions = [];
        $versions[$path] ??= @filemtime(public_path($path)) ?: 1;

        return asset($path).'?v='.$versions[$path];
    }
}

if (! function_exists('locales')) {
    function locales(): array
    {
        return config('site.locales');
    }
}

if (! function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool
    {
        return (config('site.locales.'.($locale ?? app()->getLocale()).'.dir') ?? 'rtl') === 'rtl';
    }
}

if (! function_exists('other_locale')) {
    function other_locale(): string
    {
        return collect(array_keys(locales()))->first(fn ($l) => $l !== app()->getLocale()) ?? 'en';
    }
}

if (! function_exists('lroute')) {
    /** Localized site route. */
    function lroute(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        $parameters = is_array($parameters) ? $parameters : [$parameters];

        return route($name, ['locale' => $locale ?? app()->getLocale(), ...$parameters], $absolute);
    }
}

if (! function_exists('switch_locale_url')) {
    /** The current page in another locale. */
    function switch_locale_url(string $locale): string
    {
        $route = request()->route();

        if (! $route || ! $route->getName() || ! in_array('locale', $route->parameterNames(), true)) {
            return route('home', ['locale' => $locale]);
        }

        $parameters = ['locale' => $locale];
        foreach ($route->parameterNames() as $name) {
            if ($name !== 'locale') {
                $value = $route->parameter($name);
                $parameters[$name] = $value instanceof Illuminate\Database\Eloquent\Model ? $value->getRouteKey() : $value;
            }
        }

        $query = request()->getQueryString();

        return route($route->getName(), $parameters).($query ? '?'.$query : '');
    }
}

if (! function_exists('link_url')) {
    /** Resolve a stored link value (route:, page:, service:, #anchor, /path, http). */
    function link_url(?string $value): string
    {
        return app(LinkResolver::class)->url($value);
    }
}

if (! function_exists('wa_url')) {
    function wa_url(?string $text = null): string
    {
        $number = preg_replace('/\D+/', '', (string) setting('general.whatsapp'));

        return 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('fill_template')) {
    function fill_template(string $template, array $values): string
    {
        $lines = [];
        // "u" matters: without it \R also matches byte 0x85, which is part of many Arabic letters.
        foreach (preg_split('/\R/u', $template) as $line) {
            // Drop lines whose placeholders are all empty (e.g. an optional email).
            if (preg_match_all('/\{(\w+)\}/u', $line, $m) && collect($m[1])->every(fn ($k) => blank($values[$k] ?? null))) {
                continue;
            }
            $lines[] = preg_replace_callback('/\{(\w+)\}/u', fn ($m) => (string) ($values[$m[1]] ?? ''), $line);
        }

        return implode("\n", $lines);
    }
}

if (! function_exists('hex_rgba')) {
    /** #rrggbb + opacity percentage → rgba() */
    function hex_rgba(?string $hex, int|float $opacity = 100): string
    {
        $hex = ltrim((string) $hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (! preg_match('/^[0-9a-f]{6}$/i', $hex)) {
            return 'transparent';
        }
        [$r, $g, $b] = array_map('hexdec', str_split($hex, 2));

        return "rgba({$r},{$g},{$b},".round(max(0, min(100, $opacity)) / 100, 3).')';
    }
}

if (! function_exists('admin_per_page')) {
    function admin_per_page(): int
    {
        return max(5, (int) setting('dashboard.per_page', 15));
    }
}
