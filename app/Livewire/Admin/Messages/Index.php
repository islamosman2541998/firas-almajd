<?php

namespace App\Livewire\Admin\Messages;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Models\ContactMessage;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public array $filters = ['service' => '', 'from' => '', 'to' => ''];

    public ?int $viewingId = null;

    protected array $sortable = ['created_at', 'name'];

    public function mount(): void
    {
        if ($open = (int) request()->query('open')) {
            $this->view($open);
        }
    }

    protected function modelClass(): string
    {
        return ContactMessage::class;
    }

    protected function baseQuery(): Builder
    {
        return ContactMessage::query()->with('service')
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")->orWhere('message', 'like', "%{$this->search}%")))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->filters['service'] ?? '', fn ($q, $service) => $q->where('service_id', $service))
            ->when($this->filters['from'] ?? '', fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($this->filters['to'] ?? '', fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest();
    }

    public function view(int $id): void
    {
        $message = ContactMessage::findOrFail($id);
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }
        $this->viewingId = $id;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    public function setStatus(int $id, string $status): void
    {
        abort_unless(in_array($status, ContactMessage::STATUSES, true), 422);
        ContactMessage::whereKey($id)->update(['status' => $status]);
        $this->toast(__('admin.common.updated'));
    }

    public function bulkStatus(string $status): void
    {
        abort_unless(in_array($status, ContactMessage::STATUSES, true), 422);
        $count = ContactMessage::whereKey($this->selected)->update(['status' => $status]);
        $this->selected = [];
        $this->toast(__('admin.common.bulk_updated', ['count' => $count]));
    }

    protected function exportColumns(): array
    {
        return [
            '#' => fn ($r) => $r->id,
            __('admin.fields.name') => fn ($r) => $r->name,
            __('admin.fields.phone') => fn ($r) => $r->phone,
            __('admin.fields.email') => fn ($r) => $r->email,
            __('admin.messages.service') => fn ($r) => $r->service?->title,
            __('admin.messages.message') => fn ($r) => $r->message,
            __('admin.common.status') => fn ($r) => __('admin.messages.statuses.'.$r->status),
            __('admin.common.language') => fn ($r) => strtoupper($r->locale),
            'IP' => fn ($r) => $r->ip,
            __('admin.common.created_at') => fn ($r) => $r->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.messages.index', [
            'rows' => $this->rows(),
            'services' => Service::query()->ordered()->get(['id', 'title'])->pluck('title', 'id')->all(),
            'viewing' => $this->viewingId ? ContactMessage::with('service')->find($this->viewingId) : null,
        ])->title(__('admin.nav.messages'));
    }
}
