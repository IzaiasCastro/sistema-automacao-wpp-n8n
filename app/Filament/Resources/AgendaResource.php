<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Agendas\Pages\EditAgenda;
use App\Filament\Resources\Agendas\Pages\ListAgendas;
use App\Filament\Resources\Agendas\Pages\ViewAgenda;
use App\Models\Agenda;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
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
use Filament\Forms\Components\CheckboxList; // Novo
use Filament\Forms\Components\Repeater;     // Novo

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static bool $shouldRegisterNavigation = false;


    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $recordTitleAttribute = 'Agenda';

 public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações Essenciais')
                    ->schema([
                        Select::make('profissional_id')
                            ->relationship(
                                name: 'profissional',
                                titleAttribute: 'nome',
                                modifyQueryUsing: fn ($query) => $query->whereBelongsTo(Filament::getTenant())
                            )
                            ->required()
                            ->label('Profissional'),

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
            ]);
    }


    private static function getDiasTrabalhoSchema(): array
    {
        // Use chaves sem acento para os nomes dos campos (evita problemas com nomes de chave)
        $dias = [
            'segunda' => 'Segunda-feira',
            'terca'   => 'Terça-feira',
            'quarta'  => 'Quarta-feira',
            'quinta'  => 'Quinta-feira',
            'sexta'   => 'Sexta-feira',
            'sabado'  => 'Sábado',
            'domingo' => 'Domingo',
        ];

        $schema = [];

        foreach ($dias as $key => $label) {
            // Monta o schema base do dia
            $daySchema = [
                Toggle::make($key)
                    ->label("Trabalha na {$label}?")
                    ->reactive(),

                Grid::make()
                    ->columns(5)
                    ->schema([
                        TimePicker::make("{$key}_inicio_expediente")
                            ->label('Início expediente')
                            ->seconds(false)
                            ->hidden(fn($get) => !$get($key)),

                        TimePicker::make("{$key}_fim_expediente")
                            ->label('Fim expediente')
                            ->seconds(false)
                            ->hidden(fn($get) => !$get($key)),

                        TimePicker::make("{$key}_inicio_almoco")
                            ->label('Início intervalo')
                            ->seconds(false)
                            ->hidden(fn($get) => !$get($key)),

                        TimePicker::make("{$key}_fim_almoco")
                            ->label('Fim intervalo')
                            ->seconds(false)
                            ->hidden(fn($get) => !$get($key)),
                           
                        TextInput::make("{$key}_limite_agendamento")
                                        ->label('Limites de Agend...')
                                        ->numeric()
                                    ->hidden(fn($get) => !$get($key))
                    ]),
            ];

            // Se for a segunda-feira, adiciona o toggle de aplicar padrão logo abaixo dos campos dela
            if ($key === 'segunda') {
                $daySchema[] = Toggle::make('aplicar_padrao')
                    ->label('Aplicar horários de segunda-feira a todos os dias')
                    ->onColor('success')
                    ->offColor('gray')
                    ->reactive()
                    ->hidden(fn() => false) // mantém visível
                    ->dehydrateStateUsing(fn($state) => null) // ⚡ impede salvar no banco

                    ->afterStateUpdated(function ($state, $set, $get) {
                        if ($state) {
                            $padraoInicio = $get('segunda_inicio_expediente');
                            $padraoFim = $get('segunda_fim_expediente');
                            $padraoInicioAlmoco = $get('segunda_inicio_almoco');
                            $padraoFimAlmoco = $get('segunda_fim_almoco');

                            $diasParaCopiar = ['terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

                            foreach ($diasParaCopiar as $dia) {
                                $set("{$dia}", true);
                                $set("{$dia}_inicio_expediente", $padraoInicio);
                                $set("{$dia}_fim_expediente", $padraoFim);
                                $set("{$dia}_inicio_almoco", $padraoInicioAlmoco);
                                $set("{$dia}_fim_almoco", $padraoFimAlmoco);
                            }

                            // Reseta o toggle para evitar loops ou reaplicações
                            $set('aplicar_padrao', false);
                        }
                    })
                    ->helperText('Copia automaticamente os horários definidos na segunda-feira para todos os outros dias.');
            }

            // Finalmente cria a Section com o schema pronto
            $schema[] = Section::make($label)
                ->collapsible()
                ->schema($daySchema);
        }

        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profissional.nome')
                    ->label('Profissional')
                    ->icon('heroicon-o-user-circle')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tempo_medio')
                    ->label('Tempo médio (min)')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Disponível')
                    ->formatStateUsing(fn($state) => $state ? 'Sim' : 'Não')
                    ->badge()
                    ->colors([
                        'success' => fn($state) => $state === 1,
                        'danger' => fn($state) => $state === 0,
                    ]),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return true;
    }

public static function hasBreadcrumb(): bool
{
    return false;
}




    public static function getPages(): array
    {
        return [
            'index' => ListAgendas::route('/'),
            'view' => ViewAgenda::route('/{record}'),
            'edit' => EditAgenda::route('/{record}/edit'),
        ];
    }
}
