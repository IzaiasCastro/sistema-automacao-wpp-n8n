<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AgendamentoResource;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms;

class CalendarWidget extends FullCalendarWidget
{
protected static ?string $maxWidth = null;

protected static ?string $maxHeight = '900vh';
protected static ?string $height = '900vh';
    // Opcional: Define o Model se você quiser usar a integração automática
    // public Model | string | null $model = Atendimento::class;

    // Configuração para exibir inicialmente a semana
    public function config(): array
    {
        return [
            'initialView' => 'dayGridWeek', // Mostra a semana em formato de grade
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridWeek,timeGridWeek,dayGridDay', // Opções de visualização
            ],
            'views' => [
                'timeGridWeek' => [
                    'dayHeaderFormat' => ['weekday' => 'short', 'day' => 'numeric'],
                ],
            ],
            // mobile: layout vertical
            'handleWindowResize' => true,
            'windowResize' => 'function(view) {
                calendar.updateSize();
            }',
            // Outras configurações do FullCalendar
        ];
    }

    /**
     * FullCalendar chamará esta função sempre que precisar de novos dados de evento.
     * Use $fetchInfo para filtrar por data.
     */

    public function fetchEvents(array $fetchInfo): array
{
    $start = Carbon::parse($fetchInfo['start']);
    $end = Carbon::parse($fetchInfo['end']);

    $professionalId = Profissional::where('user_id', Auth::user()->id)->first()?->id;

    $agendamentos = Agendamento::query()
        ->when($professionalId, function ($query, $professionalId) {
            return $query->where('profissional_id', $professionalId);
        })
        ->whereBetween('data', [$start->format('Y-m-d'), $end->format('Y-m-d')])
        ->where('organization_id', User::find(auth()->user()->id)->organization->first()->id)
        ->where('status', 'confirmado')
        ->with(['cliente', 'servicos'])
        ->get();

    return $agendamentos->map(function (Agendamento $agendamento) {
        $dataHorario = $agendamento->data . ' ' . $agendamento->horario;
        $inicioEvento = Carbon::parse($dataHorario);

        $tempoTotalMinutos = $agendamento->servicos->sum('tempo_medio');
        $fimEvento = $inicioEvento->copy()->addMinutes($tempoTotalMinutos);

        $titulo = optional($agendamento->cliente)->nome
            . ' - (' . $agendamento->servicos->pluck('nome')->implode(', ') . ')';

        $url = AgendamentoResource::getUrl('view', ['record' => $agendamento]);

        return [
            'id' => $agendamento->id,
            'title' => $titulo,
            'start' => $inicioEvento->toDateTimeString(),
            'end' => $fimEvento->toDateTimeString(),
            'url' => $url,
            'shouldOpenUrlInNewTab' => false,
            'color' => '#25D366',
        ];
    })->toArray();
}


    /**
     * Define o esquema do formulário de criação/edição.
     */
    public function getFormSchema(): array
    {
        // Obtém o ID da organização de forma defensiva
        $user = User::find(auth()->user()->id);
        $organizationId = $user ? ($user->organization->first()?->id ?? null) : null; 
        
        $professionalId = $this->getProfessionalId();

        $selectedServices = [];
        if ($this->record && $this->record instanceof Agendamento) {
            $selectedServices = $this->record->servicos->pluck('id')->toArray();
        }

        return [
            Forms\Components\Select::make('cliente_id')
                ->label('Cliente')
                ->options(
                    // 💡 CORREÇÃO APLICADA: Usa uma closure para carregamento tardio
                    fn () => Cliente::when($organizationId, fn ($query) => $query->where('organization_id', $organizationId))
                        ->orderBy('nome')
                        ->pluck('nome', 'id')
                        ->toArray()
                )
                ->searchable()
                ->required(),

            Forms\Components\Select::make('servicos')
                ->label('Serviços')
                ->options(
                    // 💡 CORREÇÃO APLICADA: Usa uma closure para carregamento tardio
                    fn () => Servico::when($organizationId, fn ($query) => $query->where('organization_id', $organizationId))
                        ->orderBy('nome')
                        ->pluck('nome', 'id')
                        ->toArray()
                )
                ->searchable()
                ->multiple()
                ->required()
                ->default($selectedServices), 
            
            Forms\Components\DatePicker::make('data')
                ->label('Data')
                ->required()
                ->default(fn (?array $state): string => 
                    (isset($state['start'])) 
                        ? Carbon::parse($state['start'])->toDateString() 
                        : now()->toDateString()
                )
                ->disabled(),

            Forms\Components\TimePicker::make('horario')
                ->label('Horário')
                ->required()
                ->seconds(false)
                ->default(fn (?array $state): string => 
                    (isset($state['start'])) 
                        ? Carbon::parse($state['start'])->format('H:i') 
                        : now()->format('H:i')
                ),
                
            Forms\Components\Hidden::make('profissional_id')
                ->default($professionalId),

            Forms\Components\Hidden::make('status')
                ->default('confirmado'),
        ];
    }
}