<?php

namespace App\Filament\Resources\Profissionals\Pages;

use App\Filament\Resources\ProfissionalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfissionals extends ListRecords
{
    protected static string $resource = ProfissionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
