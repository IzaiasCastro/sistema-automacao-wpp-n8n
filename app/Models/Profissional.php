<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profissional extends Model
{
    protected $guarded = [];

    protected array $guard_name = ['api', 'web'];

    public function agenda()
    {
        return $this->hasOne(Agenda::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
