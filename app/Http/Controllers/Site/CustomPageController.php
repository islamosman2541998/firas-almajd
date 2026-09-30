<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\SectionService;
use App\Services\SeoManager;
use Illuminate\View\View;

class CustomPageController extends Controller
{
    public function __invoke(Page $page, SectionService $sections, SeoManager $seo): View
    {
        abort_unless($page->is_active, 404);

        $page->load(['seo', 'gallery']);

        $seo->forModel($page->seo, $page->title, $page->description, $page->image)
            ->set('type', 'article')
            ->breadcrumb($page->title, url()->current());

        return view('site.pages.custom', [
            'page' => $page,
            'labels' => $sections->data('page', 'gallery'),
        ]);
    }
}
