<div>
    <x-admin.page-head :title="__('admin.nav.profile')" />
    <div class="row g-3">
        <div class="col-lg-6">
            <form class="a-card" wire:submit="save">
                <div class="a-card-head"><h2>{{ __('admin.profile.account') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.input model="form.name" :label="__('admin.fields.name')" required />
                    <x-admin.input model="form.email" type="email" :label="__('admin.fields.email')" dir="ltr" required />
                    <x-admin.select model="form.locale" :label="__('admin.users.locale')" :options="collect(locales())->map(fn ($l) => $l['name'])->all()" />
                </div>
                <div class="a-card-foot"><x-admin.save-button /></div>
            </form>
        </div>
        <div class="col-lg-6">
            <form class="a-card" wire:submit="updatePassword">
                <div class="a-card-head"><h2>{{ __('admin.profile.password') }}</h2></div>
                <div class="a-card-body">
                    <x-admin.input model="password.current" type="password" :label="__('admin.profile.current_password')" dir="ltr" autocomplete="current-password" />
                    <x-admin.input model="password.new" type="password" :label="__('admin.profile.new_password')" dir="ltr" autocomplete="new-password" :hint="__('admin.users.password_rule')" />
                    <x-admin.input model="password.new_confirmation" type="password" :label="__('admin.profile.confirm_password')" dir="ltr" autocomplete="new-password" />
                </div>
                <div class="a-card-foot"><x-admin.save-button target="updatePassword" :label="__('admin.profile.update_password')" /></div>
            </form>
        </div>
    </div>
</div>
