<?php

namespace App\Models;

use App\Observers\AgendaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([AgendaObserver::class])]

class Agenda extends Model
{
    protected array $guard_name = ['api', 'web'];
    protected $guarded = [];

    protected $casts = [
        'dias_trabalho'    => 'array', // Converte array PHP para JSON no DB
        'excecoes_horario' => 'array', // Converte array PHP para JSON no DB
    ];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
}
