<?php

namespace App\Models\Concerns;

use App\Support\SiteCache;

/**
 * Any write to a model the public site reads invalidates the site cache.
 */
trait FlushesSiteCache
{
    public static function bootFlushesSiteCache(): void
    {
        static::saved(fn () => SiteCache::flush());
        static::deleted(fn () => SiteCache::flush());
    }
}
