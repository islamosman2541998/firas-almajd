<?php

namespace App\Livewire\Admin\Services;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Models\Service;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public array $filters = ['home' => ''];

    protected array $sortable = ['slug', 'created_at', 'updated_at'];

    protected function modelClass(): string
    {
        return Service::class;
    }

    protected function baseQuery(): Builder
    {
        return Service::query()
            ->withCount(['messages', 'projects'])
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title', 'short_description', 'slug']))
            ->when(($this->filters['home'] ?? '') !== '', fn ($q) => $q->where('show_on_home', $this->filters['home'] === 'yes'))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->image);
        \App\Models\MenuItem::query()->where('linkable_type', 'service')->where('linkable_id', $record->id)->delete();
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.slug') => fn ($r) => $r->slug,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.fields.short_description').' (AR)' => fn ($r) => $r->getTranslation('short_description', 'ar', false),
            __('admin.fields.short_description').' (EN)' => fn ($r) => $r->getTranslation('short_description', 'en', false),
            __('admin.services.scope_items').' (AR)' => fn ($r) => collect($r->scope_items)->pluck('ar')->implode(' | '),
            __('admin.services.scope_items').' (EN)' => fn ($r) => collect($r->scope_items)->pluck('en')->implode(' | '),
            __('admin.services.show_on_home') => fn ($r) => $this->yesNo($r->show_on_home),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
            __('admin.nav.messages') => fn ($r) => $r->messages_count,
            __('admin.common.url') => fn ($r) => route('services.show', ['locale' => 'ar', 'service' => $r->slug]),
            __('admin.common.updated_at') => fn ($r) => $r->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function toggleHome(int $id): void
    {
        $this->toggleActive($id, 'show_on_home');
    }

    public function render()
    {
        return view('livewire.admin.services.index', ['rows' => $this->rows()])->title(__('admin.nav.services'));
    }
}
