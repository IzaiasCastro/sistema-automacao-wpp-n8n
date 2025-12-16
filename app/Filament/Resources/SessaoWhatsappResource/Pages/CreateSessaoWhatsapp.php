<?php

namespace App\Filament\Resources\SessaoWhatsappResource\Pages;

use App\Filament\Resources\SessaoWhatsappResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSessaoWhatsapp extends CreateRecord
{
    protected static string $resource = SessaoWhatsappResource::class;

    protected function getRedirectUrl(): string
    {
        // Redireciona para o index do resource
        return $this->getResource()::getUrl('index');
    }

}
