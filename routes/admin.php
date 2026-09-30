<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LocaleController;
use App\Livewire\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard — /admin (prefix, "admin." names and middleware in bootstrap/app.php)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
});

Route::get('locale/{locale}', LocaleController::class)->name('locale');

Route::middleware(['auth', 'admin.active'])->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

    Route::livewire('/', Admin\Dashboard::class)->name('dashboard');

    // Content
    Route::livewire('sections/{page?}', Admin\Sections\Editor::class)->name('sections');
    Route::livewire('sliders', Admin\Sliders\Index::class)->name('sliders.index');
    Route::livewire('sliders/create', Admin\Sliders\Form::class)->name('sliders.create');
    Route::livewire('sliders/{slider:id}/edit', Admin\Sliders\Form::class)->name('sliders.edit');
    Route::livewire('services', Admin\Services\Index::class)->name('services.index');
    Route::livewire('services/create', Admin\Services\Form::class)->name('services.create');
    Route::livewire('services/{service:id}/edit', Admin\Services\Form::class)->name('services.edit');
    Route::livewire('pages', Admin\Pages\Index::class)->name('pages.index');
    Route::livewire('pages/create', Admin\Pages\Form::class)->name('pages.create');
    Route::livewire('pages/{page:id}/edit', Admin\Pages\Form::class)->name('pages.edit');
    Route::livewire('projects', Admin\Projects\Index::class)->name('projects.index');
    Route::livewire('gallery', Admin\Gallery\Index::class)->name('gallery.index');
    Route::livewire('certificates', Admin\Certificates\Index::class)->name('certificates.index');
    Route::livewire('partners', Admin\Partners\Index::class)->name('partners.index');
    Route::livewire('faqs', Admin\Faqs\Index::class)->name('faqs.index');
    Route::livewire('menu', Admin\Menu\Builder::class)->name('menu');

    // Careers & inbox
    Route::livewire('jobs', Admin\Jobs\Index::class)->name('jobs.index');
    Route::livewire('applications', Admin\Applications\Index::class)->name('applications.index');
    Route::livewire('messages', Admin\Messages\Index::class)->name('messages.index');
    Route::livewire('notifications', Admin\Notifications\Index::class)->name('notifications');

    // Settings
    Route::livewire('settings/general', Admin\Settings\General::class)->name('settings.general');
    Route::livewire('settings/seo', Admin\Settings\Seo::class)->name('settings.seo');
    Route::livewire('settings/pixels', Admin\Settings\Pixels::class)->name('settings.pixels');
    Route::livewire('settings/login', Admin\Settings\Login::class)->name('settings.login');
    Route::livewire('settings/dashboard', Admin\Settings\Dashboard::class)->name('settings.dashboard');
    Route::livewire('settings/theme', Admin\Settings\Theme::class)->name('settings.theme');
    Route::livewire('translations', Admin\Translations\Index::class)->name('translations');

    // System
    Route::livewire('users', Admin\Users\Index::class)->name('users.index');
    Route::livewire('profile', Admin\Profile::class)->name('profile');
});
