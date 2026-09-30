<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class CareerJob extends Model
{
    use FlushesSiteCache, HasTranslations, Sortable;

    protected $fillable = ['title', 'description', 'employment_type', 'location', 'is_active', 'sort_order'];

    public array $translatable = ['title', 'description', 'employment_type', 'location'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
