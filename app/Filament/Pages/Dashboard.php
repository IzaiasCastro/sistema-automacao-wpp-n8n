<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\TotalUsuariosWidget;
use App\Filament\Widgets\TotalAgendamentosWidget;
use App\Filament\Widgets\AtendimentosPorDiaChart;
use App\Filament\Widgets\MensagensIaWidget;
use App\Filament\Widgets\RankingProfissionaisWidget;
use App\Filament\Widgets\UltimosRegistrosWidget;
use App\Models\User;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = '';
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
         $widgets = [];

        $profissional = User::find(auth()->user()->id)->isProfissional();
        $propietario = User::find(auth()->user()->id)->isPropietario();

            if ($profissional || $propietario) {
                $widgets[] = \App\Filament\Widgets\CalendarWidget::class;
            }

        return $widgets;
    }
}
