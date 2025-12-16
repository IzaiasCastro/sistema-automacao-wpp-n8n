<?php

namespace App\Providers;

use App\Models\Agendamento;
use App\Models\Profissional;
use App\Models\SessaoWhatsapp;
use App\Observers\AgendamentoObserver;
use App\Observers\ProfissionalObserver;
use App\Observers\SessaoWhatsappObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
         Agendamento::observe(AgendamentoObserver::class);
         SessaoWhatsapp::observe(SessaoWhatsappObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
