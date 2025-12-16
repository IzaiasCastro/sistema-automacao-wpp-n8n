<?php

namespace App\Filament\Resources\Agendamentos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgendamentoInfolist
{
   public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Cliente')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('cliente.nome')
                            ->label('Nome do Cliente'),
                        TextEntry::make('cliente.telefone')
                            ->label('Telefone')
                            ->placeholder('-'),
                        TextEntry::make('cliente,email')
                            ->label('Email')
                            ->placeholder('-'),
                        TextEntry::make('cliente_id')
                            ->label('ID do Cliente')
                            ->hidden(), // normalmente não precisa mostrar
                    ]),

                Section::make('Detalhes do Agendamento')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('data')
                            ->label('Data')
                            ->date(),
                        TextEntry::make('horario')
                            ->label('Horário')
                            ->time(),
                        TextEntry::make('observacao')
                            ->label('Observações')
                            ->placeholder('Nenhuma observação'),
                        TextEntry::make('status')
                            ->label('Status'),
                    ]),

                Section::make('Serviço e Profissional')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('servico.nome')
                            ->label('Serviço'),
                        TextEntry::make('profissional_id')
                            ->label('ID do Profissional')
                            ->hidden(),
                        TextEntry::make('servico_id')
                            ->label('ID do Serviço')
                            ->hidden(),
                        TextEntry::make('agenda_id')
                            ->label('ID da Agenda')
                            ->hidden(),
                    ]),

                Section::make('Pagamento')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('servico.preco')
                            ->label('Valor')
                            ->numeric()
                            ->prefix('R$'),
                        TextEntry::make('metodo_pagamento')
                            ->label('Método')
                            ->placeholder('-'),
                        TextEntry::make('pagamento')
                            ->label('Status do Pagamento')
                    ]),

                Section::make('Timestamps')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
