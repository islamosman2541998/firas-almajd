<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Versioned cache for everything the public site reads from the database.
 * Any content change bumps the version, so every cached fragment is
 * rebuilt on the next request without tracking individual keys.
 */
class SiteCache
{
    protected static ?int $version = null;

    /** @var array<string, mixed> per-request memo */
    protected static array $memo = [];

    public static function remember(string $key, Closure $callback): mixed
    {
        if (array_key_exists($key, static::$memo)) {
            return static::$memo[$key];
        }

        return static::$memo[$key] = Cache::rememberForever(
            'site.v'.static::version().'.'.$key,
            $callback
        );
    }

    public static function version(): int
    {
        return static::$version ??= (int) Cache::rememberForever('site.cache_version', fn () => 1);
    }

    public static function flush(): void
    {
        $next = static::version() + 1;
        Cache::forever('site.cache_version', $next);
        static::$version = $next;
        static::$memo = [];
    }
}
