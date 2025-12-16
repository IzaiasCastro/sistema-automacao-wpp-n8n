<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\Organization;
use App\Models\Profissional;
use App\Observers\ClienteObserver;
use App\Observers\OrganizationObserver;
use App\Observers\ProfissionalObserver;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
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
    public function boot(): void
    {
        Cliente::observe(ClienteObserver::class);
        Profissional::observe(ProfissionalObserver::class);
        Organization::observe(OrganizationObserver::class);

         FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_START,
            fn (): string => Blade::render('@PwaHead'),
        );
         FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_END,
            fn (): string => Blade::render('@RegisterServiceWorkerScript'),
        );
    }
}
