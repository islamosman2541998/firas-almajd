<?php

namespace App\Models;

use App\Support\DatabaseTranslationLoader;
use Illuminate\Database\Eloquent\Model;

class UiTranslation extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => DatabaseTranslationLoader::flush());
        static::deleted(fn () => DatabaseTranslationLoader::flush());
    }
}
