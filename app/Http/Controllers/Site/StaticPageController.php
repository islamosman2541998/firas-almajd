<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\SectionService;
use App\Services\SeoManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function __invoke(Request $request, SectionService $sections, SeoManager $seo): View
    {
        $page = $request->route()->defaults['page'];

        $seo->forPage($page);
        if ($page !== 'home') {
            $seo->breadcrumb(tval(config("sections.pages.{$page}.label")), url()->current());
        }

        return view('site.pages.static', [
            'page' => $page,
            'sections' => $sections->render($page),
        ]);
    }
}
