<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Media extends Model
{
    use FlushesSiteCache, HasTranslations;

    public const TYPES = ['image', 'video', 'pdf', 'embed'];

    protected $fillable = ['collection', 'type', 'path', 'poster', 'url', 'title', 'mime', 'size', 'layout', 'sort_order'];

    public array $translatable = ['title'];

    protected static function booted(): void
    {
        static::deleted(function (Media $media) {
            foreach ([$media->path, $media->poster] as $file) {
                if ($file && ! str_starts_with($file, 'seed/')) {
                    Storage::disk('public')->delete($file);
                }
            }
        });
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function fileUrl(): ?string
    {
        return media_url($this->path);
    }

    /** Embeddable URL for YouTube / Vimeo links. */
    public function embedUrl(): ?string
    {
        $url = (string) $this->url;

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{6,})~', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return $url ?: null;
    }

    public function thumbnail(): ?string
    {
        if ($this->type === 'image') {
            return $this->fileUrl();
        }
        if ($this->poster) {
            return media_url($this->poster);
        }
        if ($this->type === 'embed' && preg_match('~youtube-nocookie\.com/embed/([\w-]+)~', (string) $this->embedUrl(), $m)) {
            return "https://i.ytimg.com/vi/{$m[1]}/hqdefault.jpg";
        }

        return null;
    }

    public function humanSize(): string
    {
        $size = (float) $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < 3) {
            $size /= 1024;
            $i++;
        }

        return round($size, 1).' '.$units[$i];
    }
}
