<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointTransaction extends Model
{
    protected $fillable = [
        'citizen_id', 'type', 'amount', 'balance_after',
        'description', 'reference_type', 'reference_id',
    ];
 
    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }
 
    public function referenceClaim(): BelongsTo
    {
        return $this->belongsTo(RewardClaim::class, 'reference_id')
                    ->when($this->reference_type === 'RewardClaim');
    }
 
    public function referenceReport(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'reference_id')
                    ->when($this->reference_type === 'Report');
    }

}
