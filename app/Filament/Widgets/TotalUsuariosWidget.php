<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalUsuariosWidget extends Widget
{
    protected static string $view = 'filament.widgets.total-usuarios-widget';

     protected function getStats(): array
    {
        return [
            Stat::make('Total de Usuários', User::count())
                ->icon('heroicon-o-user-group')
                ->description('Usuários cadastrados')
                ->color('primary'),
        ];
    }
}
