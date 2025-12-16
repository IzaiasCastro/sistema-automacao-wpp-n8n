<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use Filament\Widgets\Widget;

class ClientePointsWidget extends Widget
{
    protected static string $view = 'filament.widgets.cliente-points-widget';

    protected function getViewData(): array
    {
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        if(!$cliente) {
            return [
                'points' => 0,
            ];
        }
        return [
            'points' => $cliente->total_points,
        ];
    }
}
