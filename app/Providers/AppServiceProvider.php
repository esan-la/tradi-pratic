<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\LiveStatusComposer;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(MediaStorageService::class, function ($app) {
            return new MediaStorageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager le statut Live avec la navbar (toutes les vues)
        View::composer('layouts.app', LiveStatusComposer::class);
    }
}
