<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nip', 'phone', 'email',
        'status', 'start_year', 'end_year',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
