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
use App\Filament\Resources\Servicos\Pages\EditServico;
use App\Filament\Resources\Servicos\Pages\ListServicos;
use App\Filament\Resources\Servicos\Pages\ViewServico;
use App\Models\Servico;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Leandrocfe\FilamentPtbrFormFields\Money;

class ServicoResource extends Resource
{
    protected static ?string $model = Servico::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'Servico';

   public static function form(Form $form): Form
    {
        return $form
            ->components([
            
            // Seção: Informações do Serviço
            Section::make('Informações do Serviço')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome do Serviço')
                        ->placeholder('Ex: Corte de cabelo, Manicure, Barba')
                        ->required(),

                    // TextInput::make('preco')
                    //     ->label('Preço')
                    //     ->placeholder('Ex: 50.00')
                    //     ->numeric()
                    //     ->required()
                    //     ->helperText('Informe o valor em reais, sem símbolo R$.'),
                    
                  Money::make('preco') // O nome da coluna no seu banco de dados (ex: 'valor', 'preco')
                    ->label('Preço (R$)') // Label personalizado
                    ->default(0) // Valor padrão, se desejado
                    ->required(), // Regra de validação

                ]),
            // Seção: Descrição e Detalhes
            Section::make('Descrição do Serviço')
                ->schema([
                    Textarea::make('descricao')
                        ->label('Descrição')
                        ->placeholder('Descreva o serviço detalhadamente para o cliente')
                        ->columnSpanFull()
                        ->rows(4)
                ]),
            //tempo de atendimento
            Section::make('Tempo de Atendimento')
                ->schema([
                    TextInput::make('tempo_medio')
                        ->label('Tempo médio de atendimento (em minutos)')
                        ->numeric()
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

    public static function table(Table $table): Table
    {
        return $table
             ->columns([
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('preco')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('imagem')
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
            'index' => ListServicos::route('/'),
            // 'create' => CreateAgendamento::route('/create'),
            'view' => ViewServico::route('/{record}'),
            'edit' => EditServico::route('/{record}/edit'),
        ];
    }
}
