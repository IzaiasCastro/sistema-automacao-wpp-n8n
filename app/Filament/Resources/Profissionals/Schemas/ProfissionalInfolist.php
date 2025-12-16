<?php

namespace App\Filament\Resources\Profissionals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProfissionalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('telefone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('cpf')
                    ->placeholder('-'),
                TextEntry::make('cep')
                    ->placeholder('-'),
                TextEntry::make('logradouro')
                    ->placeholder('-'),
                TextEntry::make('bairro')
                    ->placeholder('-'),
                TextEntry::make('cidade')
                    ->placeholder('-'),
                TextEntry::make('estado')
                    ->placeholder('-'),
                TextEntry::make('complemento')
                    ->placeholder('-'),
                TextEntry::make('numero')
                    ->placeholder('-'),
                TextEntry::make('imagem')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
