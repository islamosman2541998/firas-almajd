<?php

namespace App\Livewire\Admin;

use App\Models\CareerJob;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\JobApplication;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $days = collect(range(13, 0))->map(fn ($d) => now()->subDays($d)->toDateString());
        $since = now()->subDays(13)->startOfDay();

        $messagesPerDay = ContactMessage::query()->where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        $applicationsPerDay = JobApplication::query()->where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');

        $chart = $days->map(fn ($day) => [
            'label' => Carbon::parse($day)->format('d/m'),
            'messages' => (int) ($messagesPerDay[$day] ?? 0),
            'applications' => (int) ($applicationsPerDay[$day] ?? 0),
        ]);

        return view('livewire.admin.dashboard', [
            'stats' => [
                ['icon' => 'bi-envelope', 'label' => __('admin.nav.messages'), 'value' => ContactMessage::count(), 'hot' => ContactMessage::where('status', 'new')->count(), 'route' => 'admin.messages.index'],
                ['icon' => 'bi-person-lines-fill', 'label' => __('admin.nav.applications'), 'value' => JobApplication::count(), 'hot' => JobApplication::where('status', 'new')->count(), 'route' => 'admin.applications.index'],
                ['icon' => 'bi-bricks', 'label' => __('admin.nav.services'), 'value' => Service::count(), 'route' => 'admin.services.index'],
                ['icon' => 'bi-buildings', 'label' => __('admin.nav.projects'), 'value' => Project::count(), 'route' => 'admin.projects.index'],
                ['icon' => 'bi-images', 'label' => __('admin.nav.sliders'), 'value' => Slider::count(), 'route' => 'admin.sliders.index'],
                ['icon' => 'bi-grid-3x3-gap', 'label' => __('admin.nav.gallery'), 'value' => GalleryItem::count(), 'route' => 'admin.gallery.index'],
                ['icon' => 'bi-file-earmark-richtext', 'label' => __('admin.nav.pages'), 'value' => Page::count(), 'route' => 'admin.pages.index'],
                ['icon' => 'bi-people', 'label' => __('admin.nav.partners'), 'value' => Partner::count(), 'route' => 'admin.partners.index'],
            ],
            'chart' => $chart,
            'chartMax' => max(1, $chart->max(fn ($d) => max($d['messages'], $d['applications']))),
            'messages' => ContactMessage::query()->with('service')->latest()->limit(6)->get(),
            'applications' => JobApplication::query()->with('job')->latest()->limit(6)->get(),
            'openJobs' => CareerJob::query()->active()->count(),
        ])->title(__('admin.nav.dashboard'));
    }
}
