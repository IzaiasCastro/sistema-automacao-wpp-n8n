<?php

namespace App\Livewire;

use App\Models\Profissional;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ChatIa extends Component
{
    public $mensagens = [];
    public $entrada = '';

    public function enviar()
    {
        if (trim($this->entrada) === '') return;

        $this->mensagens[] = ['autor' => 'Você', 'texto' => $this->entrada];

        // Envia a pergunta para o n8n
        $resposta = $this->consultarN8n($this->entrada);

        $this->mensagens[] = ['autor' => 'Assistente', 'texto' => $resposta];

        $this->entrada = '';
    }

    public function consultarN8n($pergunta)
    {
        try {
            $usuarioAtual = User::find(auth()->user()->id);
            $usuarioAtual->load('organization');

            $dadosBarbearia = [
                'nome' => $usuarioAtual->organization->first()->name,
            ];

            $dadosDaAgendaProfissional = Profissional::where('organization_id', $usuarioAtual->organization->first()->id)
                ->where('user_id', auth()->user()->id)
                ->with('agenda')
                ->first();

            $agendaEmJson = json_encode($dadosDaAgendaProfissional->agenda);

            // Referência semanal
            $hoje = Carbon::now('America/Fortaleza');
            $hoje->locale('pt_BR');

            $referenciaSemanal = [
                'dia_semana_hoje' => strtolower(str_replace('-feira', '', $hoje->translatedFormat('l'))),
                'data_hoje' => $hoje->format('Y-m-d'),
                'amanha' => $hoje->copy()->addDay()->format('Y-m-d'),
            ];

            $diasSemana = [
                'segunda' => 'Monday',
                'terca'   => 'Tuesday',
                'quarta'  => 'Wednesday',
                'quinta'  => 'Thursday',
                'sexta'   => 'Friday',
                'sabado'  => 'Saturday',
                'domingo' => 'Sunday',
            ];

            foreach ($diasSemana as $pt => $en) {
                $referenciaSemanal[$pt] = $hoje->copy()->next($en)->format('Y-m-d');
            }

            $contexto = '- Nome da barbearia que eu trabalho: ' . $dadosBarbearia['nome'] .
                        '. Meu nome é: ' . $usuarioAtual->name .
                        ' e eu tenho essa agenda: ' . $agendaEmJson.
                        "**REGRA DE AÇÃO:** Se o usuário enviar uma mensagem que claramente indica que o profissional NÃO IRÁ TRABALHAR HOJE (ex: 'Não vou trabalhar hoje', 'cancelar expediente', 'folga hoje'), você DEVE retornar APENAS a string: 'ACTION:CANCEL_SHIFT'. Não adicione mais nada.";

            // 🌐 Envia para n8n
            // Aqui você coloca a URL do seu webhook no n8n
            $n8nWebhook = env('N8N_WEBHOOK_URL'); 

            $response = Http::post($n8nWebhook, [
                'usuario' => [
                    'id' => $usuarioAtual->id,
                    'nome' => $usuarioAtual->name,
                    'organization' => $dadosBarbearia,
                ],
                'agenda' => $agendaEmJson,
                'referenciaSemanal' => $referenciaSemanal,
                'contexto' => $contexto,
                'pergunta' => $pergunta,
            ]);
            // Espera que o n8n retorne JSON com campo "resposta"
            $responseBody = $response->body();
            
            // 1. Regex para extrair o conteúdo do atributo 'srcdoc'
            // O padrão busca: srcdoc=" (ou ')/ (.*?) /" (ou ')
            $pattern = '/srcdoc=["\'](.*?)["\']/is';
            $matches = [];

            if (preg_match($pattern, $responseBody, $matches)) {
                // O conteúdo do srcdoc estará no índice 1 do array $matches
                $respostaN8n = $matches[1];
                
                // Opcional: Decodificar entidades HTML se necessário (por exemplo, &quot; vira ")
                $respostaN8n = html_entity_decode($respostaN8n);
            } else {
                // Se a regex falhar (o que significa que não veio iframe), tenta o body inteiro.
                $respostaN8n = $responseBody; 
            }

            // Retorna a resposta limpa
            return $respostaN8n ?? '⚠️ Sem resposta do n8n.';

        } catch (\Exception $e) {
            return '⚠️ Erro ao se conectar com o n8n: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.chat-ia');
    }
}
