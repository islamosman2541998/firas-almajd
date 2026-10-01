<div>
    <x-admin.page-head :title="__('admin.nav.menu')" :subtitle="__('admin.menu.subtitle')">
        <x-slot:actions>
            <a class="a-btn" href="{{ route('home', ['locale' => app()->getLocale()]) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.common.view_site') }}</a>
            <button type="button" class="a-btn a-btn-primary" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('admin.menu.create') }}</button>
        </x-slot:actions>
    </x-admin.page-head>

    <div class="a-layout">
        <div class="a-card">
            <div class="a-card-head"><div><h2>{{ __('admin.menu.header') }}</h2><p>{{ __('admin.menu.drag_hint') }}</p></div></div>
            <div class="a-card-body">
                @if ($items->isEmpty())
                    <x-admin.empty icon="bi-list-nested" />
                @endif
                <ul class="a-tree" wire:sort="reorder">
                    @foreach ($items as $item)
                        <li class="a-tree-item" wire:key="m-{{ $item->id }}" wire:sort:item="{{ $item->id }}">
                            @include('livewire.admin.menu.row', ['item' => $item, 'child' => false])
                            @if ($item->children->isNotEmpty())
                                <ul class="a-tree-children" wire:sort="reorder" wire:sort:group-id="{{ $item->id }}">
                                    @foreach ($item->children as $child)
                                        <li class="a-tree-item" wire:key="m-{{ $child->id }}" wire:sort:item="{{ $child->id }}">
                                            @include('livewire.admin.menu.row', ['item' => $child, 'child' => true])
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <aside class="a-layout-aside">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.menu.how_title') }}</h2></div>
                <div class="a-card-body small">
                    <p class="mb-2">{{ __('admin.menu.how_1') }}</p>
                    <p class="mb-2">{{ __('admin.menu.how_2') }}</p>
                    <p class="mb-0">{{ __('admin.menu.how_3') }}</p>
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.menu.header_button') }}</h2></div>
                <div class="a-card-body small">
                    <p class="mb-2">{{ __('admin.menu.header_button_hint') }}</p>
                    <a class="a-btn a-btn-sm" href="{{ route('admin.settings.general') }}#header">{{ __('admin.nav.settings_general') }}</a>
                </div>
            </div>
        </aside>
    </div>

    @if ($formOpen && ! $quickParent)
        <x-admin.drawer :title="$editingId ? __('admin.menu.edit') : __('admin.menu.create')">
            <div class="a-field">
                <label class="a-label">{{ __('admin.menu.type') }}</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach (\App\Models\MenuItem::TYPES as $type)
                        <label @class(['a-btn a-btn-sm', 'a-btn-primary' => $form['type'] === $type])><input type="radio" class="d-none" value="{{ $type }}" wire:model.live="form.type">{{ __('admin.menu.types.'.$type) }}</label>
                    @endforeach
                </div>
                <small class="a-hint">{{ __('admin.menu.type_hints.'.$form['type']) }}</small>
            </div>
            {{-- Keyed per type so Livewire builds a fresh field instead of morphing one select into another (which kept the old wire:model binding). --}}
            <div wire:key="menu-target-{{ $form['type'] }}">
            @if ($form['type'] === 'route')
                <x-admin.select model="form.route_name" :label="__('admin.menu.target')" :options="$routes" />
            @elseif ($form['type'] === 'service')
                <x-admin.select model="form.linkable_id" :label="__('admin.menu.target')" :options="$serviceTitles" :placeholder="__('admin.common.choose')" />
            @elseif ($form['type'] === 'page')
                <x-admin.select model="form.linkable_id" :label="__('admin.menu.target')" :options="$pageTitles" :placeholder="__('admin.common.choose')" />
            @elseif ($form['type'] === 'url')
                <x-admin.input model="form.url" :label="__('admin.common.url')" dir="ltr" placeholder="https://…  /  /path  /  #section" />
            @endif
            </div>
            <x-admin.t-input model="form.title" :label="__('admin.menu.label')" :hint="in_array($form['type'], ['route', 'page', 'service']) ? __('admin.menu.label_hint') : null" stacked />
            <x-admin.select model="form.parent_id" :label="__('admin.menu.parent')" :options="$parents" :placeholder="__('admin.menu.top_level')" :hint="__('admin.menu.parent_hint')" />
            <x-admin.toggle model="form.new_tab" :label="__('admin.common.new_tab')" />
            <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" wire:click="save" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif

    @if ($quickParent)
        @php($have = $existingChildren->map(fn ($c) => $c->linkable_type.':'.$c->linkable_id)->all())
        <x-admin.drawer :title="__('admin.menu.quick_title')">
            <p class="a-hint mt-0 mb-3">{{ __('admin.menu.quick_hint') }}</p>
            <label class="a-label">{{ __('admin.nav.services') }}</label>
            <div class="d-grid gap-2 mb-4">
                @foreach ($services as $service)
                    <label class="a-swatch" style="cursor:pointer">
                        <input type="checkbox" class="form-check-input m-0" value="{{ $service->id }}" wire:model="quick.services" @disabled(in_array('service:'.$service->id, $have))>
                        <span class="flex-grow-1">{{ $service->title }}</span>
                        @if (in_array('service:'.$service->id, $have))<span class="a-badge is-success">{{ __('admin.menu.already') }}</span>@endif
                    </label>
                @endforeach
            </div>
            @if ($pages->isNotEmpty())
                <label class="a-label">{{ __('admin.nav.pages') }}</label>
                <div class="d-grid gap-2">
                    @foreach ($pages as $page)
                        <label class="a-swatch" style="cursor:pointer">
                            <input type="checkbox" class="form-check-input m-0" value="{{ $page->id }}" wire:model="quick.pages" @disabled(in_array('page:'.$page->id, $have))>
                            <span class="flex-grow-1">{{ $page->title }}</span>
                            @if (in_array('page:'.$page->id, $have))<span class="a-badge is-success">{{ __('admin.menu.already') }}</span>@endif
                        </label>
                    @endforeach
                </div>
            @endif
            <x-slot:footer>
                <button type="button" class="a-btn" wire:click="closeForm">{{ __('admin.common.cancel') }}</button>
                <x-admin.save-button type="button" target="addQuick" wire:click="addQuick" :label="__('admin.menu.add_selected')" />
            </x-slot:footer>
        </x-admin.drawer>
    @endif
</div>
