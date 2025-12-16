<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Agendamento;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\SessaoWhatsapp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Illuminate\Support\Facades\Route;
use League\Uri\Http;

Route::get('/minha/agenda/{sessionName}', function ($sessionName) {

    $organizationId = SessaoWhatsapp::where('session_name', $sessionName)->first()->organization_id;
    
    $hoje = Carbon::now('America/Fortaleza');

    $diasSemana = [
        'segunda' => 'Monday',
        'terca'   => 'Tuesday',
        'quarta'  => 'Wednesday',
        'quinta'  => 'Thursday',
        'sexta'   => 'Friday',
        'sabado'  => 'Saturday',
        'domingo' => 'Sunday',
    ];

    // 📆 Referência semanal
    $hoje->locale('pt_BR');
    $diaAtual = strtolower($hoje->translatedFormat('l'));
    $diaAtual = str_replace('-feira', '', $diaAtual);

    $referenciaSemanal = [
        'dia_semana_hoje' => $diaAtual,
        'amanha' => $hoje->copy()->addDay()->format('Y-m-d'),
    ];

    foreach ($diasSemana as $pt => $en) {
        $referenciaSemanal[$pt] = $hoje->copy()->next($en)->format('Y-m-d');
    }

    // 👨‍🔧 Profissionais e suas agendas
    $profissionais = Profissional::where('organization_id', $organizationId)
        ->with('agenda')
        ->get();

    $jsonAgendas = $profissionais->map(function ($profissional) use ($diasSemana, $hoje) {

        $agenda = $profissional->agenda;
        $disponivel = $agenda && $agenda->status;

        // 🗓️ Monta horários da semana do profissional
        $agendaSemanal = [];
        foreach ($diasSemana as $pt => $en) {
            $agendaSemanal[$pt] = [
                'inicio_expediente' => $agenda ? $agenda->{$pt . '_inicio_expediente'} : null,
                'fim_expediente'    => $agenda ? $agenda->{$pt . '_fim_expediente'} : null,
                'inicio_almoco'     => $agenda ? $agenda->{$pt . '_inicio_almoco'} : null,
                'fim_almoco'        => $agenda ? $agenda->{$pt . '_fim_almoco'} : null,
                'ativo'             => $agenda ? (bool)$agenda->{$pt} : false,
            ];
        }

        // 📅 Agendamentos confirmados (a partir de hoje)
        $agendamentosConfirmados = Agendamento::where('organization_id', $profissional->organization_id)->
        where('profissional_id', $profissional->id)
            ->where('data', '>=', $hoje->format('Y-m-d'))
            ->with(['cliente', 'servicos'])
            ->orderBy('data')
            ->get()
            ->map(function ($agendamento) {
                return [
                    'data' => $agendamento->data,
                    'horario' => $agendamento->horario,
                    'cliente' => $agendamento->cliente->nome ?? null,
                    'servicos' => $agendamento->servicos ?? null,
                ];
            });

        return [
            'profissional' => $profissional->nome,
            'whatsapp' => $profissional->telefone,
            'tempo_medio' => $agenda->tempo_medio ?? null,
            'agenda_semana' => $agendaSemanal,
            'agendamentos_confirmados' => $agendamentosConfirmados,
            'nome_estabelecimento' => '',
        ];
    });

    $response = 
            [
            'session_name' => SessaoWhatsapp::where('session_name', $sessionName)->first()->session_name,
            'referencia_semanal' => $referenciaSemanal,
            'profissionais' => $jsonAgendas,
            'data_hoje' => $hoje->format('Y-m-d'),
            'horario_atual' => $hoje->format('H:i'),
            'servicos_disponiveis' => Servico::where('organization_id', $organizationId)->where('status', true)->get()
            ];



    return view('agenda', compact('response'));
});

Route::get('session/status/{sessionName}', function ($sessionName) {
    try {
        $response = FacadesHttp::get(config('services.wpconnect.url_base') . "/session/status/{$sessionName}");
        
        if ($response->successful()) {
            return response()->json([
                'active' => (bool) ($response->json()['connected'] ?? false)
            ]);
        }

        return response()->json(['active' => false], 200);
    } catch (\Throwable $e) {
        return response()->json(['active' => false, 'error' => $e->getMessage()], 500);
    }
})->name('session.status');
