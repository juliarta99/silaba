<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RewardVoucher extends Model
{
    protected $fillable = [
        'reward_id', 'code', 'valid_from', 'valid_until', 'is_claimed',
    ];
 
    protected function casts(): array
    {
        return [
            'valid_from'  => 'date',
            'valid_until' => 'date',
            'is_claimed'  => 'boolean',
        ];
    }
 
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }
 
    public function claim(): HasOne
    {
        return $this->hasOne(RewardClaim::class);
    }
 
    // Scope: hanya yang belum diklaim
    public function scopeAvailable($query)
    {
        return $query->where('is_claimed', false)
                     ->where(function ($q) {
                         $q->whereNull('valid_until')
                           ->orWhere('valid_until', '>=', now()->toDateString());
                     });
    }
}
