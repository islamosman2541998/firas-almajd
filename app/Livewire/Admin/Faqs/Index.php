<?php

namespace App\Livewire\Admin\Faqs;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable, WithDrawerForm;

    protected array $sortable = ['created_at'];

    protected function modelClass(): string
    {
        return Faq::class;
    }

    protected function baseQuery(): Builder
    {
        return Faq::query()
            ->when($this->search !== '', fn ($q) => $this->searchTranslatable($q, ['question', 'answer']))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->ordered();
    }

    protected function formDefaults(): array
    {
        return ['question' => $this->blankT(), 'answer' => $this->blankT(), 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return ['question' => $this->translations($record, 'question'), 'answer' => $this->translations($record, 'answer'), 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [...$this->tRules('form.question', true, 255), ...$this->tRules('form.answer', true, 3000), 'form.is_active' => ['boolean']];
    }

    protected function formAttributes(): array
    {
        return [...$this->tAttributes('form.question', __('admin.faqs.question')), ...$this->tAttributes('form.answer', __('admin.faqs.answer'))];
    }

    protected function persist(?Model $record): Model
    {
        $record ??= new Faq;
        $record->fill(['question' => $this->cleanT($this->form['question']), 'answer' => $this->cleanT($this->form['answer']), 'is_active' => $this->form['is_active']])->save();

        return $record;
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.faqs.question').' (AR)' => fn ($r) => $r->getTranslation('question', 'ar', false),
            __('admin.faqs.question').' (EN)' => fn ($r) => $r->getTranslation('question', 'en', false),
            __('admin.faqs.answer').' (AR)' => fn ($r) => $r->getTranslation('answer', 'ar', false),
            __('admin.faqs.answer').' (EN)' => fn ($r) => $r->getTranslation('answer', 'en', false),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
        ];
    }

    public function render()
    {
        return view('livewire.admin.faqs.index', ['rows' => $this->rows()])->title(__('admin.nav.faqs'));
    }
}
