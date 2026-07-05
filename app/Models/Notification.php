<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'report_id', 'phone', 'message', 'is_sent', 'sent_at',
    ];
 
    protected function casts(): array
    {
        return [
            'is_sent' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }
 
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}

