<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithToast;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Profile extends Component
{
    use WithToast;

    public array $form = [];

    public array $password = ['current' => '', 'new' => '', 'new_confirmation' => ''];

    public function mount(): void
    {
        $user = auth()->user();
        $this->form = ['name' => $user->name, 'email' => $user->email, 'locale' => $user->locale];
    }

    public function save()
    {
        $this->validate([
            'form.name' => ['required', 'string', 'max:120'],
            'form.email' => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
            'form.locale' => ['required', Rule::in(array_keys(config('site.locales')))],
        ], [], ['form.name' => __('admin.fields.name'), 'form.email' => __('admin.fields.email')]);

        auth()->user()->update($this->form);
        session()->put('admin_locale', $this->form['locale']);
        $this->flashToast(__('admin.common.saved'));

        return $this->redirectRoute('admin.profile');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'password.current' => ['required', 'current_password'],
            'password.new' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [], ['password.current' => __('admin.profile.current_password'), 'password.new' => __('admin.profile.new_password')]);

        auth()->user()->update(['password' => $this->password['new']]);
        $this->password = ['current' => '', 'new' => '', 'new_confirmation' => ''];
        $this->toast(__('admin.profile.password_updated'));
    }

    public function render()
    {
        return view('livewire.admin.profile')->title(__('admin.nav.profile'));
    }
}
