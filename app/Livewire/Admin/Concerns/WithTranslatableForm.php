<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Helpers for ar/en form fields bound as form.{field}.{locale}.
 */
trait WithTranslatableForm
{
    protected function blankT(): array
    {
        return array_fill_keys(array_keys(config('site.locales')), '');
    }

    /** Translations of a model attribute, with every locale key present. */
    protected function translations(?Model $model, string $attribute): array
    {
        return array_replace($this->blankT(), $model ? array_map(fn ($v) => (string) $v, $model->getTranslations($attribute)) : []);
    }

    /** Normalise a stored ['ar' => .., 'en' => ..] value for the form. */
    protected function tValue(mixed $value): array
    {
        return array_replace($this->blankT(), is_array($value) ? array_map(fn ($v) => (string) $v, $value) : ['ar' => (string) $value]);
    }

    /**
     * Rules for a translatable field: Arabic required (optional), English optional.
     */
    protected function tRules(string $key, bool $required = true, int $max = 255): array
    {
        return [
            "{$key}.ar" => [$required ? 'required' : 'nullable', 'string', "max:{$max}"],
            "{$key}.en" => ['nullable', 'string', "max:{$max}"],
        ];
    }

    protected function tAttributes(string $key, string $label): array
    {
        return [
            "{$key}.ar" => $label.' ('.__('admin.common.lang_ar').')',
            "{$key}.en" => $label.' ('.__('admin.common.lang_en').')',
        ];
    }

    /** Drop empty strings so Spatie stores only filled locales. */
    protected function cleanT(array $value): array
    {
        return array_filter(array_map(fn ($v) => is_string($v) ? trim($v) : $v, $value), fn ($v) => $v !== '' && $v !== null);
    }
}
