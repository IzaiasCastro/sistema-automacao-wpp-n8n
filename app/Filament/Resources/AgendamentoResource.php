<?php

namespace App\Filament\Resources;

use App\Enums\GuardNames;
use App\Filament\Resources\Agendamentos\Pages\CreateAgendamento;
use App\Filament\Resources\Agendamentos\Pages\EditAgendamento;
use App\Filament\Resources\Agendamentos\Pages\ListAgendamentos;
use App\Filament\Resources\Agendamentos\Pages\ViewAgendamento;
use App\Filament\Resources\Agendamentos\Pages\VisualizarAgendamento;
use App\Filament\Resources\Agendamentos\Schemas\AgendamentoForm;
use App\Filament\Resources\Agendamentos\Schemas\AgendamentoInfolist;
use App\Filament\Resources\Agendamentos\Tables\AgendamentosTable;
use App\Filament\Resources\Agendamentos\Pages\ViewAgendamentos;
use App\Models\Agendamento;
use ArchTech\Money\Money;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Filament\Tables\Actions\Action;
use Leandrocfe\FilamentPtbrFormFields\Money as FilamentPtbrFormFieldsMoney;

class AgendamentoResource extends Resource
{
    protected static ?string $model = Agendamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'Agendamento';

  public static function form(Form $schema): Form
{
    return $schema
        ->columns(2)
        ->schema([

            // =============================
            // 📅 Dados do agendamento
            // =============================
            \Filament\Forms\Components\Section::make('Dados do Agendamento')
                ->columns(2)
                ->schema([
                    \Filament\Forms\Components\DatePicker::make('data')
                        ->label('Data')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    \Filament\Forms\Components\TimePicker::make('horario')
                        ->label('Horário')
                        ->required()
                        ->seconds(false)
                        ->native(false),

                    \Filament\Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pendente' => 'Pendente',
                            'confirmado' => 'Confirmado',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default('pendente')
                        ->required()
                        ->native(false),
                ]),

            // =============================
            // 👤 Cliente
            // =============================
            \Filament\Forms\Components\Section::make('Cliente')
                ->schema([
                    \Filament\Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->relationship('cliente', 'nome')
                        ->searchable()
                        ->required()
                        ->native(false),
                ]),

            // =============================
            // ✂️ Serviço & Profissional
            // =============================
            \Filament\Forms\Components\Section::make('Serviço e Profissional')
                ->columns(2)
                ->schema([
                    \Filament\Forms\Components\Select::make('servicos')
                        ->label('Serviços')
                        ->relationship('servicos', 'nome')
                        ->required()
                        ->searchable()
                        ->multiple()
                        ->native(false),

                    \Filament\Forms\Components\Select::make('profissional_id')
                        ->label('Profissional')
                        ->relationship('profissional', 'nome')
                        ->required()
                        ->searchable()
                        ->native(false),

                        
                ]),

                   // =============================
            // 💰 Valores
            // =============================
            \Filament\Forms\Components\Section::make('Valor')
                ->schema([
                    FilamentPtbrFormFieldsMoney::make('valor')
                        ->label('Valor')
                        ->prefix('R$')
                        ->required(),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                // 🗓️ Data e horário
                TextColumn::make('data')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
                    
                TextColumn::make('horario')
                    ->label('Hora')
                    ->time('H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock'),

                // 👤 Cliente
                TextColumn::make('cliente.nome')
                ->label('Cliente')
                ->sortable()
                ->searchable(),


                // ✂️ Serviço e profissional
                TextColumn::make('servico.nome')
                ->label('Serviço')
                ->sortable()
                ->searchable(),

                TextColumn::make('profissional.nome')
                    ->label('Profissional')
                    ->icon('heroicon-o-user-circle')
                    ->toggleable(),

                // 💰 Valor e pagamento
                TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL', true)
                    ->alignEnd()
                    ->sortable(),


                // TextColumn::make('pagamento')
                //     ->badge()
                //     ->label('Pagamento')
                //     ->color(fn (string $state): string => match (strtolower($state)) {
                //         'pago', 'confirmado' => 'success',
                //         'pendente' => 'warning',
                //         'cancelado', 'falhou' => 'danger',
                //         default => 'gray',
                //     }),

                 TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match (strtolower($state)) {
                    'pendente' => 'warning',
                    'confirmado' => 'success',
                    'cancelado' => 'danger',
                    default => 'gray',
                })
                ->sortable(),
            ])

               ->actions([
            Action::make('change_status')
                ->label('Alterar Status')
                ->icon('heroicon-o-pencil')
                ->form([
                    Select::make('status')
                        ->label('Novo Status')
                        ->options([
                            'pendente' => 'Pendente',
                            'confirmado' => 'Confirmado',
                            'cancelado' => 'Cancelado',
                        ])
                        ->required(),
                ])
                ->modalHeading('Alterar Status do Agendamento')
                ->modalButton('Salvar')
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => $data['status'],
                    ]);
                }),
        ])

            ->defaultSort('data', 'desc')

            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->options(GuardNames::options()),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canDelete($record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true; // ❌ remove o botão "Criar"
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgendamentos::route('/'),
            'view' => ViewAgendamentos::route('/{record}'),
            'edit' => EditAgendamento::route('/{record}/edit'),
        ];
    }
}
