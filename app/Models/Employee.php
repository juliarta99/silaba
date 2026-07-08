<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nip', 'phone', 'email',
        'status', 'position', 'department_id',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(ReportProgress::class);
    }

    public function isActive(): bool { return $this->status === 'active'; }
}
