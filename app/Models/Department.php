<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'phone', 'email', 'logo', 'address',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
