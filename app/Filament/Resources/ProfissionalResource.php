<?php

namespace App\Filament\Resources;

use App\Enums\GuardNames;
use App\Filament\Resources\ProfissionalResource\Pages;
use App\Filament\Resources\ProfissionalResource\RelationManagers;
use App\Filament\Resources\Profissionals\Schemas\ProfissionalForm;
use App\Filament\Resources\RoleResource\RelationManagers\PermissionsRelationManager;
use App\Filament\Resources\RoleResource\RelationManagers\UsersRelationManager;
use App\Models\Profissional;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;
use Filament\Notifications\Notification;


class ProfissionalResource extends Resource
{
    // protected static bool $isScopedToTenant = false;
    protected static ?string $model = Profissional::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $schema): Form
    {
        return $schema
            ->components([
                // Seção: Informações Pessoais
            Section::make('Informações Pessoais')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome completo')
                        ->placeholder('Digite o nome do profissional')
                        ->required(),
                        // Seção: Contato
                     TextInput::make('telefone')
                        ->label('Whatsapp')
                        ->placeholder('(99) 99999-9999')
                        ->tel()
                        ->required()
                        ->mask('(99) 99999-9999'),
                ]),


            // Seção: Endereço
            Section::make('Endereço')
                ->schema([
                    TextInput::make('cep')
                        ->label('CEP')
                        ->placeholder('00000-000'),
                        // ->mask(fn ($mask) => $mask->pattern('00000-000')),

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

            // Seção: Imagem do Perfil
            Section::make('Imagem do Perfil')
                ->schema([
                    FileUpload::make('imagem')
                        ->label('Foto do Profissional')
                        ->image()
                        ->directory('profissionais')
                        ->maxSize(2048) // 2MB
                        ->helperText('Envie uma foto clara e de boa qualidade.'),
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
                Tables\Actions\Action::make('criarAgenda')
                ->label('Criar Agenda')
                ->visible(fn ($record) => $record->agenda()->count() == 0)
                ->icon('heroicon-o-calendar')
                ->modalHeading('Criar Agenda')
                ->modalWidth('4xl')  // <<< tamanho aumentado
                ->form([
                    Forms\Components\Hidden::make('profissional_id'),

                 Section::make('Informações Essenciais')
                    ->schema([

                     TextInput::make('tempo_medio')
                            ->label('Tempo Médio (em minutos)')
                            ->helperText('O tempo médio que um atendimento leva. Ex: 30 para 30 minutos.')
                            ->required()
                            ->numeric(),
                        TextInput::make('limite_agendamento_padrao')
                            ->label('Limite de Clientes para atender no dia')
                            ->helperText('Quantidade de clientes que podem ser atendidos no dia. Ex: 20 clientes.')
                            ->numeric(),
                    ]),
                      Section::make('Horário de Trabalho Padrão')
                    ->description('Defina o horário comum da semana. Dias com horários diferentes serão configurados em "Exceções".')
                    ->columns(2)
                    ->schema([
                        CheckboxList::make('dias_trabalho')
                            ->label('Dias de Trabalho na Semana')
                            ->options([
                                'segunda' => 'Segunda-feira',
                                'terca'   => 'Terça-feira',
                                'quarta'  => 'Quarta-feira',
                                'quinta'  => 'Quinta-feira',
                                'sexta'   => 'Sexta-feira',
                                'sabado'  => 'Sábado',
                                'domingo' => 'Domingo',
                            ])
                            ->columns(3)
                            ->required()
                            ->helperText('Selecione os dias em que o profissional trabalha.'),
                        
                        Grid::make()
                            ->columns(4)
                            ->schema([
                                TimePicker::make('inicio_expediente_padrao')
                                    ->label('Início Expediente')
                                    ->seconds(false)
                                    ->required(),
    
                                TimePicker::make('fim_expediente_padrao')
                                    ->label('Fim Expediente')
                                    ->seconds(false)
                                    ->required(),
    
                                TimePicker::make('inicio_intervalo_padrao')
                                    ->label('Início Intervalo')
                                    ->seconds(false),
    
                                TimePicker::make('fim_intervalo_padrao')
                                    ->label('Fim Intervalo')
                                    ->seconds(false),
                            ]),
                    ]),
                    
                Section::make('Exceções por Dia (Opcional)')
                    ->description('Use esta seção apenas se algum dia tiver um horário DIFERENTE do padrão acima (ex: a Segunda-feira termina mais cedo).')
                    ->schema([
                        Repeater::make('excecoes_horario') // Este campo precisaria ser salvo como JSON ou array de exceções no model
                            ->label('Horários Específicos/Diferentes')
                            ->schema([
                                Select::make('dia')
                                    ->options([
                                        'segunda' => 'Segunda-feira',
                                        'terca'   => 'Terça-feira',
                                        'quarta'  => 'Quarta-feira',
                                        'quinta'  => 'Quinta-feira',
                                        'sexta'   => 'Sexta-feira',
                                        'sabado'  => 'Sábado',
                                        'domingo' => 'Domingo',
                                    ])
                                    ->required()
                                    ->label('Dia com Exceção'),

                                Grid::make(5)
                                    ->schema([
                                        TimePicker::make('inicio_expediente')
                                            ->label('Início Expediente')
                                            ->seconds(false)
                                            ->required(),
            
                                        TimePicker::make('fim_expediente')
                                            ->label('Fim Expediente')
                                            ->seconds(false)
                                            ->required(),
            
                                        TimePicker::make('inicio_intervalo')
                                            ->label('Início Intervalo')
                                            ->seconds(false),
            
                                        TimePicker::make('fim_intervalo')
                                            ->label('Fim Intervalo')
                                            ->seconds(false),
                                        
                                        TextInput::make('limite_agendamento')
                                            ->label('Limite Agend')
                                            ->numeric()
                                            ->helperText('Se diferente do padrão.')
                                            ->nullable(), // Permite usar o limite padrão se estiver vazio
                                    ])
                            ])
                            ->defaultItems(0)
                            ->minItems(0)
                            ->maxItems(7) // Não faz sentido ter mais de 7 exceções
                            ->columns(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['dia']) ? 
                                    (
                                        [
                                            'segunda' => 'Segunda-feira', 'terca' => 'Terça-feira', 'quarta' => 'Quarta-feira', 
                                            'quinta' => 'Quinta-feira', 'sexta' => 'Sexta-feira', 'sabado' => 'Sábado', 
                                            'domingo' => 'Domingo'
                                        ])[$state['dia']] . " ({$state['inicio_expediente']} - {$state['fim_expediente']})" 
                                    : null)
                    ])
                ])
                ->mountUsing(function ($form, $record) {
                    $form->fill([
                        'profissional_id' => $record->id,
                    ]);
                })
                ->action(function (array $data) {
                    $data['organization_id'] = \App\Models\User::find(auth()->user()->id)->organization->first()->id;
                    \App\Models\Agenda::create($data);

                    Notification::make()
                    ->title('Agenda criada com sucesso!')
                    ->success()
                    ->send();
                }),

                Tables\Actions\Action::make('editarAgenda')
                ->label('Editar Agenda')
                ->icon('heroicon-o-pencil')
                ->visible(fn ($record) => $record->agenda()->count() > 0)
                ->url(fn ($record) => \App\Filament\Resources\AgendaResource::getUrl('edit', [
                    'record' => $record->agenda->id, // id da agenda
                ]))
                ->openUrlInNewTab(false),
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
            // UsersRelationManager::make(),
            // PermissionsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfissionals::route('/'),
            // 'create' => Pages\CreateProfissional::route('/create'),
            'edit' => Pages\EditProfissional::route('/{record}/edit'),
        ];
    }
}
