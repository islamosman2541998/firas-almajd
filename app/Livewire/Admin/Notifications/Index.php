<?php

namespace App\Livewire\Admin\Notifications;

use App\Livewire\Admin\Concerns\WithToast;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithToast;

    #[Url(except: '')]
    public string $filter = '';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function markRead(string $id): void
    {
        auth()->user()->notifications()->whereKey($id)->first()?->markAsRead();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->toast(__('admin.notifications.all_read'));
    }

    public function delete(string $id): void
    {
        auth()->user()->notifications()->whereKey($id)->delete();
        $this->toast(__('admin.common.deleted'));
    }

    public function clearRead(): void
    {
        auth()->user()->readNotifications()->delete();
        $this->toast(__('admin.common.deleted'));
    }

    public function render()
    {
        $query = auth()->user()->notifications()->latest();
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif (in_array($this->filter, ['message', 'application'], true)) {
            $query->where('data->kind', $this->filter);
        }

        return view('livewire.admin.notifications.index', ['items' => $query->paginate(admin_per_page())])
            ->title(__('admin.nav.notifications'));
    }
}
