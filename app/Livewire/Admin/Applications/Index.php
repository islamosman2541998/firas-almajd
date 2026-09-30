<?php

namespace App\Livewire\Admin\Applications;

use App\Livewire\Admin\Concerns\WithDataTable;
use App\Models\CareerJob;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public array $filters = ['job' => '', 'from' => '', 'to' => ''];

    public ?int $viewingId = null;

    protected array $sortable = ['created_at', 'name', 'experience'];

    public function mount(): void
    {
        if ($open = (int) request()->query('open')) {
            $this->view($open);
        }
    }

    protected function modelClass(): string
    {
        return JobApplication::class;
    }

    protected function baseQuery(): Builder
    {
        return JobApplication::query()->with('job')
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")->orWhere('summary', 'like', "%{$this->search}%")))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->filters['job'] ?? '', fn ($q, $job) => $q->where('career_job_id', $job))
            ->when($this->filters['from'] ?? '', fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($this->filters['to'] ?? '', fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest();
    }

    public function view(int $id): void
    {
        $application = JobApplication::findOrFail($id);
        if ($application->status === 'new') {
            $application->update(['status' => 'reviewed']);
        }
        $this->viewingId = $id;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    public function setStatus(int $id, string $status): void
    {
        abort_unless(in_array($status, JobApplication::STATUSES, true), 422);
        JobApplication::whereKey($id)->update(['status' => $status]);
        $this->toast(__('admin.common.updated'));
    }

    public function bulkStatus(string $status): void
    {
        abort_unless(in_array($status, JobApplication::STATUSES, true), 422);
        $count = JobApplication::whereKey($this->selected)->update(['status' => $status]);
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
            __('admin.applications.job') => fn ($r) => $r->job?->title,
            __('admin.applications.experience') => fn ($r) => $r->experience,
            __('admin.applications.summary') => fn ($r) => $r->summary,
            __('admin.common.status') => fn ($r) => __('admin.applications.statuses.'.$r->status),
            __('admin.common.language') => fn ($r) => strtoupper($r->locale),
            __('admin.common.created_at') => fn ($r) => $r->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.applications.index', [
            'rows' => $this->rows(),
            'jobs' => CareerJob::query()->ordered()->get(['id', 'title'])->pluck('title', 'id')->all(),
            'viewing' => $this->viewingId ? JobApplication::with('job')->find($this->viewingId) : null,
        ])->title(__('admin.nav.applications'));
    }
}
