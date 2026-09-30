<?php

namespace App\Providers;

use App\Services\SectionService;
use App\Services\SeoManager;
use App\Services\SettingsService;
use App\Services\SiteRepository;
use App\Support\DatabaseTranslationLoader;
use App\Support\LinkResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
        $this->app->singleton(SectionService::class);
        $this->app->singleton(SiteRepository::class);
        $this->app->singleton(LinkResolver::class);
        $this->app->scoped(SeoManager::class);

        $this->app->extend('translation.loader', fn ($loader) => new DatabaseTranslationLoader($loader));
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::shouldBeStrict(false);

        Relation::enforceMorphMap([
            'page' => \App\Models\Page::class,
            'service' => \App\Models\Service::class,
            'user' => \App\Models\User::class,
        ]);

        Paginator::useBootstrapFive();
    }
}
