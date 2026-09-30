<?php

namespace App\Livewire\Admin\Concerns;

trait WithToast
{
    /** Toast on the current page (handled by admin.js). */
    protected function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', type: $type, message: $message);
    }

    /** Toast shown after a redirect. */
    protected function flashToast(string $message, string $type = 'success'): void
    {
        session()->flash('toast', ['type' => $type, 'message' => $message]);
    }

    protected function saved(): void
    {
        $this->toast(__('admin.common.saved'));
    }
}
