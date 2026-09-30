<?php

namespace App\Livewire\Admin\Certificates;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\Certificate;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithDataTable, WithDrawerForm, WithFileUploads;

    public $image = null;

    public $file = null;

    protected array $uploadProperties = ['image', 'file'];

    protected array $sortable = ['created_at'];

    protected function modelClass(): string
    {
        return Certificate::class;
    }

    protected function baseQuery(): Builder
    {
        return Certificate::query()
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title']))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function formDefaults(): array
    {
        return ['title' => $this->blankT(), 'image' => null, 'file' => null, 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return ['title' => $this->translations($record, 'title'), 'image' => $record->image, 'file' => $record->file, 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [
            ...$this->tRules('form.title', false, 180),
            'form.is_active' => ['boolean'],
            'image' => [$this->form['image'] ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('site.uploads.image_max_kb')],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:'.config('site.uploads.pdf_max_kb')],
        ];
    }

    public function removeFile(): void
    {
        $this->form['file'] = null;
    }

    protected function persist(?Model $record): Model
    {
        $uploader = app(MediaUploader::class);
        if ($this->image) {
            $this->form['image'] = $uploader->replace($record?->image, $this->image, 'certificates', true, 1600);
        }
        if ($this->file) {
            $this->form['file'] = $uploader->replace($record?->file, $this->file, 'certificates/files', false);
        }

        $record ??= new Certificate;
        $record->fill(['title' => $this->cleanT($this->form['title']), 'image' => $this->form['image'], 'file' => $this->form['file'], 'is_active' => $this->form['is_active']])->save();

        return $record;
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->image);
        app(MediaUploader::class)->delete($record->file);
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.fields.image') => fn ($r) => media_url($r->image),
            __('admin.certificates.file') => fn ($r) => media_url($r->file),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
        ];
    }

    public function render()
    {
        return view('livewire.admin.certificates.index', ['rows' => $this->rows()])->title(__('admin.nav.certificates'));
    }
}
