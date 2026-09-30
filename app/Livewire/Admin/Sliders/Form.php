<?php

namespace App\Livewire\Admin\Sliders;

use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Models\Slider;
use App\Services\MediaUploader;
use App\Support\LinkResolver;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads, WithToast, WithTranslatableForm;

    public ?Slider $slider = null;

    public array $form = [];

    public $image = null;

    public $video = null;

    public function mount(?Slider $slider = null): void
    {
        $this->slider = $slider?->exists ? $slider : null;
        $s = $this->slider;

        $this->form = [
            'media_type' => $s->media_type ?? 'image',
            'image' => $s?->image,
            'video' => $s?->video,
            'title' => $this->translations($s, 'title'),
            'description' => $this->translations($s, 'description'),
            'show_button' => $s->show_button ?? true,
            'button_text' => $this->translations($s, 'button_text'),
            'button_url' => $s->button_url ?? 'route:contact',
            'button_new_tab' => $s->button_new_tab ?? false,
            'button_bg' => $s?->button_bg ?? '',
            'button_color' => $s?->button_color ?? '',
            'button_hover_bg' => $s?->button_hover_bg ?? '',
            'overlay_opacity' => $s->overlay_opacity ?? 100,
            'is_active' => $s->is_active ?? true,
        ];
    }

    protected function rules(): array
    {
        $kb = config('site.uploads');
        $needsImage = $this->form['media_type'] === 'image' && ! $this->form['image'];
        $needsVideo = $this->form['media_type'] === 'video' && ! $this->form['video'];

        return [
            'form.media_type' => ['required', 'in:image,video'],
            ...$this->tRules('form.title', false, 180),
            ...$this->tRules('form.description', false, 400),
            ...$this->tRules('form.button_text', false, 60),
            'form.show_button' => ['boolean'],
            'form.button_url' => ['nullable', 'string', 'max:255'],
            'form.button_new_tab' => ['boolean'],
            'form.button_bg' => ['nullable', 'hex_color'],
            'form.button_color' => ['nullable', 'hex_color'],
            'form.button_hover_bg' => ['nullable', 'hex_color'],
            'form.overlay_opacity' => ['integer', 'between:0,100'],
            'form.is_active' => ['boolean'],
            'image' => [$needsImage ? 'required' : 'nullable', 'image', 'max:'.$kb['image_max_kb']],
            'video' => [$needsVideo ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:'.$kb['video_max_kb']],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'image' => __('admin.fields.image'),
            'video' => __('admin.sliders.video'),
            'form.button_bg' => __('admin.sliders.button_bg'),
            'form.button_color' => __('admin.sliders.button_color'),
            'form.button_hover_bg' => __('admin.sliders.button_hover_bg'),
        ];
    }

    public function removeImage(): void
    {
        $this->form['image'] = null;
    }

    public function removeVideo(): void
    {
        $this->form['video'] = null;
    }

    public function save()
    {
        $this->validate();
        $uploader = app(MediaUploader::class);
        $old = $this->slider?->only(['image', 'video']) ?? [];

        if ($this->image) {
            $this->form['image'] = $uploader->image($this->image, 'sliders', 2200);
        }
        if ($this->video) {
            $this->form['video'] = $uploader->file($this->video, 'sliders/videos');
        }

        $data = [
            ...$this->form,
            'title' => $this->cleanT($this->form['title']),
            'description' => $this->cleanT($this->form['description']),
            'button_text' => $this->cleanT($this->form['button_text']),
            'button_bg' => $this->form['button_bg'] ?: null,
            'button_color' => $this->form['button_color'] ?: null,
            'button_hover_bg' => $this->form['button_hover_bg'] ?: null,
        ];

        $slider = $this->slider ?? new Slider;
        $slider->fill($data)->save();

        foreach (['image', 'video'] as $key) {
            if (($old[$key] ?? null) && $old[$key] !== $slider->{$key}) {
                $uploader->delete($old[$key]);
            }
        }

        $this->image = $this->video = null;

        if (! $this->slider) {
            $this->flashToast(__('admin.common.created'));

            return $this->redirectRoute('admin.sliders.index');
        }

        $this->slider = $slider;
        $this->saved();
    }

    public function render()
    {
        return view('livewire.admin.sliders.form', [
            'linkOptions' => app(LinkResolver::class)->options(),
        ])->title($this->slider ? __('admin.sliders.edit') : __('admin.sliders.create'));
    }
}
