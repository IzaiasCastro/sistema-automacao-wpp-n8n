<?php

namespace App\Filament\Widgets;

use App\Models\Profissional;
use Filament\Widgets\Widget;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;

class RankingProfissionaisWidget extends Widget
{
    protected static string $view = 'filament.widgets.ranking-profissionais-widget';

     protected static ?string $heading = 'Ranking de Profissionais';

    protected function getTableQuery()
    {
        return Profissional::query()
            ->withCount('agendamentos')
            ->orderByDesc('agendamentos_count')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nome')->label('Profissional'),
            Tables\Columns\TextColumn::make('agendamentos_count')->label('Atendimentos'),
        ];
    }
}
