<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;

class UltimosRegistrosWidget extends Widget
{
    protected static string $view = 'filament.widgets.ultimos-registros-widget';

     protected static ?string $heading = 'Últimos Usuários Cadastrados';

    protected function getTableQuery()
    {
        return User::latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Nome'),
            Tables\Columns\TextColumn::make('email')->label('E-mail'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Criado em')
                ->dateTime('d/m/Y H:i'),
        ];
    }
}
