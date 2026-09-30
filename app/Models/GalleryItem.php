<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GalleryItem extends Model
{
    use FlushesSiteCache, HasTranslations, Sortable;

    public const TYPES = ['image', 'video'];

    protected $fillable = ['title', 'type', 'image', 'video', 'layout', 'is_active', 'sort_order'];

    public array $translatable = ['title'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function isVideo(): bool
    {
        return $this->type === 'video' && filled($this->video);
    }
}
