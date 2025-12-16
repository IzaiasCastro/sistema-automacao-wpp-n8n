<?php

namespace App\Livewire;

use App\Models\SessaoWhatsapp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class QrcodeSession extends Component
{
     public $sessionName;
    public $qrcode;
    public $message;
    public $active = false;

    public $countdown = 60; // segundos até expirar

    public function mount($sessionName, $qrcode = null, $message = null)
    {
        $this->sessionName = $sessionName;
        $this->qrcode = $qrcode;
        $this->message = $message;

        $session = SessaoWhatsapp::where('session_name', $sessionName)->first();
        if ($session) {
            $this->active = (bool) $session->active;
        }
    }

    public function checkStatus()
    {
        try {
            $response = Http::get(config('services.wpconnect.url_base') . "/session/status/{$this->sessionName}");

            if ($response->successful()) {
                $connected = $response->json()['connected'] ?? false;
                $this->active = $connected;

                if ($connected) {
                    $this->activateSession();
                }
            }
        } catch (\Throwable $e) {
            Log::error("Erro ao checar status da sessão: " . $e->getMessage());
        }
    }

    public function activateSession()
    {
        $session = SessaoWhatsapp::where('session_name', $this->sessionName)->first();
        if ($session) {
            $session->active = 1;
            $session->save();
            $this->active = true;
        }
    }

    public function destroySession()
    {
        $session = SessaoWhatsapp::where('session_name', $this->sessionName)->first();

        if ($session && !$session->active) {
            try {
                Http::delete(config('services.wpconnect.url_base') . "/session/{$this->sessionName}");
                $this->createNewSession($session);
                $session->delete();
            } catch (\Throwable $e) {
                Log::error("❌ Erro ao deletar sessão '{$this->sessionName}': " . $e->getMessage());
            }
        }
    }

    public function createNewSession($sessaoModelo)
    {
        //criar nova sessão
        $sessao = new SessaoWhatsapp();
        $sessao->session_name = 'sessao_' . uniqid();
        $sessao->webhook = $sessaoModelo->webhook;
        $sessao->organization_id = $sessaoModelo->organization_id;
        $sessao->save();
    }


    public function render()
    {
        return view('livewire.qrcode-session');
    }
}
