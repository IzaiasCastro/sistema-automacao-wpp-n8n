<?php

namespace App\Models;

use Illuminate\Console\View\Components\Warn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SessaoWhatsapp extends Model
{
    use HasFactory;

    protected array $guard_name = ['api', 'web'];
    protected $guarded = [];
    private $api_base_url;

    protected static function booted()
    {
        static::creating(function ($model) {
            // Gera um nome aleatório e único (ex: sessao_4f9a7d)
            $model->session_name = 'sessao_' . uniqid();
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->api_base_url = config('services.wpconnect.url_base');
    }


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


public function generateSession(SessaoWhatsapp $whatsapp)
{
    $apiBase = config('services.wpconnect.url_base');
    // dd($apiBase);
    // 🟢 Criar nova sessão
    $response = Http::post("{$apiBase}/session/start", [
        'sessionName' => $this->session_name,
        'webhook' => $this->webhook,
    ]);

    // dd($response->json());
    if ($response->failed()) {
        \Log::error("Erro ao criar sessão: " . $response->body());
        return ['error' => 'Falha ao criar sessão'];
    }

    $this->qrcode = $response['qrCode'] ?? null;
    $this->save();

    return $response;
}


    public function reopenSession(SessaoWhatsapp $sessaoWhatsapp)
    {
        $response = Http::post("{$this->api_base_url}/api/sessao/{$sessaoWhatsapp->session_name}/reopen");
        return $response->json();
    }


}
