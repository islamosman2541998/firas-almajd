<?php

namespace App\Livewire\Admin\Media;

use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Models\Media;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Gallery of any model (images, videos, PDFs, YouTube / Vimeo links).
 * Used inside forms: <livewire:admin.media.manager owner-type="page" :owner-id="$page->id" />
 */
class Manager extends Component
{
    use WithFileUploads, WithToast, WithTranslatableForm;

    #[Locked]
    public string $ownerType;

    #[Locked]
    public int $ownerId;

    #[Locked]
    public string $collection = 'gallery';

    #[Locked]
    public bool $allowPdf = true;

    public array $uploads = [];

    public string $embedUrl = '';

    public ?int $editingId = null;

    public array $form = [];

    public $poster = null;

    public function mount(string $ownerType, int $ownerId, string $collection = 'gallery', bool $allowPdf = true): void
    {
        $this->ownerType = $ownerType;
        $this->ownerId = $ownerId;
        $this->collection = $collection;
        $this->allowPdf = $allowPdf;
    }

    protected function owner()
    {
        return Relation::getMorphedModel($this->ownerType)::findOrFail($this->ownerId);
    }

    public function updatedUploads(): void
    {
        $kb = config('site.uploads');
        $this->validate([
            'uploads' => ['array', 'max:30'],
            'uploads.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,image/svg+xml,video/mp4,video/webm'.($this->allowPdf ? ',application/pdf' : ''), 'max:'.$kb['video_max_kb']],
        ], [], ['uploads.*' => __('admin.media.file')]);

        $uploader = app(MediaUploader::class);
        $owner = $this->owner();
        $order = (int) $owner->media()->max('sort_order');
        $added = 0;

        foreach ($this->uploads as $file) {
            $mime = $file->getMimeType();
            [$type, $path] = match (true) {
                str_starts_with($mime, 'image/') => ['image', $uploader->image($file, 'media/images')],
                str_starts_with($mime, 'video/') => ['video', $uploader->file($file, 'media/videos')],
                default => ['pdf', $uploader->file($file, 'media/files')],
            };

            if ($type === 'image' && $file->getSize() > $kb['image_max_kb'] * 1024) {
                $uploader->delete($path);

                continue;
            }

            $owner->media()->create([
                'collection' => $this->collection,
                'type' => $type,
                'path' => $path,
                'mime' => $mime,
                'size' => $file->getSize(),
                'title' => $type === 'pdf' ? ['ar' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), 'en' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)] : [],
                'sort_order' => ++$order,
            ]);
            $added++;
        }

        $this->uploads = [];
        $this->toast(__('admin.media.added', ['count' => $added]));
    }

    public function addEmbed(): void
    {
        $this->validate(['embedUrl' => ['required', 'url', 'regex:~(youtube\.com|youtu\.be|vimeo\.com)~i']], [], ['embedUrl' => __('admin.media.embed_url')]);

        $this->owner()->media()->create([
            'collection' => $this->collection,
            'type' => 'embed',
            'url' => $this->embedUrl,
            'title' => [],
            'sort_order' => (int) $this->owner()->media()->max('sort_order') + 1,
        ]);

        $this->embedUrl = '';
        $this->toast(__('admin.media.added', ['count' => 1]));
    }

    public function edit(int $id): void
    {
        $media = $this->find($id);
        $this->editingId = $media->id;
        $this->poster = null;
        $this->form = [
            'title' => $this->translations($media, 'title'),
            'layout' => $media->layout,
            'url' => $media->url,
            'type' => $media->type,
            'poster' => $media->poster,
        ];
    }

    public function closeForm(): void
    {
        $this->editingId = null;
        $this->poster = null;
        $this->resetValidation();
    }

    public function save(): void
    {
        $media = $this->find($this->editingId);
        $this->validate([
            'form.title.*' => ['nullable', 'string', 'max:180'],
            'form.layout' => ['required', 'in:normal,wide,tall'],
            'form.url' => [$media->type === 'embed' ? 'required' : 'nullable', 'nullable', 'url'],
            'poster' => ['nullable', 'image', 'max:'.config('site.uploads.image_max_kb')],
        ]);

        if ($this->poster) {
            $this->form['poster'] = app(MediaUploader::class)->replace($media->poster, $this->poster, 'media/posters', true, 1600);
        }

        $media->update([
            'title' => $this->cleanT($this->form['title']),
            'layout' => $this->form['layout'],
            'url' => $media->type === 'embed' ? $this->form['url'] : $media->url,
            'poster' => $this->form['poster'],
        ]);

        $this->closeForm();
        $this->toast(__('admin.common.updated'));
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
        $this->toast(__('admin.common.deleted'));
    }

    public function reorder(int $id, int $position): void
    {
        $ids = $this->owner()->media()->where('collection', $this->collection)->pluck('id')
            ->reject(fn ($v) => $v === $id)->values()->all();
        array_splice($ids, $position, 0, [$id]);
        foreach ($ids as $i => $mediaId) {
            Media::query()->whereKey($mediaId)->update(['sort_order' => $i + 1]);
        }
        \App\Support\SiteCache::flush();
        $this->toast(__('admin.common.order_saved'));
    }

    protected function find(int $id): Media
    {
        return Media::query()->where('mediable_type', $this->ownerType)->where('mediable_id', $this->ownerId)->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.media.manager', [
            'items' => Media::query()->where('mediable_type', $this->ownerType)->where('mediable_id', $this->ownerId)
                ->where('collection', $this->collection)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
