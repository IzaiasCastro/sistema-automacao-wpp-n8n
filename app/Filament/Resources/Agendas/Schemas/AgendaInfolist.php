<?php

namespace App\Filament\Resources\Agendas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgendaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('domingo')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('segunda')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('terca')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('quarta')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('quinta')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sexta')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sabado')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('profissional_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
