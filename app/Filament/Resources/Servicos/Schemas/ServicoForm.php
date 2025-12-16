<?php

namespace App\Filament\Resources\Servicos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServicoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            
            // Seção: Informações do Serviço
            Section::make('Informações do Serviço')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome do Serviço')
                        ->placeholder('Ex: Corte de cabelo, Manicure, Barba')
                        ->required(),

                    TextInput::make('preco')
                        ->label('Preço')
                        ->placeholder('Ex: 50.00')
                        ->numeric()
                        ->required()
                        ->helperText('Informe o valor em reais, sem símbolo R$.'),
                ]),

            // Seção: Descrição e Detalhes
            Section::make('Descrição do Serviço')
                ->schema([
                    Textarea::make('descricao')
                        ->label('Descrição')
                        ->placeholder('Descreva o serviço detalhadamente para o cliente')
                        ->columnSpanFull()
                        ->rows(4)
                        ->required(),
                ]),

            // Seção: Imagem do Serviço
            Section::make('Imagem do Serviço')
                ->schema([
                    FileUpload::make('imagem')
                        ->label('Foto do Serviço')
                        ->image()
                        ->directory('servicos')
                        ->maxSize(2048) // 2MB
                        ->helperText('Envie uma imagem clara e representativa do serviço.')
                        ->nullable(),
                ]),
            // Seção: Imagem do Serviço
            Section::make('Disponibilidade do Serviço')
                ->schema([
                    Toggle::make('status')
                        ->label('Serviço Ativo')
                        ->default(true)
                        ->helperText('Ative ou desative a disponibilidade deste serviço para agendamentos.'),
                ]),
        ]);
    }
}
