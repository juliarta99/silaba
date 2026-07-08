<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Reward extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'image',
        'points_required', 'description', 'is_active',
    ];
 
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
 
    public function vouchers(): HasMany
    {
        return $this->hasMany(RewardVoucher::class);
    }
 
    public function claims(): HasMany
    {
        return $this->hasMany(RewardClaim::class);
    }

    public function getStockAttribute(): int
    {
        return $this->vouchers()->where('is_claimed', false)->count();
    }
    
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}


