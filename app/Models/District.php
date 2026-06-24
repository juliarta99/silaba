<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'email', 'phone'];

    public function districtChiefs(): HasMany
    {
        return $this->hasMany(District_Chief::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function activeChief(): HasOne
    {
        return $this->hasOne(District_Chief::class)
                    ->where('status', 'active')
                    ->latestOfMany();
    }
}
