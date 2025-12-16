<?php

namespace App\Providers\Filament;
use Filament\Facades\Filament;

use App\Filament\Pages\CentralInteligente;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Tenancy\EditOrganizationProfile;
use App\Filament\Pages\Tenancy\RegisterOrganization;
use App\Models\Organization;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->darkMode(false)
            ->default()
            ->passwordReset()
            ->tenant(Organization::class)
            ->tenantRegistration(RegisterOrganization::class)
            ->tenantProfile(EditOrganizationProfile::class)
            ->id('admin')
            ->path('/')
             // ⭐️ O renderHook injeta nosso Livewire Component
            ->renderHook(
                 PanelsRenderHook::FOOTER,
                fn () => auth()->check()
                    ? Blade::render('@livewire("global-chat-widget")')
                    : '' // se não estiver logado, não renderiza nada
            )
            
             ->homeUrl(fn () => Dashboard::getUrl()) // 👈 define tua página como a inicial
            ->login()
            ->breadcrumbs(fn (): bool => ! request()->routeIs('filament.resources.agendas.*'))

            ->brandName('Zaptend')
            ->brandLogo(asset('logo-admin.png')) // Caminho da tua logo
             ->brandLogoHeight('6rem')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->plugins([
                FilamentFullCalendarPlugin::make()
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                 \App\Http\Middleware\ForceTreinamento::class, // <---- AQUI!
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        Livewire::component('global-chat-widget', \App\Livewire\GlobalChatWidget::class);

        Filament::serving(function () {

        if (!auth()->check()) {
            return;
        }

            // Garantir que é o painel admin
    if (Filament::getCurrentPanel()?->getId() !== 'admin') {
        return;
    }


        $user = \App\Models\User::find(auth()->user()->id); // auth()->user();

        // if (!$user->treinado && !$user->isSuperAdmin()) {
        //     Filament::registerRenderHook(
        //         PanelsRenderHook::STYLES_AFTER,
        //         fn () => '<style>
        //             .fi-sidebar, 
        //             .fi-sidebar-header, 
        //             .fi-sidebar-item, 
        //             .fi-main-sidebar {
        //                 display: none !important;
        //             }

        //             /* expandir a área principal já que sidebar sumiu */
        //             .fi-main { 
        //                 margin-left: 0 !important;
        //             }
        //         </style>'
        //     );
        // }

       
    });


       FilamentColor::register([
        'primary' => Color::hex('#25D366'),   // Verde WhatsApp
        'secondary' => Color::hex('#128C7E'), // Verde escuro
        // 'gray' => Color::hex('#e4bb59ff'),      // Fundo claro
        'success' => Color::hex('#25D366'),
        'danger' => Color::hex('#EF4444'),
        'info' => Color::hex('#34B7F1'),
        'warning' => Color::hex('#FFD700'),
         'neutral' => '#ECE5DD', 
    ]);
        FilamentAsset::register([
            Css::make('whatsapp-theme', resource_path('css/filament/whatsapp-theme.css')),
        ]);

    }

}
