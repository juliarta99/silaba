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
        'user_id',
        'guest_name',       // ← TAMBAH: untuk laporan tamu
        'guest_phone',      // ← TAMBAH: untuk laporan tamu
        'category_id',
        'code',
        'title',
        'description',
        'location',
        'district_id',
        'latitude',
        'longitude',
        'priority',
        'sla_deadline',
        'parent_report_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sla_deadline' => 'datetime',
            // ← FIX: pakai 'float' bukan 'decimal:7'
            // 'decimal:7' cast mengembalikan string, bukan float,
            // sehingga operasi perbandingan numerik dan Leaflet JS bisa bermasalah
            'latitude'     => 'float',
            'longitude'    => 'float',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────

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

    // ← FIX: hapus ->latest() dari sini — scope di relasi menyebabkan
    // masalah saat eager loading dengan constraint tambahan, misal:
    // $report->progresses()->with(...)->get() akan diabaikan ordernya
    // Lebih baik sort di query masing-masing: ->progresses()->latest()->get()
    public function progresses(): HasMany
    {
        return $this->hasMany(ReportProgress::class);
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

    // ── Scopes ─────────────────────────────────────────────────────────────

    // Scope laporan aktif (belum ditolak)
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'rejected');
    }

    // Scope laporan publik (tampil di halaman Semua Laporan)
    public function scopePublic($query)
    {
        return $query->whereNull('parent_report_id') // hanya parent, bukan child/duplicate
                     ->where('status', '!=', 'rejected');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isOverdue(): bool
    {
        return $this->sla_deadline
            && now()->isAfter($this->sla_deadline)
            && ! $this->isCompleted()
            && ! $this->isRejected();
    }

    // Apakah laporan ini milik user tertentu (termasuk via child report)
    public function isOwnedBy(int $userId): bool
    {
        if ($this->user_id === $userId) {
            return true;
        }
        // Cek apakah ada child report milik user ini
        return $this->childReports->contains('user_id', $userId);
    }

    // Label status dalam Bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return [
            'pending'               => 'Baru',
            'in_progress'           => 'Diproses',
            'under_review'          => 'Menunggu Verifikasi',
            'waiting_for_materials' => 'Menunggu Material',
            'completed'             => 'Selesai',
            'rejected'              => 'Ditolak',
        ][$this->status] ?? 'Baru';
    }

    // Label prioritas dalam Bahasa Indonesia
    public function getPriorityLabelAttribute(): string
    {
        return [
            'low'      => 'Rendah',
            'medium'   => 'Sedang',
            'high'     => 'Tinggi',
            'critical' => 'Kritis',
        ][$this->priority] ?? 'Sedang';
    }

    // Apakah laporan ini adalah laporan gabungan (menjadi parent dari report lain)
    public function isGrouped(): bool
    {
        return $this->childReports()->exists();
    }

    // Nama pelapor — fallback ke guest_name jika tidak ada user
    public function getReporterNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Tamu';
    }
}