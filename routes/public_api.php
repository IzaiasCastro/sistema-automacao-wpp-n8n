<?php

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Organization;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\SessaoWhatsapp;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


Route::get('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);

});

Route::middleware('auth:sanctum')->post('/organization/create', function (Request $request) {

    $organization = Organization::create([
        'name' => $request->name_organization,
    ]);

    //criar usuario para a organizacao
    $user = User::create([
        'name' => $request->name_user,
        'email' => $request->email_user,
        'password' => Hash::make('#zaptend123')
    ]);

    //model has role
    DB::table('model_has_roles')->insert([
        'role_id' => $organization->roles->where('name', 'Propietario')->first()->id,
        'model_id' => $user->id,
        'model_type' => 'App\Models\User',
        'organization_id' => $organization->id
    ]);

    DB::table('organization_user')->insert([
        'organization_id' => $organization->id,
        'user_id' => $user->id
    ]);

    return response()->json([
        'organization' => $organization,
        'dados_user' => [
            'url_sistema' => env('APP_URL'),
            'email' => $user->email,
            'senha' => '#zaptend123'
        ]
    ]);

});


Route::get('/ping', function (Request $request) {
    // -------------------------------------------------------------------------
    // 1. Funções Auxiliares (Novas/Aprimoradas)
    // -------------------------------------------------------------------------

    /**
     * Converte hora (string) em Carbon, lidando com formatos variados.
     */
    $parseHora = function ($hora) {
        if (!$hora) return null;
        try {
            return Carbon::createFromFormat('H:i:s', $hora);
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('H:i', $hora);
            } catch (\Exception $e2) {
                return Carbon::parse($hora);
            }
        }
    };

    /**
     * Extrai o horário e intervalo de trabalho para um dia específico,
     * priorizando exceções sobre o padrão.
     * @param \App\Models\Agenda $agenda
     * @param string $dayKey ('segunda', 'terca', etc.)
     * @return array|null [ativo, inicio, fim, inicioIntervalo, fimIntervalo, limiteAgendamento]
     */
    $getScheduleForDay = function ($agenda, $dayKey) {
        // 1. Verifica se o dia é um dia de trabalho no padrão
        if (!in_array($dayKey, $agenda->dias_trabalho ?? [])) {
            return null; // Profissional não trabalha neste dia
        }

        // 2. Tenta encontrar uma exceção para o dia
        $excecoes = collect($agenda->excecoes_horario ?? []);
        $excecao = $excecoes->firstWhere('dia', $dayKey);

        // 3. Define a base de horários (Exceção ou Padrão)
        if ($excecao) {
            // Usa horários da exceção
            return [
                'ativo'             => true,
                'inicio'            => $excecao['inicio_expediente'] ?? null,
                'fim'               => $excecao['fim_expediente'] ?? null,
                'inicioIntervalo'   => $excecao['inicio_intervalo'] ?? null,
                'fimIntervalo'      => $excecao['fim_intervalo'] ?? null,
                'limiteAgendamento' => $excecao['limite_agendamento'] ?? $agenda->limite_agendamento,
                'tempoMedio'        => $excecao['tempo_medio'] ?? $agenda->tempo_medio ?? null
            ];
        }

        // 4. Usa horários do Padrão
        return [
            'ativo'             => true,
            'inicio'            => $agenda->inicio_expediente_padrao,
            'fim'               => $agenda->fim_expediente_padrao,
            'inicioIntervalo'   => $agenda->inicio_intervalo_padrao ?? null,
            'fimIntervalo'      => $agenda->fim_intervalo_padrao ?? null,
            'limiteAgendamento' => $agenda->limite_agendamento_padrao,
            'tempoMedio'        => $agenda->tempo_medio
        ];
    };

    // -------------------------------------------------------------------------
    // 2. Lógica Principal (Quase inalterada)
    // -------------------------------------------------------------------------

    $webhook = $request->input('webhook_organization_id');

    $sessao = SessaoWhatsapp::where('webhook', $webhook)->first();
    if (!$sessao) {
        return response()->json(['success' => false, 'message' => 'Sessão não encontrada'], 404);
    }

    $organizationId = $sessao->organization_id;
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

    // 📅 Datas de referência
    $referenciaSemanal = [];
    foreach ($diasSemana as $pt => $en) {
        $referenciaSemanal[$pt] = $hoje->copy()->next($en)->format('Y-m-d');
    }
    $referenciaSemanal['hoje'] = $hoje->format('Y-m-d');

    // 👨‍🔧 Profissionais e agendas
    $profissionais = Profissional::where('organization_id', $organizationId)
        ->with('agenda')
        ->get();

    $jsonAgendas = $profissionais->map(function ($profissional) use ($diasSemana, $referenciaSemanal, $organizationId, $parseHora, $getScheduleForDay) {
        $agenda = $profissional->agenda;
        if (!$agenda || !$agenda->status) {
            return [
                'profissional' => $profissional->nome,
                'horarios_disponiveis_semana' => [],
                'msg' => 'Agenda inativa'
            ];
        }

        // O tempo médio base é agora o campo PADRÃO
        $tempoMedioPadrao = $agenda->tempo_medio ?? 30;

        // 🧭 percorre cada dia da semana
        $semanaDisponivel = [];

        foreach ($diasSemana as $pt => $en) {
            $dataDia = Carbon::parse($referenciaSemanal[$pt]);
            
            // ⭐️ NOVO: Usa a função para obter o horário do dia (padrão ou exceção)
            $schedule = $getScheduleForDay($agenda, $pt);

            if (!$schedule || !$schedule['inicio'] || !$schedule['fim']) {
                $semanaDisponivel[$pt] = [];
                continue;
            }

            // Mapeia os dados do Schedule
            $inicioExpediente = $parseHora($schedule['inicio']);
            $fimExpediente = $parseHora($schedule['fim']);
            $inicioAlmoco = $parseHora($schedule['inicioIntervalo']);
            $fimAlmoco = $parseHora($schedule['fimIntervalo']);
            $tempoMedioDia = $schedule['tempoMedio'] ?? $tempoMedioPadrao;


            // Verifica se os horários foram parseados corretamente
            if (!$inicioExpediente || !$fimExpediente) {
                $semanaDisponivel[$pt] = [];
                continue;
            }

            // Busca agendamentos do dia
            $agendamentos = Agendamento::where('organization_id', $organizationId)
                ->where('profissional_id', $profissional->id)
                ->where('data', $dataDia->format('Y-m-d'))
                ->with('servicos')
                ->get();

            
            // ----------------------------------------------
            // NOVO: Contagem de agendamentos por dia
            // ----------------------------------------------
            $quantidadeAgendamentos = $agendamentos->count();
            $limiteDia = $schedule['limiteAgendamento'] ?? $agenda->limite_agendamento_padrao ?? 9999;

            $agendamentosPorDia[$pt] = [
                'quantidade' => $quantidadeAgendamentos,
                'limite'     => $limiteDia,
                'excedido'   => $quantidadeAgendamentos >= $limiteDia
            ];

            // 🔒 horários ocupados
            $horariosOcupados = [];
            foreach ($agendamentos as $ag) {
                $inicioAg = $parseHora($ag->horario);
                if (!$inicioAg) continue;

                $tempo = 0;
                // Deve usar o tempo médio real do agendamento (soma dos serviços)
                foreach ($ag->servicos as $servico) {
                    $tempo += $servico->tempo_medio;
                }
                // Se não houver serviços, usa o limite do dia
                if ($tempo === 0) $tempo = $tempoMedioDia; 

                $fimAg = $inicioAg->copy()->addMinutes($tempo);
                $cursor = $inicioAg->copy();

                // Marca como ocupado todos os slots de `tempoMedioDia` que o agendamento consome
                while ($cursor < $fimAg) {
                    $horariosOcupados[] = $cursor->format('H:i');
                    $cursor->addMinutes($tempoMedioDia); // Usa o slot de tempo do dia
                }
            }

            // 🕒 gerar horários livres
            $cursor = $inicioExpediente->copy();
            $horariosDisponiveis = [];
            while ($cursor < $fimExpediente) {
                $horaStr = $cursor->format('H:i');
                $horaCarbon = Carbon::createFromFormat('H:i', $horaStr);

                // O slot começa antes do fim do expediente, mas o serviço pode terminar depois.
                // Aqui checamos apenas se o slot de início cai dentro do intervalo de almoço.
                $emAlmoco = $inicioAlmoco && $fimAlmoco &&
                    $horaCarbon >= $inicioAlmoco && $horaCarbon < $fimAlmoco;

                if (!$emAlmoco && !in_array($horaStr, $horariosOcupados)) {
                    $horariosDisponiveis[] = $horaStr;
                }

                $cursor->addMinutes($tempoMedioDia); // Usa o slot de tempo do dia
            }

            $semanaDisponivel[$pt] = $horariosDisponiveis;
        }

        // 🔁 Agendamentos confirmados futuros (sem alteração)
        $agendamentosConfirmados = Agendamento::where('organization_id', $organizationId)
            ->where('profissional_id', $profissional->id)
            ->where('data', '>=', now()->format('Y-m-d'))
            ->with(['cliente', 'servicos'])
            ->orderBy('data')
            ->get()
            ->map(function ($ag) {
                return [
                    'data' => $ag->data,
                    'horario' => $ag->horario,
                    'cliente' => optional($ag->cliente)->nome,
                    'servicos' => $ag->servicos->pluck('nome')->toArray(),
                    'tempo_previsto' => $ag->tempo_previsto,
                ];
            });

        return [
            'profissional' => $profissional->nome,
            'telefone' => $profissional->telefone,
            'tempo_medio_padrao' => $tempoMedioPadrao,
            'horarios_disponiveis_semana' => $semanaDisponivel,
            'agendamentos_confirmados' => $agendamentosConfirmados,
             // 🆕 NOVO CAMPO
            'agendamentos_por_dia' => $agendamentosPorDia
        ];
    });

    return response()->json([
        'success' => true,
        'data' => [
            'session_name' => $sessao->session_name,
            'nome_estabelecimento' => $sessao->organization->name,
            'referencia_semanal' => $referenciaSemanal,
            'profissionais' => $jsonAgendas,
            'data_hoje' => $hoje->format('Y-m-d'),
            'horario_atual' => $hoje->format('H:i'),
            'servicos_disponiveis' => Servico::where('organization_id', $organizationId)
                ->where('status', true)
                ->get()
        ],
    ]);
});

Route::get('/buscar-cliente', function (Request $request) {
    $webhook =  $request->input('webhook_organization_id');
    $organizationId = SessaoWhatsapp::where('webhook', $webhook)->first()->organization_id;

    if( !$organizationId ) {
        Log::error('Organizacao nao encontrada pelo webhook: ' . $webhook);
        return response()->json([
            'success' => false,
            'message' => 'Organizacao nao encontrada',
        ], 404);
    }

    $cliente = Cliente::where('organization_id', $organizationId)->where('telefone', 'like', $request->input('whatsapp'))->first();
    if( !$cliente ) {
        Log::error('Cliente nao encontrado pelo whatsapp: ' . $request->input('whatsapp'));
        return response()->json([
            'success' => false,
            'message' => 'Cliente nao encontrado',
        ], 404);
    }
    return response()->json([
        'success' => true,
        'data' => $cliente,
    ]);
    
});

Route::post('/criar-cliente', function (Request $request) {
    $webhook =  $request->input('webhook_organization_id');
    $organizationId = SessaoWhatsapp::where('webhook', $webhook)->first()->organization_id;
    $dados = [
        'nome' => $request->input('nome'),
        'telefone' => $request->input('telefone'),
        'organization_id' => $organizationId
    ];
    $cliente = Cliente::create($dados);
    return response()->json([
        'success' => true,
        'data' => $cliente,
    ]);
});


Route::post('/agendamento', function (Request $request) {

    $webhook = $request->input('webhook_organization_id');
    $organizationId = SessaoWhatsapp::where('webhook', $webhook)->first()->organization_id;

    // Converter data_inicio DD/MM/YYYY H:i:s para MySQL
    try {
        $dataObj = Carbon::createFromFormat('d/m/Y H:i:s', $request->input('data_inicio'));
    } catch (\Exception $e) {
        Log::error('Formato de data inválido: ' . $request->input('data_inicio'));
        return response()->json([
            'success' => false,
            'message' => 'Formato de data inválido, use DD/MM/YYYY HH:MM:SS'
        ], 422);
    }

    $dataInicio = $dataObj->format('Y-m-d'); // somente data
    $horario = $dataObj->format('H:i');      // somente hora

    // Profissional
    $profissional = Profissional::where('organization_id', $organizationId)
        ->where('nome', 'like', $request->input('profissional'))
        ->first();

    if (!$profissional) {
        Log::error('Profissional não encontrado: ' . $request->input('profissional'));
        return response()->json([
            'success' => false,
            'message' => 'Profissional não encontrado',
        ], 404);
    }

    // ✅ VERIFICA LIMITE DE AGENDAMENTOS
    $diaSemana = strtolower($dataObj->format('l')); // Monday, Tuesday...
    $mapDias = [
        'monday'    => 'segunda_limite_agendamento',
        'tuesday'   => 'terca_limite_agendamento',
        'wednesday' => 'quarta_limite_agendamento',
        'thursday'  => 'quinta_limite_agendamento',
        'friday'    => 'sexta_limite_agendamento',
        'saturday'  => 'sabado_limite_agendamento',
        'sunday'    => 'domingo_limite_agendamento',
    ];

    $colunaLimite = $mapDias[$diaSemana] ?? null;

    if ($colunaLimite) {
        $limite = $profissional->agenda?->$colunaLimite ?? 1;
        if ($limite > 1) {
            $agendamentos = Agendamento::where('profissional_id', $profissional->id)
                ->where('data', $dataInicio)
                ->where('horario', $horario)
                ->where('status', 'confirmado')
                ->count();

            if ($agendamentos >= $limite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível criar o agendamento: limite diário excedido para este profissional',
                ], 422);
            }

        }
    }

    // Cliente
    $cliente = Cliente::where('organization_id', $organizationId)
        ->where('nome', 'like', $request->input('cliente'))
        ->first();

    if (!$cliente) {
        Log::error('Cliente nao encontrado: ' . $request->input('cliente'));
        return response()->json([
            'success' => false,
            'message' => 'Cliente nao encontrado',
        ], 404);
    }

    // Servicos: transformar string em array
    $servicosArray = array_map('trim', explode(',', $request->input('servicos')));

    // Buscar ou criar serviços no banco
    $servicosIds = [];
    $tempoPrevisto = 0;
    // dd($servicosArray);
    foreach ($servicosArray as $servico) {
        $servico = Servico::where(
            'nome','like', $servico)->where('organization_id' ,'like', $organizationId
        )->first();
        if( !$servico ) {
            Log::error('Servico nao encontrado: ' . $servico);
            return response()->json([
                'success' => false,
                'message' => 'Servico nao encontrado',
            ], 404);
        }
        $tempoPrevisto += $servico->tempo_medio;
        $servicosIds[] = $servico->id;
    }

    //converter para que se o tempo for maior que 60 ele retorne em horas e minutos
    if ($tempoPrevisto > 60) {
        $horas = floor($tempoPrevisto / 60);
        $minutos = $tempoPrevisto % 60;
        $tempoPrevisto = $horas . 'h ' . $minutos . 'min';
    }else{
        $tempoPrevisto = $tempoPrevisto . ' min';
    }

    // Verificar agendamento duplicado
    $agendamentoExistente = Agendamento::where('organization_id', $organizationId)
        ->where('profissional_id', $profissional->id)
        ->where('data', $dataInicio)
        ->where('horario', $horario)
        ->first();

    if ($agendamentoExistente) {
        Log::error('Agendamento duplicado para o profissional: ' . $profissional->nome . ' na data: ' . $dataInicio . ' horario: ' . $horario);
        return response()->json([
            'success' => false,
            'message' => 'Agendamento duplicado para o profissional na data e horario informados',
        ], 409);
    }


    // Criar agendamento
    $agendamento = Agendamento::create([
        'profissional_id' => $profissional->id,
        'cliente_id' => $cliente->id,
        'data' => $dataInicio,
        'horario' => $horario,
        'status' => 'confirmado',
        'organization_id' => $organizationId,
        'tempo_previsto' => $tempoPrevisto
    ]);

    

    // Vincular serviços ao agendamento
    $agendamento->servicos()->sync($servicosIds);

    return response()->json([
        'success' => true,
        'data' => $agendamento->load('servicos'), // retorna agendamento com serviços
    ]);
});

Route::post('/wpp-event', function (Request $request) {

    $event = $request->input('event');
    $data = $request->input('data');
    $sessionWebhook = $data['webhook'] ?? null; // URL do webhook específica da sessão

    Log::info('📩 Webhook recebido do WppConnect', [
        'event' => $event,
        'data' => $data,
    ]);

    // Verifica se existe um webhook da sessão
    if ($sessionWebhook) {
        try {
            Http::post($sessionWebhook, [
                'event' => $event,
                'data' => $data
            ]);

            Log::info("✅ Evento enviado pro webhook da sessão: {$sessionWebhook}");

        } catch (\Exception $e) {
            Log::error("❌ Erro ao enviar para o webhook da sessão: ".$e->getMessage());
        }
    } else {
        Log::warning("⚠️ Nenhum webhook da sessão definido");
    }

    return response()->json(['status' => 'ok']);
});

//respotas ia
Route::post('/api/assistant', function (Request $request) {
    // $response = ChatIa::consultarIa($request->input('prompt'));
    $response = 'Desculpe, nao consegui responder agora.';
    return response()->json(['resposta' => $response]);
});
