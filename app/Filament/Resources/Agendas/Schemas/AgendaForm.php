<?php

namespace App\Filament\Resources\Agendas\Schemas;

use App\Models\Profissional;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\User;
use Filament\Schemas\Components\Grid;

class AgendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informações do Profissional')
                ->description('Escolha o profissional e defina o tempo médio de cada atendimento.')
                ->schema([
                    Select::make('profissional_id')
                        ->label('Profissional')
                        ->options(Profissional::pluck('nome', 'id')->toArray())
                        ->searchable()
                        ->placeholder('Selecione o profissional responsável')
                        ->required()
                        ->hint('Escolha o profissional ao qual esta agenda pertence.'),

                    TextInput::make('tempo_medio')
                        ->label('Tempo médio por atendimento')
                        ->numeric()
                        ->suffix('min')
                        ->default(30)
                        ->helperText('Exemplo: 30 minutos por cliente.')
                        ->required(),
                ]),

            Section::make('Expediente Semanal')
                ->description('Defina os horários e dias de trabalho do profissional.')
                ->schema([
                    Flex::make([
                        TimePicker::make('inicio_expediente')
                            ->label('Início do expediente')
                            ->seconds(false)
                            ->required()
                            ->hint('Exemplo: 09:00'),
                        TimePicker::make('fim_expediente')
                            ->label('Fim do expediente')
                            ->seconds(false)
                            ->required()
                            ->hint('Exemplo: 18:00'),
                    ])->from('md')->gap(3),

                    Flex::make([
                        TimePicker::make('inicio_almoco')
                            ->label('Início do almoço')
                            ->seconds(false)
                            ->hint('Exemplo: 12:00'),
                        TimePicker::make('fim_almoco')
                            ->label('Fim do almoço')
                            ->seconds(false)
                            ->hint('Exemplo: 13:00'),
                    ])->from('md')->gap(3),
                ]),
            
            Section::make('Dias de Trabalho')
                ->description('Defina os dias de trabalho do profissional.')
                ->schema([
                    Grid::make()
                        ->columns([
                            '@md' => 3,
                            '@xl' => 4,
                        ])
                        ->schema([
                            Toggle::make('segunda')
                                ->label('Segunda-feira'),
                            Toggle::make('terca')
                                ->label('Terça-feira'),
                            Toggle::make('quarta')
                                ->label('Quarta-feira'),
                            Toggle::make('quinta')
                                ->label('Quinta-feira'),
                            Toggle::make('sexta')
                                ->label('Sexta-feira'),
                            Toggle::make('sabado')
                                ->label('Sábado'),
                            Toggle::make('domingo')
                                ->label('Domingo'),
                        ])
                ]),

             Section::make('Disponível')
                ->description('Defina o status da agenda.')
                ->schema([
                    Grid::make()
                        ->columns([
                            '@md' => 3,
                            '@xl' => 4,
                        ])
                        ->schema([
                            Toggle::make('status')
                                ->label('Disponível'),
                            
                        ])
                ]),

            
        ]);
    }
}
