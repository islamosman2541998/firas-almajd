<?php

namespace App\Livewire\Admin\Gallery;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\GalleryItem;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithDataTable, WithDrawerForm, WithFileUploads;

    #[Url(except: '')]
    public array $filters = ['layout' => '', 'type' => ''];

    public $image = null;

    public $video = null;

    public array $bulk = [];

    protected array $uploadProperties = ['image', 'video'];

    protected array $sortable = ['created_at'];

    protected function modelClass(): string
    {
        return GalleryItem::class;
    }

    protected function baseQuery(): Builder
    {
        return GalleryItem::query()
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title']))
            ->when($this->filters['layout'] ?? '', fn ($q, $layout) => $q->where('layout', $layout))
            ->when($this->filters['type'] ?? '', fn ($q, $type) => $q->where('type', $type))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    /** Multi-upload: every file (image or video) becomes a gallery item. */
    public function updatedBulk(): void
    {
        $kb = config('site.uploads');
        $this->validate([
            'bulk' => ['array', 'max:40'],
            'bulk.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime', 'max:'.$kb['video_max_kb']],
        ], [], ['bulk.*' => __('admin.media.file')]);

        $uploader = app(MediaUploader::class);
        foreach ($this->bulk as $file) {
            if (str_starts_with((string) $file->getMimeType(), 'video/')) {
                GalleryItem::create(['type' => 'video', 'video' => $uploader->file($file, 'gallery/videos'), 'layout' => 'normal', 'title' => [], 'is_active' => true]);
            } elseif ($file->getSize() <= $kb['image_max_kb'] * 1024) {
                GalleryItem::create(['type' => 'image', 'image' => $uploader->image($file, 'gallery'), 'layout' => 'normal', 'title' => [], 'is_active' => true]);
            }
        }

        $this->toast(__('admin.media.added', ['count' => count($this->bulk)]));
        $this->bulk = [];
    }

    protected function formDefaults(): array
    {
        return ['title' => $this->blankT(), 'type' => 'image', 'layout' => 'normal', 'image' => null, 'video' => null, 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return ['title' => $this->translations($record, 'title'), 'type' => $record->type ?: 'image', 'layout' => $record->layout, 'image' => $record->image, 'video' => $record->video, 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [
            ...$this->tRules('form.title', false, 180),
            'form.layout' => ['required', 'in:normal,wide,tall'],
            'form.is_active' => ['boolean'],
            'form.type' => ['required', 'in:image,video'],
            'image' => [$this->form['type'] === 'image' && ! $this->form['image'] ? 'required' : 'nullable', 'image', 'max:'.config('site.uploads.image_max_kb')],
            'video' => [$this->form['type'] === 'video' && ! $this->form['video'] ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:'.config('site.uploads.video_max_kb')],
        ];
    }

    protected function formAttributes(): array
    {
        return ['image' => __('admin.fields.image'), 'video' => __('admin.sliders.video')];
    }

    public function removeImage(): void
    {
        $this->form['image'] = null;
    }

    protected function persist(?Model $record): Model
    {
        $uploader = app(MediaUploader::class);
        if ($this->image) {
            $this->form['image'] = $uploader->replace($record?->image, $this->image, 'gallery');
        } elseif ($record?->image && ! $this->form['image']) {
            $uploader->delete($record->image);
        }
        if ($this->video) {
            $this->form['video'] = $uploader->replace($record?->video, $this->video, 'gallery/videos', false);
        }

        $record ??= new GalleryItem;
        $record->fill([
            'title' => $this->cleanT($this->form['title']),
            'type' => $this->form['type'],
            'layout' => $this->form['layout'],
            'image' => $this->form['image'],
            'video' => $this->form['type'] === 'video' ? $this->form['video'] : $record->video,
            'is_active' => $this->form['is_active'],
        ])->save();

        return $record;
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->image);
        app(MediaUploader::class)->delete($record->video);
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.sliders.media_type') => fn ($r) => __('admin.sliders.types.'.($r->type ?: 'image')),
            __('admin.media.layout') => fn ($r) => __('admin.media.layouts.'.$r->layout),
            __('admin.fields.image') => fn ($r) => media_url($r->image),
            __('admin.sliders.video') => fn ($r) => media_url($r->video),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
        ];
    }

    public function render()
    {
        return view('livewire.admin.gallery.index', ['rows' => $this->rows()])->title(__('admin.nav.gallery'));
    }
}
