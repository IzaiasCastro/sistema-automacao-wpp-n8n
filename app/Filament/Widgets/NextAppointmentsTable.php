<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class NextAppointmentsTable extends BaseWidget
{
    protected static ?string $heading = 'Próximos Agendamentos';
    protected int | string | array $columnSpan = 'full'; // Ocupa a largura total

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return \App\Models\Agendamento::query()
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5); // Limita aos 5 próximos
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('client.name')->label('Cliente'),
            TextColumn::make('professional.name')->label('Profissional'),
            TextColumn::make('scheduled_at')->label('Data/Hora')
                ->dateTime('d/m/Y H:i'),
            TextColumn::make('service.name')->label('Serviço'),
        ];
    }
}