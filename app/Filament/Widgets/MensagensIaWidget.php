<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MensagensIaWidget extends Widget
{
    protected static string $view = 'filament.widgets.mensagens-ia-widget';

     protected function getStats(): array
    {
        return [
            // Stat::make('Mensagens da IA', MensagemIa::count())
            //     ->icon('heroicon-o-chat-bubble-left-right')
            //     ->description('Total de mensagens enviadas pela IA')
            //     ->color('info'),
        ];
    }
}
