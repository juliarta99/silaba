<?php

namespace App\Http\Controllers\Employee\FieldOfficer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Report;
use App\Models\ReportProgress;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    // ── Daftar Tugas ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);

        $query = Assignment::where('employee_id', $employee->id)
            ->with([
                'report' => fn ($q) => $q->with([
                    'category',
                    'district',
                    'tags',
                    'evidences' => fn ($e) => $e->where('file_type', 'photo')->limit(1),
                    'progresses' => fn ($p) => $p->latest()->limit(1),
                    'user',
                ]),
            ]);

        // Tampilkan semua status — urutan ditentukan oleh FIELD() di bawah
        // Filter status
        if ($request->filled('status')) {
            $query->whereHas('report', fn ($q) =>
                $q->where('status', $request->status)
            );
        }

        // Filter prioritas
        if ($request->filled('priority')) {
            $query->whereHas('report', fn ($q) =>
                $q->where('priority', $request->priority)
            );
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('report', fn ($q) =>
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
            );
        }

        // Urutan status: pending → in_progress → waiting_for_materials → under_review
        // lalu SLA deadline terdekat dalam tiap grup
        $query->join('reports as r_sort', 'r_sort.id', '=', 'assignments.report_id')
              ->orderByRaw("
                  FIELD(r_sort.status,
                      'pending',
                      'in_progress',
                      'waiting_for_materials',
                      'under_review',
                      'completed',
                      'rejected'
                  ) ASC
              ")
              ->orderByRaw('r_sort.sla_deadline IS NULL ASC')
              ->orderBy('r_sort.sla_deadline', 'asc')
              ->select('assignments.*');

        $totalCount  = (clone $query)->count();
        $assignments = $query->paginate(10)->withQueryString();

        // Hitung badge per status untuk filter
        $allAssignments = Assignment::where('employee_id', $employee->id)
            ->with('report:id,status')->get();
        $statusCounts = [
            'all'          => $allAssignments->count(),
            'active'       => $allAssignments->filter(fn ($a) => in_array($a->report?->status, ['pending','in_progress','under_review','waiting_for_materials']))->count(),
            'completed'    => $allAssignments->filter(fn ($a) => $a->report?->status === 'completed')->count(),
        ];

        return view('employee.field-officer.assignments.index', compact(
            'assignments', 'totalCount', 'statusCounts'
        ));
    }

    // ── Detail Tugas / Laporan ────────────────────────────────────────────
    public function show(string $code)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);

        // Pastikan petugas ini yang ditugaskan
        $assignment = Assignment::where('employee_id', $employee->id)
            ->whereHas('report', fn ($q) => $q->where('code', $code))
            ->firstOrFail();

        $report = Report::where('code', $code)
            ->with([
                'category',
                'district',
                'tags',
                'evidences',
                'user.citizen',
                'progresses' => fn ($q) => $q->with('employee.user')->latest(),
                'assignments.employee.user',
            ])
            ->firstOrFail();

        // Hitung SLA
        $slaInfo = $this->calcSla($report);

        return view('employee.field-officer.assignments.show', compact(
            'report', 'assignment', 'slaInfo'
        ));
    }

    // ── Form Update Progress ──────────────────────────────────────────────
    public function createProgress(string $code)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);

        Assignment::where('employee_id', $employee->id)
            ->whereHas('report', fn ($q) => $q->where('code', $code))
            ->firstOrFail();

        $report = Report::where('code', $code)->firstOrFail();

        $statusOptions = [
            'in_progress'           => 'Sedang Diproses',
            'waiting_for_materials' => 'Menunggu Material/Alat',
            'completed'             => 'Selesai',
        ];

        return view('employee.field-officer.assignments.progress-form', compact(
            'report', 'statusOptions'
        ));
    }

    // ── Simpan Update Progress ────────────────────────────────────────────
    public function storeProgress(Request $request, string $code, WhatsAppService $whatsapp)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);

        Assignment::where('employee_id', $employee->id)
            ->whereHas('report', fn ($q) => $q->where('code', $code))
            ->firstOrFail();

        $report = Report::where('code', $code)
            ->with('user.citizen')
            ->firstOrFail();

        $request->validate([
            'status'               => 'required|in:in_progress,waiting_for_materials,under_review,completed',
            'title'                => 'required|string|max:255',
            'description'          => 'required|string|min:10',
            'photo'                => 'nullable|file|mimes:png,jpg,jpeg|max:5120',
            'estimated_completion' => 'nullable|date|after:now',
            'notes'                => 'nullable|string|max:1000',
        ], [
            'status.required'      => 'Status progress wajib dipilih.',
            'title.required'       => 'Judul progress wajib diisi.',
            'description.required' => 'Deskripsi progress wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
            'photo.max'            => 'Ukuran foto maksimal 5MB.',
            'estimated_completion.after' => 'Estimasi harus waktu mendatang.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store(
                'progress/' . $report->id, 'public'
            );
        }

        // Simpan progress
        ReportProgress::create([
            'report_id'            => $report->id,
            'employee_id'          => $employee->id,
            'title'                => $request->title,
            'description'          => $request->description,
            'photo'                => $photoPath,
            'status'               => $request->status,
            'estimated_completion' => $request->estimated_completion,
            'notes'                => $request->notes,
        ]);

        // Update status laporan
        $report->update(['status' => $request->status]);

        // Notifikasi WA ke pelapor
        $phone = $report->user?->citizen?->phone ?? $report->guest_phone;
        if ($phone) {
            $statusLabel = [
                'in_progress'           => 'Sedang Diproses',
                'waiting_for_materials' => 'Menunggu Material',
                'under_review'          => 'Menunggu Verifikasi',
                'completed'             => 'Selesai',
            ][$request->status] ?? $request->status;

            $name = $report->user?->name ?? $report->guest_name ?? 'Warga';

            $whatsapp->send($phone,
                "🔔 *Update Laporan {$report->code} — SILABU*\n\n"
                . "Halo {$name},\n\n"
                . "📋 Tiket: *{$report->code}*\n"
                . "📝 Update: {$request->title}\n"
                . "⚡ Status: *{$statusLabel}*\n\n"
                . "{$request->description}\n\n"
                . ($request->estimated_completion
                    ? "🗓 Estimasi selesai: " . \Carbon\Carbon::parse($request->estimated_completion)->translatedFormat('j M Y, H:i') . "\n\n"
                    : '')
                . "🔗 Lihat detail laporan Anda pada link berikut:\n"
                . route('reports.show', $report->code) . "\n\n"
                . "Terima kasih atas kesabaran Anda."
            );
        }

        return redirect()
            ->route('employee.field-officer.assignments.show', $code)
            ->with('success', 'Progress berhasil diperbarui dan notifikasi telah dikirim ke pelapor.');
    }

    // ── Helper SLA ────────────────────────────────────────────────────────
    private function calcSla(Report $report): array
    {
        if (! $report->sla_deadline) {
            return ['text' => '—', 'percent' => 0, 'urgent' => false, 'expired' => false, 'total_hours' => 0];
        }

        $deadline   = \Carbon\Carbon::parse($report->sla_deadline);
        $created    = $report->created_at;
        $totalHours = max(1, $created->diffInHours($deadline));
        $elapsed    = $created->diffInHours(now());
        $remaining  = now()->diffInHours($deadline, false); // negatif jika terlambat
        $percent    = min(100, round(($elapsed / $totalHours) * 100));
        $expired    = $remaining <= 0;
        $urgent     = ! $expired && $remaining <= 24;

        if ($expired) {
            $text = $this->formatOverdue(abs((int) $remaining));
        } elseif ($remaining < 1) {
            $text = 'Kurang dari 1 jam';
        } elseif ($remaining < 24) {
            $text = (int)$remaining . ' jam tersisa';
        } elseif ($remaining < 48) {
            $text = '1 hari tersisa';
        } else {
            $days = (int) ceil($remaining / 24);
            $text = "{$days} hari tersisa";
        }

        return ['text' => $text, 'percent' => $percent, 'urgent' => $urgent, 'expired' => $expired, 'total_hours' => $totalHours];
    }

    /**
     * Format keterlambatan dalam bahasa manusia.
     * Contoh: 1789 jam → "74 hari 13 jam terlambat"
     */
    private function formatOverdue(int $hours): string
    {
        if ($hours < 1) {
            return 'Baru saja terlambat';
        }
        if ($hours < 24) {
            return "{$hours} jam terlambat";
        }
        $days  = intdiv($hours, 24);
        $sisa  = $hours % 24;

        if ($days < 7) {
            return $sisa > 0 ? "{$days} hari {$sisa} jam terlambat" : "{$days} hari terlambat";
        }
        if ($days < 30) {
            $weeks = intdiv($days, 7);
            $sDay  = $days % 7;
            return $sDay > 0 ? "{$weeks} minggu {$sDay} hari terlambat" : "{$weeks} minggu terlambat";
        }
        $months = intdiv($days, 30);
        $sDay   = $days % 30;
        return $sDay > 0 ? "{$months} bulan {$sDay} hari terlambat" : "{$months} bulan terlambat";
    }
}