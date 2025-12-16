<?php

namespace App\Filament\Resources\ProfissionalResource\Pages;

use App\Filament\Resources\AgendaResource;
use App\Filament\Resources\ProfissionalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfissional extends CreateRecord
{
    protected static string $resource = ProfissionalResource::class;

        protected function afterCreate(): void
    {
        $this->redirect(AgendaResource::getUrl('index') . '?abrirAgenda=1&profissional=' . $this->record->id);
    }

}
