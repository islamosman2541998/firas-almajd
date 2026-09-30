<?php

namespace App\Services;

use App\Models\Setting;
use App\Support\SiteCache;

/**
 * Grouped settings (general, seo, pixels, login, dashboard, theme_*,
 * section.*, layout.*). Stored values are merged over config defaults.
 */
class SettingsService
{
    public function all(): array
    {
        return SiteCache::remember('settings', function () {
            $stored = Setting::query()->pluck('value', 'key')->all();

            $merged = [];
            foreach (config('settings') as $group => $defaults) {
                $merged[$group] = $this->merge($defaults, $stored[$group] ?? []);
            }

            foreach ($stored as $key => $value) {
                $merged[$key] ??= $value;
            }

            return $merged;
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        if (array_key_exists($key, $all)) {
            return $all[$key] ?? $default;
        }

        [$group, $path] = array_pad(explode('.', $key, 2), 2, null);
        $value = $all[$group] ?? null;

        if ($path === null) {
            return $value ?? $default;
        }

        return data_get($value, $path, $default);
    }

    /** Current-locale value of a translatable setting. */
    public function trans(string $key, ?string $locale = null): string
    {
        return tval($this->get($key), $locale);
    }

    public function group(string $group): array
    {
        return (array) ($this->all()[$group] ?? []);
    }

    public function stored(string $group): ?array
    {
        return Setting::query()->where('key', $group)->value('value');
    }

    public function put(string $group, array $value): void
    {
        Setting::query()->updateOrCreate(['key' => $group], ['value' => $value]);
        SiteCache::flush();
    }

    public function forget(string $group): void
    {
        Setting::query()->where('key', $group)->delete();
        SiteCache::flush();
    }

    public function defaults(string $group): array
    {
        return (array) config("settings.{$group}", []);
    }

    /**
     * Recursive merge where lists (repeaters, social links) are replaced as a
     * whole instead of merged index by index.
     */
    protected function merge(array $defaults, array $stored): array
    {
        foreach ($stored as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])
                && ! array_is_list($value) && ! array_is_list($defaults[$key])) {
                $defaults[$key] = $this->merge($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }
}
