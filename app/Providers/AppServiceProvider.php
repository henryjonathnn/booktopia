<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // In production, Laravel will automatically cache views.
        if (!app()->isLocal()) {
            // You can add any other custom caching logic you need here,
            // but there is no need to call View::cache()
        }
    }
}
