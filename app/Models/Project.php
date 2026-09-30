<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use FlushesSiteCache, HasTranslations, Sortable;

    protected $fillable = ['title', 'location', 'category', 'service_id', 'image', 'is_active', 'sort_order'];

    public array $translatable = ['title', 'location', 'category'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
