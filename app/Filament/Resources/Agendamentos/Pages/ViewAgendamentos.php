<?php

namespace App\Filament\Resources\Agendamentos\Pages;

use App\Filament\Resources\AgendamentoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAgendamentos extends ViewRecord
{
    protected static string $resource = AgendamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
