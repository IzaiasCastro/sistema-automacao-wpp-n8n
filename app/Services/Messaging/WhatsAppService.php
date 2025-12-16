<?php

namespace App\Services\Messaging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;
    protected string $session;

    public function __construct()
    {
        $this->baseUrl = config('services.wpconnect.url_base');
    }

    protected function formatPhone(string $phone): string
    {
        // Remove qualquer caractere que não seja número
        $digits = preg_replace('/\D/', '', $phone);

        // Garante que tenha 11 dígitos (DDD + 9 + número)
        // Exemplo: 86994388360 → 8694388360
        if (strlen($digits) === 11 && $digits[2] === '9') {
            $digits = $digits[0] . $digits[1] . substr($digits, 3);
        }

        // Adiciona o código do país 55 se ainda não tiver
        if (substr($digits, 0, 2) !== '55') {
            $digits = '55' . $digits;
        }

        return $digits;
    }

    /**
     * Envia uma mensagem de texto.
     */
    public function sendMessage(string $phone, string $message, string $session): bool
    {
        try {
            $phone = $this->formatPhone($phone);

            $response = Http::post("{$this->baseUrl}/message/send", [
                'sessionName' => $session,
                'number' => $phone,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error('Erro ao enviar mensagem WhatsApp', [
                    'number' => $phone,
                    'message' => $message,
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Exceção ao enviar mensagem WhatsApp', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Envia uma imagem com legenda.
     */
    public function sendImage(string $phone, string $imageUrl, ?string $caption = null, string $session): bool
    {
        try {
            $response = Http::post("{$this->baseUrl}/api/{$session}/send-image", [
                'phone' => $phone,
                'path' => $imageUrl,
                'caption' => $caption,
            ]);

            return $response->ok();
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar imagem via WhatsApp', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Verifica se o número é válido.
     */
    public function checkNumber(string $phone, string $session): ?bool
    {
        try {
            $response = Http::get("{$this->baseUrl}/api/{$session}/check-number/{$phone}");
            return $response->json('result.exists') ?? null;
        } catch (\Throwable $e) {
            Log::error('Erro ao checar número WhatsApp', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
