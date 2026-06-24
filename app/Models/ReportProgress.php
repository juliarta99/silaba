<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'employee_id', 'title', 'description',
        'photo', 'estimated_completion', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['estimated_completion' => 'datetime'];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
