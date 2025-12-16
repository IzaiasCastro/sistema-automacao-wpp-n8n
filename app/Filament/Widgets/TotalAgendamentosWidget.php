<?php

namespace App\Filament\Widgets;

use App\Models\Agendamento;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;

class TotalAgendamentosWidget extends Widget
{
    protected static string $view = 'filament.widgets.total-agendamentos-widget';

     protected function getStats(): array
    {
        return [
            Stat::make('Agendamentos', Agendamento::count())
                ->icon('heroicon-o-calendar-days')
                ->description('Total de agendamentos realizados')
                ->color('success'),
        ];
    }
}
