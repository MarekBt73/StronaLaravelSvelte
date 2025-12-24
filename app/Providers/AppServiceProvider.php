<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Article;
use App\Observers\ArticleObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\ImageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Article::observe(ArticleObserver::class);

        // Wymuszenie HTTPS w środowisku produkcyjnym
        // (dla hostingów z terminacją SSL na load balancerze)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
