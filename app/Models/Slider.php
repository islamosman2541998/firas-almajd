<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use FlushesSiteCache, HasTranslations, Sortable;

    protected $fillable = [
        'media_type', 'image', 'video', 'title', 'description', 'show_button', 'button_text', 'button_url',
        'button_new_tab', 'button_bg', 'button_color', 'button_hover_bg', 'overlay_opacity', 'is_active', 'sort_order',
    ];

    public array $translatable = ['title', 'description', 'button_text'];

    protected function casts(): array
    {
        return [
            'show_button' => 'boolean',
            'button_new_tab' => 'boolean',
            'is_active' => 'boolean',
            'overlay_opacity' => 'integer',
        ];
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video' && filled($this->video);
    }
}
