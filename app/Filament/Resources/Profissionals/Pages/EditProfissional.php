<?php

namespace App\Filament\Resources\Profissionals\Pages;

use App\Filament\Resources\ProfissionalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProfissional extends EditRecord
{
    protected static string $resource = ProfissionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
