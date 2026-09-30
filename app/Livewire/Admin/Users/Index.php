<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Livewire\Admin\Concerns\WithDrawerForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable, WithDrawerForm;

    protected array $sortable = ['name', 'email', 'last_login_at', 'created_at'];

    protected function modelClass(): string
    {
        return User::class;
    }

    protected function baseQuery(): Builder
    {
        return User::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")))
            ->tap(fn ($q) => $this->applyStatus($q))
            ->orderBy('name');
    }

    protected function formDefaults(): array
    {
        return ['name' => '', 'email' => '', 'password' => '', 'locale' => 'ar', 'is_active' => true];
    }

    protected function formFromModel(Model $record): array
    {
        return ['name' => $record->name, 'email' => $record->email, 'password' => '', 'locale' => $record->locale, 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:120'],
            'form.email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($this->editingId)],
            'form.password' => [$this->editingId ? 'nullable' : 'required', 'string', Password::min(8)->letters()->numbers()],
            'form.locale' => ['required', Rule::in(array_keys(config('site.locales')))],
            'form.is_active' => ['boolean'],
        ];
    }

    protected function formAttributes(): array
    {
        return ['form.name' => __('admin.fields.name'), 'form.email' => __('admin.fields.email'), 'form.password' => __('admin.fields.password')];
    }

    protected function persist(?Model $record): Model
    {
        $record ??= new User;
        $data = ['name' => $this->form['name'], 'email' => $this->form['email'], 'locale' => $this->form['locale'], 'is_active' => $this->form['is_active']];
        if (filled($this->form['password'])) {
            $data['password'] = $this->form['password'];
        }
        if ($record->is(auth()->user())) {
            $data['is_active'] = true;
        }
        $record->fill($data)->save();

        return $record;
    }

    protected function beforeDelete(Model $record): void
    {
        abort_if($record->is(auth()->user()), 403, __('admin.users.cannot_delete_self'));
    }

    public function toggleActive(int $id, string $column = 'is_active'): void
    {
        if ($id === auth()->id()) {
            $this->toast(__('admin.users.cannot_disable_self'), 'warning');

            return;
        }
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
        $this->toast(__('admin.common.updated'));
    }

    public function bulkDelete(): void
    {
        $this->selected = array_values(array_diff($this->selected, [(string) auth()->id(), auth()->id()]));
        $count = User::whereKey($this->selected)->delete();
        $this->selected = [];
        $this->toast(__('admin.common.bulk_deleted', ['count' => $count]));
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.name') => fn ($r) => $r->name,
            __('admin.fields.email') => fn ($r) => $r->email,
            __('admin.common.language') => fn ($r) => strtoupper($r->locale),
            __('admin.common.status') => fn ($r) => $this->yesNo($r->is_active),
            __('admin.users.last_login') => fn ($r) => $r->last_login_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.users.index', ['rows' => $this->rows()])->title(__('admin.nav.users'));
    }
}
