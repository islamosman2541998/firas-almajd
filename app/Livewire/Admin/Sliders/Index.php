<?php

namespace App\Livewire\Admin\Sliders;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Models\Slider;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public array $filters = ['media_type' => ''];

    protected array $sortable = ['created_at', 'sort_order'];

    protected function modelClass(): string
    {
        return Slider::class;
    }

    protected function baseQuery(): Builder
    {
        return Slider::query()
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['title', 'description', 'button_text']))
            ->when($this->filters['media_type'] ?? '', fn ($q, $type) => $q->where('media_type', $type))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function beforeDelete(Model $record): void
    {
        $uploader = app(MediaUploader::class);
        $uploader->delete($record->image);
        $uploader->delete($record->video);
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.title').' (AR)' => fn ($r) => $r->getTranslation('title', 'ar', false),
            __('admin.fields.title').' (EN)' => fn ($r) => $r->getTranslation('title', 'en', false),
            __('admin.fields.description').' (AR)' => fn ($r) => $r->getTranslation('description', 'ar', false),
            __('admin.fields.description').' (EN)' => fn ($r) => $r->getTranslation('description', 'en', false),
            __('admin.sliders.media_type') => fn ($r) => __('admin.sliders.types.'.$r->media_type),
            __('admin.sliders.button_text').' (AR)' => fn ($r) => $r->getTranslation('button_text', 'ar', false),
            __('admin.sliders.button_text').' (EN)' => fn ($r) => $r->getTranslation('button_text', 'en', false),
            __('admin.sliders.button_url') => fn ($r) => link_url($r->button_url),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
            __('admin.common.order') => fn ($r) => $r->sort_order,
            __('admin.common.created_at') => fn ($r) => $r->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.sliders.index', ['rows' => $this->rows()])->title(__('admin.nav.sliders'));
    }
}
