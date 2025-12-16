<?php

namespace App\Filament\Widgets;

use App\Models\Agendamento;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AtendimentosPorDiaChart extends ChartWidget
{
    protected static ?string $heading = 'Atendimentos por Dia';

 

    protected function getType(): string
    {
        return 'line';
    }

     protected function getData(): array
    {
        $dados = collect(range(6, 0))->map(function ($i) {
            $data = Carbon::now()->subDays($i)->format('Y-m-d');
            return [
                'data' => $data,
                'total' => Agendamento::whereDate('created_at', $data)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Atendimentos',
                    'data' => $dados->pluck('total'),
                    'borderColor' => '#3b82f6',
                    'fill' => false,
                ],
            ],
            'labels' => $dados->pluck('data'),
        ];
    }
}
