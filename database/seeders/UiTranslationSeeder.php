<?php

namespace Database\Seeders;

use App\Models\UiTranslation;
use App\Support\DatabaseTranslationLoader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

/**
 * Copies lang/{ar,en}/site.php into the database so every label is listed
 * (and editable) in the dashboard. Existing edits are kept.
 */
class UiTranslationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DatabaseTranslationLoader::GROUPS as $group) {
            $lines = [];
            foreach (array_keys(config('site.locales')) as $locale) {
                $file = lang_path("{$locale}/{$group}.php");
                foreach (Arr::dot(is_file($file) ? require $file : []) as $key => $value) {
                    $lines[$key][$locale] = $value;
                }
            }

            foreach ($lines as $key => $value) {
                UiTranslation::query()->firstOrCreate(['group' => $group, 'key' => $key], ['value' => $value]);
            }
        }

        DatabaseTranslationLoader::flush();
    }
}
