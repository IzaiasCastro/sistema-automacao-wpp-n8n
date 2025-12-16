<?php

namespace App\Filament\Resources\SessaoWhatsappResource\Pages;

use App\Filament\Resources\SessaoWhatsappResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSessaoWhatsapps extends ListRecords
{
    protected static string $resource = SessaoWhatsappResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
