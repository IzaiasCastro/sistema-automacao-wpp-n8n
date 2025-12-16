<?php

namespace App\Filament\Resources\Profissionals\Pages;

use App\Filament\Resources\ProfissionalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProfissional extends ViewRecord
{
    protected static string $resource = ProfissionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
