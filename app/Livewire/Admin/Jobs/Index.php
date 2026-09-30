<?php

namespace App\Livewire\Admin\Jobs;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\CareerJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable, WithDrawerForm;

    protected array $sortable = ['created_at', 'applications_count'];

    protected function modelClass(): string
    {
        return CareerJob::class;
    }

    protected function baseQuery(): Builder
    {
        return CareerJob::query()->withCount(['applications', 'applications as new_applications_count' => fn ($q) => $q->where('status', 'new')])
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title', 'description', 'location', 'employment_type']))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function formDefaults(): array
    {
        return ['title' => $this->blankT(), 'description' => $this->blankT(), 'employment_type' => ['ar' => 'دوام كامل', 'en' => 'Full time'], 'location' => ['ar' => 'الرياض', 'en' => 'Riyadh'], 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return [
            'title' => $this->translations($record, 'title'),
            'description' => $this->translations($record, 'description'),
            'employment_type' => $this->translations($record, 'employment_type'),
            'location' => $this->translations($record, 'location'),
            'is_active' => $record->is_active,
        ];
    }

    protected function formRules(): array
    {
        return [
            ...$this->tRules('form.title', true, 150),
            ...$this->tRules('form.description', false, 1000),
            ...$this->tRules('form.employment_type', false, 80),
            ...$this->tRules('form.location', false, 80),
            'form.is_active' => ['boolean'],
        ];
    }

    protected function formAttributes(): array
    {
        return $this->tAttributes('form.title', __('admin.fields.title'));
    }

    protected function persist(?Model $record): Model
    {
        $record ??= new CareerJob;
        $record->fill([
            'title' => $this->cleanT($this->form['title']),
            'description' => $this->cleanT($this->form['description']),
            'employment_type' => $this->cleanT($this->form['employment_type']),
            'location' => $this->cleanT($this->form['location']),
            'is_active' => $this->form['is_active'],
        ])->save();

        return $record;
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.jobs.type') => fn ($r) => $r->employment_type,
            __('admin.jobs.location') => fn ($r) => $r->location,
            __('admin.nav.applications') => fn ($r) => $r->applications_count,
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
        ];
    }

    public function render()
    {
        return view('livewire.admin.jobs.index', ['rows' => $this->rows()])->title(__('admin.nav.jobs'));
    }
}
