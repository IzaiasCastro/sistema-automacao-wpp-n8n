<?php

namespace App\Filament\Resources\Agendamentos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;

class AgendamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Seção: Dados do Agendamento
            Section::make('Dados do Agendamento')
                ->schema([
                    DatePicker::make('data')
                        ->label('Data')
                        ->required(),

                    TimePicker::make('horario')
                        ->label('Horário')
                        ->required(),

                    Textarea::make('observacao')
                        ->label('Observações')
                        ->placeholder('Informações adicionais sobre o atendimento')
                        ->rows(3)
                        ->columnSpanFull()
                        ->nullable(),
                ]),

            // Seção: Cliente
            Section::make('Cliente')
                ->schema([
                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::pluck('nome', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    

                   
                ]),

            // Seção: Profissional e Serviço
            Section::make('Profissional e Serviço')
                ->schema([
                    Select::make('profissional_id')
                        ->label('Profissional')
                        ->options(Profissional::pluck('nome', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    Select::make('servico_id')
                        ->label('Serviço')
                        ->options(Servico::pluck('nome', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    
                ]),

            // Seção: Pagamento e Status
            Section::make('Pagamento e Status')
                ->schema([
                    Select::make('status')
                        ->label('Status do Agendamento')
                        ->options([
                            'confirmado' => 'Confirmado',
                            'cancelado' => 'Cancelado',
                            'ausente' => 'Ausente',
                            'finalizado' => 'Finalizado',
                        ])
                        ->default('pendente')
                        ->required(),

                    Select::make('pagamento')
                        ->label('Status do Pagamento')
                        ->options([
                            'pendente' => 'Pendente',
                            'pago' => 'Pago',
                        ])
                        ->default('pendente')
                        ->required(),

                    TextInput::make('metodo_pagamento')
                        ->label('Método de Pagamento')
                        ->placeholder('Ex: Dinheiro, Pix, Cartão')
                        ->nullable(),
                ]),
        ]);
    }
}
