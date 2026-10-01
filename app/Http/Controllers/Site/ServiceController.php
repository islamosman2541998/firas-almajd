<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\SectionService;
use App\Services\SeoManager;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(SectionService $sections, SeoManager $seo): View
    {
        $seo->forPage('services')->breadcrumb(tval(config('sections.pages.services.label')), url()->current());

        return view('site.pages.static', [
            'page' => 'services',
            'sections' => $sections->render('services'),
        ]);
    }

    public function show(Service $service, SectionService $sections, SeoManager $seo): View
    {
        abort_unless($service->is_active, 404);

        $service->load(['seo', 'gallery', 'related' => fn ($query) => $query->active()]);

        $seo->forModel($service->seo, $service->title, $service->short_description, $service->image)
            ->set('type', 'article')
            ->breadcrumb(tval(config('sections.pages.services.label')), lroute('services.index'))
            ->breadcrumb($service->title, url()->current())
            ->schema([
                '@type' => 'Service',
                'name' => $service->title,
                'description' => $service->short_description,
                'serviceType' => $service->title,
                'image' => media_url($service->image),
                'url' => url()->current(),
                'areaServed' => setting('seo.geo_placename') ?: null,
                'provider' => ['@type' => setting('seo.organization_type') ?: 'Organization', 'name' => setting_t('general.company_name'), 'url' => lroute('home')],
            ]);

        return view('site.pages.service', [
            'service' => $service,
            'sections' => $sections->render('service'),
        ]);
    }
}
