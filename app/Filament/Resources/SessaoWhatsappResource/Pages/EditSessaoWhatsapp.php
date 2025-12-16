<?php

namespace App\Filament\Resources\SessaoWhatsappResource\Pages;

use App\Filament\Resources\SessaoWhatsappResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSessaoWhatsapp extends EditRecord
{
    protected static string $resource = SessaoWhatsappResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
