<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Seção: Informações Pessoais
            Section::make('Informações Pessoais')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome completo')
                        ->placeholder('Digite o nome do cliente')
                        ->required(),

                    TextInput::make('cpf')
                        ->label('CPF')
                        ->placeholder('000.000.000-00')
                        // ->mask(fn ($mask) => $mask->pattern('000.000.000-00'))
                        ->required(),
                ]),

            // Seção: Contato
            Section::make('Contato')
                ->schema([
                    TextInput::make('telefone')
                        ->label('Telefone')
                        ->tel()
                        ->placeholder('(99) 99999-9999')
                        // ->mask(fn ($mask) => $mask->pattern('(00) 00000-0000'))
                        ->required(),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->placeholder('exemplo@dominio.com')
                        ->email()
                        ->required(),
                ]),

            // Seção: Endereço
            Section::make('Endereço')
                ->schema([
                    TextInput::make('cep')
                        ->label('CEP')
                        ->placeholder('00000-000')
                        // ->mask(fn ($mask) => $mask->pattern('00000-000'))
                        ->required(),

                    TextInput::make('logradouro')
                        ->label('Logradouro')
                        ->placeholder('Rua, Avenida, etc.'),

                    TextInput::make('numero')
                        ->label('Número')
                        ->placeholder('Número do endereço'),

                    TextInput::make('complemento')
                        ->label('Complemento')
                        ->placeholder('Apartamento, bloco, etc.')
                        ->nullable(),

                    TextInput::make('bairro')
                        ->label('Bairro'),

                    TextInput::make('cidade')
                        ->label('Cidade'),

                    TextInput::make('estado')
                        ->label('Estado')
                        ->placeholder('Ex: SP, RJ'),
                ]),

            
        ]);
    }
}
