<div>
    <x-admin.page-head :title="__('admin.nav.sections')" :subtitle="__('admin.sections.subtitle')">
        <x-slot:actions>
            @if ($pageUrl)<a class="a-btn" href="{{ $pageUrl }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin.sections.view_page') }}</a>@endif
        </x-slot:actions>
    </x-admin.page-head>

    <div class="row g-3">
        <div class="col-xl-3 col-lg-4">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.sections.pages') }}</h2></div>
                <nav class="a-sidelist">
                    @foreach ($pages as $key => $info)
                        <a href="{{ route('admin.sections', ['page' => $key]) }}" @class(['is-active' => $page === $key])>
                            {{ tval($info['label']) }}
                            <small>{{ count($info['sections']) }}</small>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="row g-3">
                <div class="col-xxl-4">
                    <div class="a-card">
                        <div class="a-card-head"><div><h2>{{ tval($pages[$page]['label']) }}</h2><p>{{ __('admin.sections.order_hint') }}</p></div></div>
                        <div wire:sort="reorderSections">
                            @foreach ($layout as $row)
                                @php($type = config('sections.types.'.$instances[$row['key']]['type']))
                                <div @class(['a-section-row', 'is-active' => $section === $row['key'], 'is-off' => ! $row['enabled']]) wire:key="s-{{ $row['key'] }}" wire:sort:item="{{ $row['key'] }}" wire:click="selectSection('{{ $row['key'] }}')">
                                    <i class="bi bi-grip-vertical a-drag" wire:sort:handle></i>
                                    <span class="a-section-name">{{ tval($type['label']) }}<small>{{ $row['key'] }}</small></span>
                                    <div class="form-check form-switch m-0" wire:click.stop>
                                        <input class="form-check-input" type="checkbox" role="switch" @checked($row['enabled']) wire:click="toggleSection('{{ $row['key'] }}')" title="{{ __('admin.sections.visible') }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($hasSeo)
                            <div @class(['a-section-row', 'is-active' => $section === 'seo']) wire:click="selectSection('seo')" style="border-top:1px solid var(--a-border)">
                                <i class="bi bi-search text-muted"></i>
                                <span class="a-section-name">{{ __('admin.sections.page_seo') }}<small>title · description · og</small></span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xxl-8">
                    @if ($section === 'seo' && $hasSeo)
                        <form class="a-card" wire:submit="saveSeo">
                            <div class="a-card-head"><div><h2>{{ __('admin.sections.page_seo') }}</h2><p>{{ tval($pages[$page]['label']) }}</p></div><x-admin.save-button target="saveSeo" /></div>
                            <div class="a-card-body">
                                <x-admin.t-input model="seo.title" :label="__('admin.seo.meta_title')" :hint="__('admin.seo.meta_title_hint')" max="120" />
                                <x-admin.t-input model="seo.description" :label="__('admin.seo.meta_description')" textarea rows="3" max="320" />
                                <x-admin.t-input model="seo.keywords" :label="__('admin.seo.meta_keywords')" :hint="__('admin.seo.keywords_hint')" />
                                <x-admin.t-input model="seo.heading" :label="__('admin.sections.h1')" :hint="__('admin.sections.h1_hint')" />
                                <div class="a-grid">
                                    <x-admin.upload model="seoImage" :file="$seoImage" :current="$seo['og_image'] ?: null" :label="__('admin.seo.og_image')" :hint="__('admin.seo.og_image_hint')" remove="removeSeoImage" />
                                    <x-admin.toggle model="seo.noindex" :label="__('admin.seo.noindex')" :hint="__('admin.seo.noindex_hint')" />
                                </div>
                            </div>
                        </form>
                    @elseif ($typeConfig)
                        <form class="a-card" wire:submit="saveSection">
                            <div class="a-card-head">
                                <div><h2>{{ tval($typeConfig['label']) }}</h2><p>{{ tval($pages[$page]['label']) }} · <span class="ltr">{{ $section }}</span></p></div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="a-btn a-btn-ghost a-btn-sm" x-on:click="confirmAction(() => $wire.resetSection(), {text: @js(__('admin.sections.reset_confirm')), icon: 'question'})"><i class="bi bi-arrow-counterclockwise"></i> {{ __('admin.sections.reset') }}</button>
                                    <x-admin.save-button target="saveSection" />
                                </div>
                            </div>
                            <div class="a-card-body">
                                @if (! empty($typeConfig['note']))
                                    <div class="a-note mb-3"><i class="bi bi-info-circle"></i><span>{{ tval($typeConfig['note']) }}</span></div>
                                @endif
                                @if (! collect($layout)->firstWhere('key', $section)['enabled'])
                                    <div class="a-note mb-3" style="border-color:var(--a-warning)"><i class="bi bi-eye-slash" style="color:var(--a-warning)"></i><span>{{ __('admin.sections.hidden_note') }}</span></div>
                                @endif
                                @forelse ($fields as $name => $field)
                                    @include('livewire.admin.sections.field', ['name' => $name, 'field' => $field, 'model' => 'data.'.$name])
                                @empty
                                    <p class="text-muted mb-0">{{ __('admin.sections.no_fields') }}</p>
                                @endforelse
                            </div>
                            @if ($fields)
                                <div class="a-card-foot"><x-admin.save-button target="saveSection" /></div>
                            @endif
                        </form>
                    @else
                        <div class="a-card"><x-admin.empty icon="bi-layout-text-window" :text="__('admin.sections.choose')" /></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
