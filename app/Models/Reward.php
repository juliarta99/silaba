<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'image',
        'points_required', 'stock', 'description',
    ];

    public function claims(): HasMany
    {
        return $this->hasMany(RewardClaim::class);
    }

    public function isAvailable(): bool { return $this->stock > 0; }

    public function decrementStock(): void
    {
        $this->decrement('stock');
    }
}
