<?php

namespace App\Filament\Resources;

use App\Enums\GuardNames;
use App\Filament\Resources\Agendamentos\Pages\CreateAgendamento;
use App\Filament\Resources\Agendamentos\Pages\EditAgendamento;
use App\Filament\Resources\Agendamentos\Pages\ListAgendamentos;
use App\Filament\Resources\Agendamentos\Pages\ViewAgendamento;
use App\Filament\Resources\Agendamentos\Schemas\AgendamentoForm;
use App\Filament\Resources\Agendamentos\Schemas\AgendamentoInfolist;
use App\Filament\Resources\Agendamentos\Tables\AgendamentosTable;
use App\Filament\Resources\Agendas\Pages\EditAgenda;
use App\Filament\Resources\Agendas\Pages\ListAgendas;
use App\Filament\Resources\Agendas\Pages\ViewAgenda;
use App\Filament\Resources\Clientes\Pages\EditCliente;
use App\Filament\Resources\Clientes\Pages\ListClientes;
use App\Filament\Resources\Clientes\Pages\ViewCliente;
use App\Models\Cliente;
use App\Models\Agendamento;
use App\Models\Profissional;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction as ActionsEditAction;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'Cliente';

   public static function form(Form $form): Form
    {
            return $form->components([

            // Seção: Informações Pessoais
            Section::make('Informações Pessoais')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome completo')
                        ->placeholder('Digite o nome do cliente')
                        ->required(),

                    // TextInput::make('cpf')
                    //     ->label('CPF')
                    //     ->placeholder('000.000.000-00')
                    //     // ->mask(fn ($mask) => $mask->pattern('000.000.000-00'))
                    //     ->required(),
                ]),

            // Seção: Contato
            Section::make('Contato')
                ->schema([
                    TextInput::make('telefone')
                        ->label('Whatsapp')
                        ->placeholder('(99) 99999-9999')
                        ->tel()
                        ->mask('(99) 99999-9999')
                        ->required(),

                    // TextInput::make('email')
                    //     ->label('E-mail')
                    //     ->placeholder('exemplo@dominio.com')
                    //     ->email()
                    //     ->required(),
                ]),

            // Seção: Endereço
            Section::make('Endereço')
                ->schema([
                    TextInput::make('cep')
                        ->label('CEP')
                        ->placeholder('00000-000'),
                        // ->mask(fn ($mask) => $mask->pattern('00000-000'))

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

    public static function table(Table $table): Table
    {
        return $table
           ->columns([
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('telefone')
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->options(GuardNames::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClientes::route('/'),
            // 'create' => CreateAgendamento::route('/create'),
            'view' => ViewCliente::route('/{record}'),
            'edit' => EditCliente::route('/{record}/edit'),
        ];
    }
}
