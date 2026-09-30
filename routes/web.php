<?php

use App\Http\Controllers\Site\CustomPageController;
use App\Http\Controllers\Site\RootController;
use App\Http\Controllers\Site\SeoFilesController;
use App\Http\Controllers\Site\ServiceController;
use App\Http\Controllers\Site\StaticPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website — every page lives under /{locale} (ar | en)
|--------------------------------------------------------------------------
*/

Route::get('/', RootController::class)->name('root');
Route::get('sitemap.xml', [SeoFilesController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoFilesController::class, 'robots'])->name('robots');

Route::prefix('{locale}')
    ->where(['locale' => implode('|', array_keys(config('site.locales')))])
    ->middleware('site.locale')
    ->group(function () {
        Route::get('/', StaticPageController::class)->defaults('page', 'home')->name('home');
        Route::get('about', StaticPageController::class)->defaults('page', 'about')->name('about');
        Route::get('projects', StaticPageController::class)->defaults('page', 'projects')->name('projects');
        Route::get('gallery', StaticPageController::class)->defaults('page', 'gallery')->name('gallery');
        Route::get('certificates', StaticPageController::class)->defaults('page', 'certificates')->name('certificates');
        Route::get('careers', StaticPageController::class)->defaults('page', 'careers')->name('careers');
        Route::get('approach', StaticPageController::class)->defaults('page', 'approach')->name('approach');
        Route::get('contact', StaticPageController::class)->defaults('page', 'contact')->name('contact');

        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('p/{page:slug}', CustomPageController::class)->name('pages.show');
    });
