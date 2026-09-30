<?php

namespace App\Livewire\Admin\Sections;

use App\Livewire\Admin\Concerns\WithListItems;
use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Services\MediaUploader;
use App\Services\SectionService;
use App\Support\LinkResolver;
use Illuminate\Support\Arr;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Everything inside the static pages: section visibility & order, every
 * text (ar/en), images and links — generated from config/sections.php.
 */
class Editor extends Component
{
    use WithFileUploads, WithListItems, WithToast, WithTranslatableForm;

    public string $page = 'home';

    #[Url(except: '')]
    public string $section = '';

    public array $layout = [];

    public array $data = [];

    public array $uploads = [];

    public array $seo = [];

    public $seoImage = null;

    public function mount(?string $page = null): void
    {
        $pages = config('sections.pages');
        $this->page = array_key_exists((string) $page, $pages) ? $page : 'home';
        $this->loadPage();

        $keys = array_column($this->layout, 'key');
        if ($this->section !== 'seo' && ! in_array($this->section, $keys, true)) {
            $this->section = $keys[0] ?? '';
        }
        $this->loadSection();
    }

    protected function sections(): SectionService
    {
        return app(SectionService::class);
    }

    protected function hasSeo(): bool
    {
        return array_key_exists($this->page, config('settings.seo.pages'));
    }

    protected function loadPage(): void
    {
        $this->layout = $this->sections()->layout($this->page);

        if ($this->hasSeo()) {
            $page = (array) setting("seo.pages.{$this->page}", []);
            $this->seo = [
                'title' => $this->tValue($page['title'] ?? null),
                'description' => $this->tValue($page['description'] ?? null),
                'keywords' => $this->tValue($page['keywords'] ?? null),
                'heading' => $this->tValue($page['heading'] ?? null),
                'og_image' => $page['og_image'] ?? '',
                'noindex' => (bool) ($page['noindex'] ?? false),
            ];
        }
    }

    protected function loadSection(): void
    {
        $this->resetValidation();
        $this->uploads = [];
        if ($this->section === '' || $this->section === 'seo') {
            $this->data = [];

            return;
        }

        $data = $this->sections()->data($this->page, $this->section);
        foreach ($this->fields() as $name => $field) {
            $data[$name] = $this->normalize($field, $data[$name] ?? null);
        }
        $this->data = $data;
    }

    protected function normalize(array $field, mixed $value): mixed
    {
        return match (true) {
            $field['type'] === 'repeater' => collect((array) $value)->map(function ($row) use ($field) {
                $clean = [];
                foreach ($field['fields'] as $sub => $subField) {
                    $clean[$sub] = $this->normalize($subField, $row[$sub] ?? null);
                }

                return $clean;
            })->values()->all(),
            ! empty($field['t']) => $this->tValue($value),
            $field['type'] === 'toggle' => (bool) $value,
            default => $value,
        };
    }

    protected function fields(): array
    {
        if ($this->section === '' || $this->section === 'seo') {
            return [];
        }
        $type = $this->sections()->instances($this->page)[$this->section]['type'];

        return $this->sections()->type($type)['fields'];
    }

    public function selectPage(string $page): void
    {
        $this->redirectRoute('admin.sections', ['page' => $page], navigate: false);
    }

    public function selectSection(string $key): void
    {
        $this->section = $key;
        $this->loadSection();
    }

    public function toggleSection(string $key): void
    {
        foreach ($this->layout as &$row) {
            if ($row['key'] === $key) {
                $row['enabled'] = ! $row['enabled'];
            }
        }
        $this->sections()->saveLayout($this->page, $this->layout);
        $this->toast(__('admin.common.updated'));
    }

    public function reorderSections(string $key, int $position): void
    {
        $rows = collect($this->layout)->keyBy('key');
        $moving = $rows->pull($key);
        $ordered = $rows->values()->all();
        array_splice($ordered, $position, 0, [$moving]);
        $this->layout = $ordered;
        $this->sections()->saveLayout($this->page, $this->layout);
        $this->toast(__('admin.common.order_saved'));
    }

    public function addRow(string $field): void
    {
        $row = [];
        foreach ($this->fields()[$field]['fields'] ?? [] as $sub => $subField) {
            $row[$sub] = ! empty($subField['t']) ? $this->blankT() : '';
        }
        $this->addItem("data.{$field}", $row);
    }

    protected function sectionRules(): array
    {
        $rules = [];
        foreach ($this->fields() as $name => $field) {
            $key = "data.{$name}";
            $rules += match ($field['type']) {
                'repeater' => collect($field['fields'])->mapWithKeys(fn ($sub, $subName) => ["{$key}.*.{$subName}.*" => ['nullable', 'string', 'max:2000']])->all(),
                'image' => ["uploads.{$name}" => ['nullable', 'image', 'max:'.config('site.uploads.image_max_kb')]],
                'number' => [$key => ['nullable', 'integer', 'min:0', 'max:100000']],
                'toggle' => [$key => ['boolean']],
                'select' => [$key => ['required', 'in:'.implode(',', array_keys($field['options']))]],
                'color' => [$key => ['nullable', 'hex_color']],
                default => ! empty($field['t'])
                    ? ["{$key}.*" => ['nullable', 'string', 'max:2000']]
                    : [$key => ['nullable', 'string', 'max:500']],
            };
        }

        return $rules;
    }

    public function saveSection(): void
    {
        $this->validate($this->sectionRules());
        $uploader = app(MediaUploader::class);

        foreach ($this->fields() as $name => $field) {
            if ($field['type'] === 'image' && ($this->uploads[$name] ?? null)) {
                $this->data[$name] = $uploader->replace($this->data[$name] ?? null, $this->uploads[$name], 'sections', true, 2000);
            }
            if ($field['type'] === 'repeater') {
                $this->data[$name] = array_values($this->data[$name] ?? []);
            }
            if ($field['type'] === 'number') {
                $this->data[$name] = (int) ($this->data[$name] ?? 0);
            }
        }

        $this->uploads = [];
        $this->sections()->save($this->page, $this->section, Arr::only($this->data, array_keys($this->fields())));
        $this->saved();
    }

    public function resetSection(): void
    {
        $this->sections()->reset($this->page, $this->section);
        $this->loadSection();
        $this->toast(__('admin.sections.reset_done'));
    }

    public function saveSeo(): void
    {
        $this->validate([
            'seo.title.*' => ['nullable', 'string', 'max:120'],
            'seo.description.*' => ['nullable', 'string', 'max:320'],
            'seo.keywords.*' => ['nullable', 'string', 'max:255'],
            'seo.heading.*' => ['nullable', 'string', 'max:180'],
            'seo.noindex' => ['boolean'],
            'seoImage' => ['nullable', 'image', 'max:'.config('site.uploads.image_max_kb')],
        ]);

        if ($this->seoImage) {
            $this->seo['og_image'] = app(MediaUploader::class)->replace($this->seo['og_image'] ?: null, $this->seoImage, 'seo', true, 1200);
            $this->seoImage = null;
        }

        $stored = settings()->stored('seo') ?? [];
        $stored['pages'][$this->page] = $this->seo;
        settings()->put('seo', $stored);
        $this->saved();
    }

    public function removeSeoImage(): void
    {
        $this->seo['og_image'] = '';
    }

    public function render()
    {
        $instances = $this->sections()->instances($this->page);
        $pageConfig = config("sections.pages.{$this->page}");

        return view('livewire.admin.sections.editor', [
            'pages' => config('sections.pages'),
            'instances' => $instances,
            'fields' => $this->fields(),
            'typeConfig' => $this->section && $this->section !== 'seo' ? $this->sections()->type($instances[$this->section]['type']) : null,
            'hasSeo' => $this->hasSeo(),
            'linkOptions' => app(LinkResolver::class)->options(),
            'pageUrl' => $pageConfig['route'] ? route($pageConfig['route'], ['locale' => app()->getLocale()]) : null,
        ])->title(__('admin.nav.sections'));
    }
}
