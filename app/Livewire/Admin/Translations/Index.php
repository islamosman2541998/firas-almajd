<?php

namespace App\Livewire\Admin\Translations;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\UiTranslation;
use Database\Seeders\UiTranslationSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * Interface labels of the public site (lang/{locale}/site.php overrides).
 */
class Index extends Component
{
    use WithDataTable, WithDrawerForm;

    protected array $sortable = ['key', 'updated_at'];

    protected function modelClass(): string
    {
        return UiTranslation::class;
    }

    protected function baseQuery(): Builder
    {
        return UiTranslation::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('key', 'like', "%{$this->search}%")
                ->orWhere('value->ar', 'like', "%{$this->search}%")->orWhere('value->en', 'like', "%{$this->search}%")))
            ->orderBy('key');
    }

    protected function formDefaults(): array
    {
        return ['key' => '', 'value' => $this->blankT()];
    }

    protected function formFromModel(Model $record): array
    {
        return ['key' => $record->key, 'value' => $this->tValue($record->value)];
    }

    protected function formRules(): array
    {
        return [
            'form.key' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9_.]+$/'],
            'form.value.ar' => ['required', 'string', 'max:2000'],
            'form.value.en' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function persist(?Model $record): Model
    {
        $record ??= new UiTranslation(['group' => 'site']);
        $record->fill(['key' => $record->exists ? $record->key : $this->form['key'], 'value' => $this->form['value']])->save();

        return $record;
    }

    /** Restore the label from lang files. */
    public function restore(int $id): void
    {
        $row = UiTranslation::findOrFail($id);
        $value = [];
        foreach (array_keys(config('site.locales')) as $locale) {
            $file = lang_path("{$locale}/{$row->group}.php");
            $value[$locale] = data_get(is_file($file) ? require $file : [], $row->key, '');
        }
        $row->update(['value' => $value]);
        $this->toast(__('admin.translations.restored'));
    }

    public function sync(): void
    {
        (new UiTranslationSeeder)->run();
        $this->toast(__('admin.translations.synced'));
    }

    protected function exportColumns(): array
    {
        return [
            'key' => fn ($r) => $r->key,
            'AR' => fn ($r) => $r->value['ar'] ?? '',
            'EN' => fn ($r) => $r->value['en'] ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.translations.index', ['rows' => $this->rows()])->title(__('admin.nav.translations'));
    }
}
