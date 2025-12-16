<?php

namespace App\Observers;

use App\Models\Agendamento;
use App\Models\SessaoWhatsapp;
use App\Services\Messaging\WhatsAppService;
use Illuminate\Support\Facades\Log;

class AgendamentoObserver
{
    /**
     * Handle the Agendamento "created" event.
     */
    public function created(Agendamento $agendamento): void
    {
        try {
            // Garante que existe um profissional vinculado
            $profissional = $agendamento->profissional ?? $agendamento->user ?? null;

            if (!$profissional || empty($profissional->telefone)) {
                Log::warning('Profissional sem telefone vinculado ao agendamento', [
                    'agendamento_id' => $agendamento->id,
                ]);
                return;
            }

            // Monta a mensagem
            $mensagem = sprintf(
                "Olá %s! 📅\nVocê tem um novo agendamento:\n\n📌 Cliente: %s\n🗓️ Data: %s\n🕒 Hora: %s",
                $profissional->nome,
                $agendamento->cliente->nome,
                $agendamento->data,
                $agendamento->horario ?? 'Horário não informado'
            );

            // Envia via WhatsApp
            $whatsapp = new WhatsAppService();
            $organizationSessao = SessaoWhatsapp::where('organization_id', $profissional->organization_id)->first();
            $whatsapp->sendMessage($profissional->telefone, $mensagem, $organizationSessao->session_name);

            Log::info('Mensagem WhatsApp enviada para o profissional', [
                'profissional' => $profissional->id,
                'agendamento' => $agendamento->id,
                'sessao' => $organizationSessao->session_name
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar mensagem de agendamento via WhatsApp', [
                'erro' => $e->getMessage(),
                'agendamento' => $agendamento->id,
            ]);
        }
    }

    public function creating(Agendamento $agendamento): void {}
    public function updated(Agendamento $agendamento): void {}
    public function deleted(Agendamento $agendamento): void {}
    public function restored(Agendamento $agendamento): void {}
    public function forceDeleted(Agendamento $agendamento): void {}
}
