<?php

namespace App\Livewire\Admin\Services;

use App\Livewire\Admin\Concerns\WithListItems;
use App\Livewire\Admin\Concerns\WithSeoForm;
use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Models\Service;
use App\Services\MediaUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads, WithListItems, WithSeoForm, WithToast, WithTranslatableForm;

    public ?Service $service = null;

    public array $form = [];

    public $image = null;

    #[Url(except: 'content')]
    public string $tab = 'content';

    public function mount(?Service $service = null): void
    {
        $this->service = $service?->exists ? $service->load('seo', 'related') : null;
        $s = $this->service;

        $this->form = [
            'slug' => $s?->slug ?? '',
            'title' => $this->translations($s, 'title'),
            'short_description' => $this->translations($s, 'short_description'),
            'image' => $s?->image,
            'scope_items' => collect($s?->scope_items ?? [])->map(fn ($i) => $this->tValue($i))->all(),
            'prep_items' => collect($s?->prep_items ?? [])->map(fn ($i) => $this->tValue($i))->all(),
            'related' => $s ? $s->related->pluck('id')->map(fn ($id) => (string) $id)->all() : [],
            'show_on_home' => $s->show_on_home ?? true,
            'is_active' => $s->is_active ?? true,
        ];

        $this->fillSeo($s);
    }

    public function updatedFormTitleEn(string $value): void
    {
        if (! $this->service && $this->form['slug'] === '') {
            $this->form['slug'] = Str::slug($value);
        }
    }

    protected function rules(): array
    {
        return [
            'form.slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('services', 'slug')->ignore($this->service?->id)],
            ...$this->tRules('form.title', true, 150),
            ...$this->tRules('form.short_description', false, 500),
            'form.scope_items.*.ar' => ['nullable', 'string', 'max:200'],
            'form.scope_items.*.en' => ['nullable', 'string', 'max:200'],
            'form.prep_items.*.ar' => ['nullable', 'string', 'max:200'],
            'form.prep_items.*.en' => ['nullable', 'string', 'max:200'],
            'form.related' => ['array'],
            'form.related.*' => ['integer', 'exists:services,id'],
            'form.show_on_home' => ['boolean'],
            'form.is_active' => ['boolean'],
            'image' => [$this->form['image'] ? 'nullable' : 'required', 'image', 'max:'.config('site.uploads.image_max_kb')],
            ...$this->seoRules(),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            ...$this->tAttributes('form.title', __('admin.fields.title')),
            'form.slug' => __('admin.fields.slug'),
            'image' => __('admin.fields.image'),
        ];
    }

    public function save()
    {
        if (blank($this->form['slug'])) {
            $this->form['slug'] = Str::slug($this->form['title']['en'] ?: $this->form['title']['ar']) ?: 'service-'.Str::lower(Str::random(5));
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->tab = collect($e->validator->errors()->keys())->contains(fn ($k) => str_starts_with($k, 'seo')) ? 'seo' : 'content';
            throw $e;
        }

        $uploader = app(MediaUploader::class);
        if ($this->image) {
            $this->form['image'] = $uploader->replace($this->service?->image, $this->image, 'services', true, 1800);
            $this->image = null;
        }

        $service = $this->service ?? new Service;
        $service->fill([
            'slug' => Str::slug($this->form['slug']),
            'title' => $this->cleanT($this->form['title']),
            'short_description' => $this->cleanT($this->form['short_description']),
            'image' => $this->form['image'],
            'scope_items' => $this->cleanList($this->form['scope_items']),
            'prep_items' => $this->cleanList($this->form['prep_items']),
            'show_on_home' => $this->form['show_on_home'],
            'is_active' => $this->form['is_active'],
        ])->save();

        $related = collect($this->form['related'])->map(fn ($id) => (int) $id)->reject(fn ($id) => $id === $service->id)->values();
        $service->related()->sync($related->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i + 1]])->all());

        $this->persistSeo($service);

        if (! $this->service) {
            $this->flashToast(__('admin.common.created'));

            return $this->redirectRoute('admin.services.edit', $service);
        }

        $this->service = $service->fresh(['seo', 'related']);
        $this->saved();
    }

    public function render()
    {
        return view('livewire.admin.services.form', [
            'allServices' => Service::query()->ordered()->when($this->service, fn ($q) => $q->whereKeyNot($this->service->id))->get(['id', 'title', 'is_active']),
        ])->title($this->service ? __('admin.services.edit') : __('admin.services.create'));
    }
}
