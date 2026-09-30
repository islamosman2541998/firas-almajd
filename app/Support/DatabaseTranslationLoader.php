<?php

namespace App\Support;

use App\Models\UiTranslation;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Decorates the file loader: lines edited from the dashboard ("Site texts")
 * override lang/{locale}/{group}.php for the editable groups.
 */
class DatabaseTranslationLoader implements Loader
{
    public const GROUPS = ['site'];

    protected const CACHE_KEY = 'ui_translations';

    protected static ?array $lines = null;

    public function __construct(protected Loader $files) {}

    public function load($locale, $group, $namespace = null)
    {
        $lines = $this->files->load($locale, $group, $namespace);

        if (($namespace === null || $namespace === '*') && in_array($group, self::GROUPS, true)) {
            foreach (static::overrides()[$group] ?? [] as $key => $values) {
                if (filled($values[$locale] ?? null)) {
                    data_set($lines, $key, $values[$locale]);
                }
            }
        }

        return $lines;
    }

    public static function overrides(): array
    {
        if (static::$lines !== null) {
            return static::$lines;
        }

        try {
            return static::$lines = Cache::rememberForever(self::CACHE_KEY, function () {
                $grouped = [];
                foreach (UiTranslation::query()->get(['group', 'key', 'value']) as $row) {
                    $grouped[$row->group][$row->key] = $row->value;
                }

                return $grouped;
            });
        } catch (Throwable) {
            return static::$lines = []; // table not migrated yet
        }
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        static::$lines = null;
        app('translator')->setLoaded([]);
    }

    public function addNamespace($namespace, $hint)
    {
        $this->files->addNamespace($namespace, $hint);
    }

    public function addPath($path)
    {
        if (method_exists($this->files, 'addPath')) {
            $this->files->addPath($path);
        }
    }

    public function addJsonPath($path)
    {
        $this->files->addJsonPath($path);
    }

    public function namespaces()
    {
        return $this->files->namespaces();
    }
}
