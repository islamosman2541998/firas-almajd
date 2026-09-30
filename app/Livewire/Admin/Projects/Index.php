<?php

namespace App\Livewire\Admin\Projects;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\Project;
use App\Models\Service;
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
    public array $filters = ['service' => ''];

    public $image = null;

    protected array $uploadProperties = ['image'];

    protected array $sortable = ['created_at', 'updated_at'];

    protected function modelClass(): string
    {
        return Project::class;
    }

    protected function baseQuery(): Builder
    {
        return Project::query()->with('service')
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title', 'location', 'category']))
            ->when($this->filters['service'] ?? '', fn ($q, $id) => $q->where('service_id', $id))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function formDefaults(): array
    {
        return ['title' => $this->blankT(), 'location' => $this->blankT(), 'category' => $this->blankT(), 'service_id' => '', 'image' => null, 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return [
            'title' => $this->translations($record, 'title'),
            'location' => $this->translations($record, 'location'),
            'category' => $this->translations($record, 'category'),
            'service_id' => (string) ($record->service_id ?? ''),
            'image' => $record->image,
            'is_active' => $record->is_active,
        ];
    }

    protected function formRules(): array
    {
        return [
            ...$this->tRules('form.title', true, 180),
            ...$this->tRules('form.location', false, 120),
            ...$this->tRules('form.category', false, 180),
            'form.service_id' => ['nullable', 'exists:services,id'],
            'form.is_active' => ['boolean'],
            'image' => [$this->form['image'] ? 'nullable' : 'required', 'image', 'max:'.config('site.uploads.image_max_kb')],
        ];
    }

    protected function formAttributes(): array
    {
        return [...$this->tAttributes('form.title', __('admin.fields.title')), 'image' => __('admin.fields.image')];
    }

    protected function persist(?Model $record): Model
    {
        if ($this->image) {
            $this->form['image'] = app(MediaUploader::class)->replace($record?->image, $this->image, 'projects', true, 1800);
        }

        $record ??= new Project;
        $record->fill([
            'title' => $this->cleanT($this->form['title']),
            'location' => $this->cleanT($this->form['location']),
            'category' => $this->cleanT($this->form['category']),
            'service_id' => $this->form['service_id'] ?: null,
            'image' => $this->form['image'],
            'is_active' => $this->form['is_active'],
        ])->save();

        return $record;
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->image);
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.projects.location').' (AR)' => fn ($r) => $r->getTranslation('location', 'ar', false),
            __('admin.projects.location').' (EN)' => fn ($r) => $r->getTranslation('location', 'en', false),
            __('admin.projects.category').' (AR)' => fn ($r) => $r->getTranslation('category', 'ar', false),
            __('admin.projects.category').' (EN)' => fn ($r) => $r->getTranslation('category', 'en', false),
            __('admin.projects.service') => fn ($r) => $r->service?->title,
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
            __('admin.common.created_at') => fn ($r) => $r->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.projects.index', [
            'rows' => $this->rows(),
            'services' => Service::query()->ordered()->get(['id', 'title'])->pluck('title', 'id')->all(),
        ])->title(__('admin.nav.projects'));
    }
}
