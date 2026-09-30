<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

/**
 * Topbar bell. Polls quietly and raises a toast when new items arrive.
 */
class Bell extends Component
{
    public int $lastCount = -1;

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function open(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->redirect($notification->data['url'] ?? route('admin.notifications'));
    }

    public function render()
    {
        $user = auth()->user();
        $count = $user->unreadNotifications()->count();

        if ($this->lastCount >= 0 && $count > $this->lastCount) {
            $this->dispatch('toast', type: 'info', message: __('admin.notifications.new_arrived', ['count' => $count - $this->lastCount]));
        }
        $this->lastCount = $count;

        return view('livewire.admin.notifications.bell', [
            'count' => $count,
            'items' => $user->notifications()->latest()->limit(8)->get(),
            'interval' => max(10, (int) setting('dashboard.notifications_poll', 30)),
        ]);
    }
}
