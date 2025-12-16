<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointTransaction extends Model
{
    protected array $guard_name = ['api', 'web'];
    protected $guarded = [];

    use HasFactory;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    //user
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
