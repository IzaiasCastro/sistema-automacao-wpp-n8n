<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servico extends Model
{
    protected array $guard_name = ['api', 'web'];
    protected $guarded = [];

      public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function agendamentos()
    {
        return $this->belongsToMany(Agendamento::class, 'agendamento_servico');
    }

}
