<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardClaim extends Model
{
    protected $fillable = [
        'user_id', 'reward_id', 'reward_voucher_id', 'points_used', 'notes',
    ];
 
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }
 
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(RewardVoucher::class, 'reward_voucher_id');
    }
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}