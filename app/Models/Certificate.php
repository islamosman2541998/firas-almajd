<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Certificate extends Model
{
    use FlushesSiteCache, HasTranslations, Sortable;

    protected $fillable = ['title', 'image', 'file', 'is_active', 'sort_order'];

    public array $translatable = ['title'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
