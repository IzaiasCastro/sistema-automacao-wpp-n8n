<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PwaViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->getFactory()->startPush('head', view('pwa.head'));
            $view->getFactory()->startPush('scripts', view('pwa.script'));
        });
    }
}
