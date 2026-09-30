<form wire:submit="save">
    <x-admin.page-head :title="$slider ? __('admin.sliders.edit') : __('admin.sliders.create')" :back="route('admin.sliders.index')">
        <x-slot:actions><x-admin.save-button /></x-slot:actions>
    </x-admin.page-head>

    <div class="a-layout">
        <div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.sliders.media') }}</h2></div>
                <div class="a-card-body">
                    <div class="a-field">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" id="mt-image" value="image" wire:model.live="form.media_type">
                            <label class="a-btn" for="mt-image"><i class="bi bi-image"></i> {{ __('admin.sliders.types.image') }}</label>
                            <input type="radio" class="btn-check" id="mt-video" value="video" wire:model.live="form.media_type">
                            <label class="a-btn" for="mt-video"><i class="bi bi-camera-video"></i> {{ __('admin.sliders.types.video') }}</label>
                        </div>
                    </div>
                    <div class="a-grid">
                        <x-admin.upload model="image" :file="$image" :current="$form['image']" :label="$form['media_type'] === 'video' ? __('admin.sliders.poster') : __('admin.fields.image')" :hint="__('admin.sliders.image_hint')" remove="removeImage" />
                        @if ($form['media_type'] === 'video')
                            <x-admin.upload model="video" kind="video" accept="video/mp4,video/webm" :file="$video" :current="$form['video']" :label="__('admin.sliders.video')" :hint="__('admin.sliders.video_hint')" remove="removeVideo" />
                        @endif
                    </div>
                </div>
            </div>

            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.sliders.texts') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.t-input model="form.title" :label="__('admin.fields.title')" live />
                    <x-admin.t-input model="form.description" :label="__('admin.fields.description')" textarea rows="2" live />
                </div>
            </div>

            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.sliders.button') }}</h2><x-admin.toggle model="form.show_button" :label="__('admin.sliders.show_button')" live /></div>
                @if ($form['show_button'])
                    <div class="a-card-body">
                        <x-admin.t-input model="form.button_text" :label="__('admin.sliders.button_text')" live />
                        <div class="a-grid">
                            <x-admin.link model="form.button_url" :label="__('admin.sliders.button_url')" :options="$linkOptions" />
                            <div class="pt-md-4"><x-admin.toggle model="form.button_new_tab" :label="__('admin.common.new_tab')" /></div>
                        </div>
                        <div class="a-grid-3">
                            <x-admin.color model="form.button_bg" :label="__('admin.sliders.button_bg')" nullable live />
                            <x-admin.color model="form.button_color" :label="__('admin.sliders.button_color')" nullable live />
                            <x-admin.color model="form.button_hover_bg" :label="__('admin.sliders.button_hover_bg')" nullable live />
                        </div>
                        <small class="a-hint">{{ __('admin.sliders.colors_hint') }}</small>
                    </div>
                @endif
            </div>
        </div>

        <aside class="a-layout-aside">
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.common.publish') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.toggle model="form.is_active" :label="__('admin.common.active')" />
                    <x-admin.range model="form.overlay_opacity" :label="__('admin.sliders.overlay')" live />
                    <x-admin.save-button class="w-100" />
                </div>
            </div>
            <div class="a-card">
                <div class="a-card-head"><h2>{{ __('admin.common.preview') }}</h2></div>
                @php($previewSrc = $image && method_exists($image, 'isPreviewable') && $image->isPreviewable() ? $image->temporaryUrl() : media_url($form['image']))
                <div class="a-preview-box" style="position:relative;aspect-ratio:16/10;background:#262a28;overflow:hidden;color:#fff" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
                    @if ($previewSrc)<img src="{{ $previewSrc }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif
                    <div style="position:absolute;inset:0;opacity:{{ ($form['overlay_opacity'] ?? 100) / 100 }};background:linear-gradient({{ is_rtl() ? '270deg' : '90deg' }},rgba(15,20,18,.87),rgba(15,20,18,.5) 45%,rgba(15,20,18,.05)),linear-gradient(0deg,rgba(15,20,18,.72),transparent 35%)"></div>
                    <div style="position:absolute;inset:0;padding:22px;display:flex;flex-direction:column;justify-content:center;gap:8px">
                        <strong style="font-size:17px;line-height:1.5">{{ tval($form['title']) }}</strong>
                        <span style="font-size:11px;opacity:.85;max-width:80%">{{ \Illuminate\Support\Str::limit(tval($form['description']), 90) }}</span>
                        @if ($form['show_button'] && tval($form['button_text']))
                            <span style="align-self:flex-start;margin-top:6px;padding:7px 12px;font-size:11px;font-weight:700;background:{{ $form['button_bg'] ?: '#c9a32d' }};color:{{ $form['button_color'] ?: '#231f20' }}">{{ tval($form['button_text']) }} ↗</span>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
    </div>
</form>
