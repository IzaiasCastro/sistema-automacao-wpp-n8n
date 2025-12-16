<?php

namespace App\Observers;

use App\Models\SessaoWhatsapp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SessaoWhatsappObserver
{
    public function created(SessaoWhatsapp $whatsapp): void
    {
        // 🔹 Pega a última sessão dessa organização
        $sessaoAntiga = SessaoWhatsapp::where('organization_id', $whatsapp->organization_id)
            ->where('id', '!=', $whatsapp->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($sessaoAntiga) {
            $sessaoAntiga->delete();

            try {
                $sessionName = $sessaoAntiga->session_name;

                // ✅ Corrigido: monta a URL corretamente
                $baseUrl = config('services.wpconnect.url_base') ?? env('WPPCONNECT_URL');
                $url = "{$baseUrl}/session/{$sessionName}";

                $response = Http::timeout(10)->delete($url);

                if ($response->successful()) {
                    Log::info("🗑️ Sessão antiga '{$sessionName}' removida com sucesso no WPPConnect.");
                } else {
                    Log::warning("⚠️ Falha ao remover sessão '{$sessionName}' no WPPConnect.", [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("❌ Erro ao deletar sessão '{$sessaoAntiga->session_name}' no WPPConnect: " . $e->getMessage());
            }
        }
    }
}
