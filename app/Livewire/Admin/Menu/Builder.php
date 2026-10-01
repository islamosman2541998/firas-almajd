<?php

namespace App\Livewire\Admin\Menu;

use App\Livewire\Admin\Concerns\WithToast;
use App\Livewire\Admin\Concerns\WithTranslatableForm;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Service;
use App\Support\SiteCache;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * Header navigation: top-level links with optional dropdown children.
 * Children can be any link type, e.g. "Services" → selected services.
 */
class Builder extends Component
{
    use WithToast, WithTranslatableForm;

    public bool $formOpen = false;

    public ?int $editingId = null;

    public array $form = [];

    public ?int $quickParent = null;

    public array $quick = [];

    protected function defaults(?int $parentId = null): array
    {
        return ['title' => $this->blankT(), 'type' => 'route', 'route_name' => 'home', 'linkable_id' => '', 'url' => '', 'parent_id' => (string) ($parentId ?? ''), 'new_tab' => false, 'is_active' => true];
    }

    public function create(?int $parentId = null): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->form = $this->defaults($parentId);
        $this->formOpen = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $item = MenuItem::findOrFail($id);
        $this->editingId = $item->id;
        $this->form = [
            'title' => $this->translations($item, 'title'),
            'type' => $item->type,
            'route_name' => $item->route_name ?? 'home',
            'linkable_id' => (string) ($item->linkable_id ?? ''),
            'url' => (string) $item->url,
            'parent_id' => (string) ($item->parent_id ?? ''),
            'new_tab' => $item->new_tab,
            'is_active' => $item->is_active,
        ];
        $this->formOpen = true;
    }

    /** Switching the link type starts its target fresh so no stale value fails validation unseen. */
    public function updatedFormType(): void
    {
        $this->form['linkable_id'] = '';
        if (! array_key_exists($this->form['route_name'] ?? '', config('site.routes'))) {
            $this->form['route_name'] = 'home';
        }
        $this->resetValidation(['form.linkable_id', 'form.route_name', 'form.url']);
    }

    public function closeForm(): void
    {
        $this->formOpen = false;
        $this->quickParent = null;
        $this->resetValidation();
    }

    protected function rules(): array
    {
        $autoTitle = in_array($this->form['type'], ['page', 'service', 'route'], true);

        return [
            'form.title.ar' => [$autoTitle ? 'nullable' : 'required', 'string', 'max:80'],
            'form.title.en' => ['nullable', 'string', 'max:80'],
            'form.type' => ['required', Rule::in(MenuItem::TYPES)],
            'form.route_name' => $this->form['type'] === 'route' ? ['required', Rule::in(array_keys(config('site.routes')))] : ['nullable'],
            'form.linkable_id' => [Rule::requiredIf(in_array($this->form['type'], ['page', 'service'], true)), 'nullable', 'integer'],
            'form.url' => [Rule::requiredIf($this->form['type'] === 'url'), 'nullable', 'string', 'max:255'],
            'form.parent_id' => ['nullable', 'exists:menu_items,id', Rule::notIn([(string) $this->editingId])],
            'form.new_tab' => ['boolean'],
            'form.is_active' => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form.title.ar' => __('admin.fields.title').' ('.__('admin.common.lang_ar').')',
            'form.linkable_id' => __('admin.menu.target'),
            'form.url' => __('admin.common.url'),
        ];
    }

    public function save(): void
    {
        $this->validate();
        $type = $this->form['type'];

        $title = $this->cleanT($this->form['title']);
        if (empty($title['ar'])) {
            $title = match ($type) {
                'page' => Page::find($this->form['linkable_id'])?->getTranslations('title'),
                'service' => Service::find($this->form['linkable_id'])?->getTranslations('title'),
                'route' => config('sections.pages.'.config('site.routes.'.$this->form['route_name']).'.label'),
                default => $title,
            } ?? $title;
        }

        $item = $this->editingId ? MenuItem::findOrFail($this->editingId) : new MenuItem(['location' => 'header']);
        $parentId = $this->form['parent_id'] ?: null;

        // Keep a two-level tree: an item with children cannot become a child.
        if ($parentId && $item->exists && $item->children()->exists()) {
            $this->addError('form.parent_id', __('admin.menu.has_children'));

            return;
        }
        if ($parentId && MenuItem::whereKey($parentId)->whereNotNull('parent_id')->exists()) {
            $this->addError('form.parent_id', __('admin.menu.max_depth'));

            return;
        }

        $item->fill([
            'title' => $title,
            'type' => $type,
            'route_name' => $type === 'route' ? $this->form['route_name'] : null,
            'linkable_type' => in_array($type, ['page', 'service'], true) ? $type : null,
            'linkable_id' => in_array($type, ['page', 'service'], true) ? (int) $this->form['linkable_id'] : null,
            'url' => $type === 'url' ? trim($this->form['url']) : null,
            'parent_id' => $parentId,
            'new_tab' => $this->form['new_tab'],
            'is_active' => $this->form['is_active'],
        ]);

        if (! $item->exists || $item->isDirty('parent_id')) {
            $item->sort_order = (int) MenuItem::query()->where('parent_id', $parentId)->max('sort_order') + 1;
        }
        $item->save();

        $this->toast($this->editingId ? __('admin.common.updated') : __('admin.common.created'));
        $this->closeForm();
    }

    public function delete(int $id): void
    {
        MenuItem::findOrFail($id)->delete();
        $this->toast(__('admin.common.deleted'));
    }

    public function toggleActive(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);
    }

    public function reorder(int $id, int $position, $parentId = null): void
    {
        $parentId = $parentId ? (int) $parentId : null;
        $ids = MenuItem::query()->where('parent_id', $parentId)->ordered()->pluck('id')->reject(fn ($v) => $v === $id)->values()->all();
        array_splice($ids, $position, 0, [$id]);
        foreach ($ids as $i => $itemId) {
            MenuItem::query()->whereKey($itemId)->update(['sort_order' => $i + 1, 'parent_id' => $parentId]);
        }
        SiteCache::flush();
        $this->toast(__('admin.common.order_saved'));
    }

    /** Quick add: pick several services / pages as children of one item. */
    public function openQuick(int $parentId): void
    {
        $this->quickParent = $parentId;
        $this->quick = ['services' => [], 'pages' => []];
    }

    public function addQuick(): void
    {
        $parent = MenuItem::findOrFail($this->quickParent);
        $order = (int) $parent->children()->max('sort_order');

        foreach (['service' => Service::class, 'page' => Page::class] as $type => $model) {
            foreach ($model::query()->whereKey($this->quick[$type.'s'] ?? [])->ordered()->get() as $target) {
                MenuItem::create([
                    'location' => 'header', 'parent_id' => $parent->id, 'title' => $target->getTranslations('title'),
                    'type' => $type, 'linkable_type' => $type, 'linkable_id' => $target->id, 'is_active' => true, 'sort_order' => ++$order,
                ]);
            }
        }

        $this->toast(__('admin.common.created'));
        $this->closeForm();
    }

    public function render()
    {
        $items = MenuItem::query()->whereNull('parent_id')->where('location', 'header')->with('children')->ordered()->get();
        $services = Service::query()->ordered()->get(['id', 'title', 'is_active']);
        $pages = Page::query()->ordered()->get(['id', 'title', 'is_active']);

        $routes = collect(config('site.routes'))->mapWithKeys(fn ($page, $route) => [$route => tval(config("sections.pages.{$page}.label"))])->all();

        $existingChildren = $this->quickParent ? MenuItem::where('parent_id', $this->quickParent)->get(['linkable_type', 'linkable_id']) : collect();

        return view('livewire.admin.menu.builder', [
            'items' => $items,
            'routes' => $routes,
            'services' => $services,
            'pages' => $pages,
            'serviceTitles' => $services->pluck('title', 'id')->all(),
            'pageTitles' => $pages->pluck('title', 'id')->all(),
            'parents' => $items->when($this->editingId, fn ($c) => $c->where('id', '!=', $this->editingId))->mapWithKeys(fn ($i) => [$i->id => $i->title])->all(),
            'existingChildren' => $existingChildren,
        ])->title(__('admin.nav.menu'));
    }
}
