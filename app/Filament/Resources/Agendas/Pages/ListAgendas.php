<?php

namespace App\Filament\Resources\Agendas\Pages;

use App\Filament\Resources\AgendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgendas extends ListRecords
{
    protected static string $resource = AgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        if (request()->get('abrirAgenda') == 1) {
            $this->dispatch('open-modal', id: 'criar-agenda');
        }
    }

}
