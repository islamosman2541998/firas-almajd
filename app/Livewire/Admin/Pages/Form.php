<?php

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\Concerns\WithSeoForm;
use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Models\Page;
use App\Services\MediaUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads, WithSeoForm, WithToast, WithTranslatableForm;

    public ?Page $page = null;

    public array $form = [];

    public $image = null;

    #[Url(except: 'content')]
    public string $tab = 'content';

    public function mount(?Page $page = null): void
    {
        $this->page = $page?->exists ? $page->load('seo') : null;
        $p = $this->page;

        $this->form = [
            'slug' => $p?->slug ?? '',
            'title' => $this->translations($p, 'title'),
            'description' => $this->translations($p, 'description'),
            'image' => $p?->image,
            'is_active' => $p->is_active ?? true,
        ];

        $this->fillSeo($p);
    }

    public function updatedFormTitleEn(string $value): void
    {
        if (! $this->page && $this->form['slug'] === '') {
            $this->form['slug'] = Str::slug($value);
        }
    }

    protected function rules(): array
    {
        return [
            'form.slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('pages', 'slug')->ignore($this->page?->id)],
            ...$this->tRules('form.title', true, 180),
            ...$this->tRules('form.description', false, 20000),
            'form.is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:'.config('site.uploads.image_max_kb')],
            ...$this->seoRules(),
        ];
    }

    protected function validationAttributes(): array
    {
        return [...$this->tAttributes('form.title', __('admin.fields.title')), 'form.slug' => __('admin.fields.slug'), 'image' => __('admin.fields.image')];
    }

    public function removeImage(): void
    {
        $this->form['image'] = null;
    }

    public function save()
    {
        if (blank($this->form['slug'])) {
            $this->form['slug'] = Str::slug($this->form['title']['en'] ?: $this->form['title']['ar']) ?: 'page-'.Str::lower(Str::random(5));
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->tab = collect($e->validator->errors()->keys())->contains(fn ($k) => str_starts_with($k, 'seo')) ? 'seo' : 'content';
            throw $e;
        }

        $uploader = app(MediaUploader::class);
        $oldImage = $this->page?->image;
        if ($this->image) {
            $this->form['image'] = $uploader->image($this->image, 'pages', 2200);
            $this->image = null;
        }

        $page = $this->page ?? new Page;
        $page->fill([
            'slug' => Str::slug($this->form['slug']),
            'title' => $this->cleanT($this->form['title']),
            'description' => $this->cleanT($this->form['description']),
            'image' => $this->form['image'],
            'is_active' => $this->form['is_active'],
        ])->save();

        if ($oldImage && $oldImage !== $page->image) {
            $uploader->delete($oldImage);
        }

        $this->persistSeo($page);

        if (! $this->page) {
            $this->flashToast(__('admin.pages.created_add_media'));

            return $this->redirectRoute('admin.pages.edit', ['page' => $page, 'tab' => 'gallery']);
        }

        $this->page = $page->fresh('seo');
        $this->saved();
    }

    public function render()
    {
        return view('livewire.admin.pages.form')->title($this->page ? __('admin.pages.edit') : __('admin.pages.create'));
    }
}
