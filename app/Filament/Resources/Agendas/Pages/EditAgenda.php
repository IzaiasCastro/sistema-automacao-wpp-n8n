<?php

namespace App\Filament\Resources\Agendas\Pages;

use App\Filament\Resources\AgendaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAgenda extends EditRecord
{
    protected static string $resource = AgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

     public static function hasBreadcrumb(): bool
    {
        return false;
    }

    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

    //     foreach ($dias as $dia) {
    //         if (!empty($data[$dia])) {
    //             $info = json_decode($data[$dia], true);
    //             $data["{$dia}_inicio_expediente"] = $info['inicio_expediente'] ?? null;
    //             $data["{$dia}_fim_expediente"] = $info['fim_expediente'] ?? null;
    //             $data["{$dia}_inicio_almoco"] = $info['inicio_almoco'] ?? null;
    //             $data["{$dia}_fim_almoco"] = $info['fim_almoco'] ?? null;
    //             $data[$dia] = true;
    //         } else {
    //             $data[$dia] = false;
    //         }
    //     }

    //     return $data;
    // }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

    //     foreach ($dias as $dia) {
    //         if (!empty($data[$dia])) {
    //             $data[$dia] = json_encode([
    //                 'inicio_expediente' => $data["{$dia}_inicio_expediente"] ?? null,
    //                 'fim_expediente' => $data["{$dia}_fim_expediente"] ?? null,
    //                 'inicio_almoco' => $data["{$dia}_inicio_almoco"] ?? null,
    //                 'fim_almoco' => $data["{$dia}_fim_almoco"] ?? null,
    //             ]);
    //         } else {
    //             $data[$dia] = null;
    //         }

    //         unset(
    //             $data["{$dia}_inicio_expediente"],
    //             $data["{$dia}_fim_expediente"],
    //             $data["{$dia}_inicio_almoco"],
    //             $data["{$dia}_fim_almoco"]
    //         );
    //     }

    //     return $data;
    // }
}
