<?php

namespace App\Livewire\Admin\Settings;

use App\Livewire\Admin\Concerns\WithListItems;
use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Services\MediaUploader;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * One settings group edited as $data. Subclasses declare the group, the
 * rules, translatable keys and image keys; save/reset are shared.
 */
abstract class SettingsPage extends Component
{
    use WithFileUploads, WithListItems, WithToast, WithTranslatableForm;

    public array $data = [];

    public array $uploads = [];

    abstract protected function group(): string;

    abstract protected function rules(): array;

    /** Keys holding ['ar' => .., 'en' => ..]. */
    protected function translatable(): array
    {
        return [];
    }

    /** Image keys: [key => [directory, maxWidth, convertToWebp]]. */
    protected function images(): array
    {
        return [];
    }

    /** Keys owned by other screens (kept untouched on save). */
    protected function excluded(): array
    {
        return [];
    }

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        $data = Arr::except(settings()->group($this->group()), $this->excluded());
        foreach ($this->translatable() as $key) {
            $data[$key] = $this->tValue($data[$key] ?? null);
        }
        $this->data = $data;
        $this->uploads = [];
    }

    protected function imageRules(): array
    {
        return collect($this->images())->mapWithKeys(fn ($cfg, $key) => ["uploads.{$key}" => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg,ico,gif', 'max:'.config('site.uploads.image_max_kb')]])->all();
    }

    public function save(): void
    {
        $this->validate([...$this->rules(), ...$this->imageRules()], [], $this->attributes());

        $uploader = app(MediaUploader::class);
        foreach ($this->images() as $key => [$directory, $maxWidth, $convert]) {
            if ($this->uploads[$key] ?? null) {
                $this->data[$key] = $uploader->image($this->uploads[$key], $directory, $maxWidth, $convert);
            }
        }

        $this->beforeSave();

        $stored = settings()->stored($this->group()) ?? [];
        settings()->put($this->group(), array_replace($stored, Arr::except($this->data, $this->excluded())));

        $this->uploads = [];
        $this->saved();
        $this->afterSave();
    }

    protected function beforeSave(): void {}

    protected function afterSave(): void {}

    protected function attributes(): array
    {
        return [];
    }

    public function resetDefaults(): void
    {
        $stored = settings()->stored($this->group()) ?? [];
        $keep = Arr::only($stored, $this->excluded());
        $keep ? settings()->put($this->group(), $keep) : settings()->forget($this->group());
        $this->loadData();
        $this->toast(__('admin.settings.reset_done'));
    }

    public function clearImage(string $key): void
    {
        $this->data[$key] = config("settings.{$this->group()}.{$key}");
    }
}
