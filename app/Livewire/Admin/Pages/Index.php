<?php

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Models\MenuItem;
use App\Models\Page;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable;

    protected array $sortable = ['slug', 'created_at', 'updated_at'];

    protected function modelClass(): string
    {
        return Page::class;
    }

    protected function baseQuery(): Builder
    {
        return Page::query()
            ->withCount('media')
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title', 'description', 'slug']))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function beforeDelete(Model $record): void
    {
        app(MediaUploader::class)->delete($record->image);
        MenuItem::query()->where('linkable_type', 'page')->where('linkable_id', $record->id)->delete();
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.slug') => fn ($r) => $r->slug,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.pages.media_count') => fn ($r) => $r->media_count,
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
            __('admin.common.url') => fn ($r) => route('pages.show', ['locale' => 'ar', 'page' => $r->slug]),
            __('admin.common.updated_at') => fn ($r) => $r->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function addToMenu(int $id): void
    {
        $page = Page::findOrFail($id);
        MenuItem::create([
            'location' => 'header',
            'title' => $page->getTranslations('title'),
            'type' => 'page',
            'linkable_type' => 'page',
            'linkable_id' => $page->id,
            'is_active' => true,
            'sort_order' => (int) MenuItem::query()->whereNull('parent_id')->max('sort_order') + 1,
        ]);
        $this->toast(__('admin.pages.added_to_menu'));
    }

    public function render()
    {
        return view('livewire.admin.pages.index', [
            'rows' => $this->rows(),
            'inMenu' => MenuItem::query()->where('linkable_type', 'page')->pluck('linkable_id')->all(),
        ])->title(__('admin.nav.pages'));
    }
}
