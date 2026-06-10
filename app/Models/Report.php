<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'code', 'title',
        'description', 'location', 'district_id',
        'latitude', 'longitude', 'priority',
        'sla_deadline', 'parent_report_id', 'status',
    ];

    protected function casts(): array
    {
        return [
            'sla_deadline' => 'datetime',
            'latitude'     => 'decimal:7',
            'longitude'    => 'decimal:7',
        ];
    }

    // ── Relations ──────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function parentReport(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'parent_report_id');
    }

    public function childReports(): HasMany
    {
        return $this->hasMany(Report::class, 'parent_report_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'report_tags');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(ReportEvidence::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(ReportProgress::class)->latest();
    }

    public function latestProgress(): HasOne
    {
        return $this->hasOne(ReportProgress::class)->latestOfMany();
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isRejected(): bool   { return $this->status === 'rejected'; }
    public function isOverdue(): bool
    {
        return $this->sla_deadline && now()->isAfter($this->sla_deadline)
            && !$this->isCompleted();
    }
}
