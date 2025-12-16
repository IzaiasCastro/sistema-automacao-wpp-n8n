<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected array $guard_name = ['api', 'web'];
    protected $guarded = [];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getTotalPointsAttribute()
    {
        $earned = $this->pointTransactions()->where('type', 'earn')->sum('points');
        $redeemed = $this->pointTransactions()->where('type', 'redeem')->sum('points');
        return $earned - $redeemed;
    }
}
