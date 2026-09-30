<?php

namespace App\Livewire\Admin\Partners;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\Partner;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithDataTable, WithDrawerForm, WithFileUploads;

    public $logo = null;

    protected array $uploadProperties = ['logo'];

    protected array $sortable = ['name', 'created_at'];

    protected function modelClass(): string
    {
        return Partner::class;
    }

    protected function baseQuery(): Builder
    {
        return Partner::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('url', 'like', "%{$this->search}%")))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function formDefaults(): array
    {
        return ['name' => '', 'url' => '', 'logo' => null, 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return ['name' => $record->name, 'url' => (string) $record->url, 'logo' => $record->logo, 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:150'],
            'form.url' => ['nullable', 'url', 'max:255'],
            'form.is_active' => ['boolean'],
            'logo' => [$this->form['logo'] ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('site.uploads.image_max_kb')],
        ];
    }

    protected function formAttributes(): array
    {
        return ['form.name' => __('admin.fields.name'), 'form.url' => __('admin.common.url'), 'logo' => __('admin.partners.logo')];
    }

    protected function persist(?Model $record): Model
    {
        if ($this->logo) {
            $this->form['logo'] = app(MediaUploader::class)->replace($record?->logo, $this->logo, 'partners', true, 800);
        }

        $record ??= new Partner;
        $record->fill(['name' => trim($this->form['name']), 'url' => $this->form['url'] ?: null, 'logo' => $this->form['logo'], 'is_active' => $this->form['is_active']])->save();

        return $record;
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->logo);
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.name') => fn ($r) => $r->name,
            __('admin.common.url') => fn ($r) => $r->url,
            __('admin.partners.logo') => fn ($r) => media_url($r->logo),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
        ];
    }

    public function render()
    {
        return view('livewire.admin.partners.index', ['rows' => $this->rows()])->title(__('admin.nav.partners'));
    }
}
