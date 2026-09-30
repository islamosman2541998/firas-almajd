<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Create / edit in a side drawer on the list page (simple modules).
 *
 * The component provides:
 *   formDefaults(): array            — empty form
 *   formFromModel(Model): array      — form filled from a record
 *   formRules(): array               — validation rules
 *   persist(?Model): Model           — save (handles uploads)
 *   $uploadProperties (array)        — temporary upload properties to clear
 */
trait WithDrawerForm
{
    use WithTranslatableForm;

    public bool $formOpen = false;

    public ?int $editingId = null;

    public array $form = [];

    abstract protected function formDefaults(): array;

    abstract protected function formFromModel(Model $record): array;

    abstract protected function formRules(): array;

    abstract protected function persist(?Model $record): Model;

    protected function formAttributes(): array
    {
        return [];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->resetUploads();
        $this->editingId = null;
        $this->form = $this->formDefaults();
        $this->formOpen = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $this->resetUploads();
        $record = $this->modelClass()::findOrFail($id);
        $this->editingId = $record->getKey();
        $this->form = $this->formFromModel($record);
        $this->formOpen = true;
    }

    public function closeForm(): void
    {
        $this->formOpen = false;
        $this->editingId = null;
        $this->resetValidation();
        $this->resetUploads();
    }

    public function save(): void
    {
        $this->validate($this->formRules(), [], $this->formAttributes());

        $record = $this->editingId ? $this->modelClass()::findOrFail($this->editingId) : null;
        $this->persist($record);

        $this->toast($record ? __('admin.common.updated') : __('admin.common.created'));
        $this->closeForm();
    }

    protected function resetUploads(): void
    {
        foreach ($this->uploadProperties ?? [] as $property) {
            $this->{$property} = null;
        }
    }
}
