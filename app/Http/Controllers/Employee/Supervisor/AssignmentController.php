<?php

namespace App\Http\Controllers\Employee\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Employee;
use App\Models\Report;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    // ── Halaman Penugasan Petugas ─────────────────────────────────────────
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);
        $deptId = $employee->department_id;

        // Laporan yang belum/perlu ditugaskan (aktif di dept ini)
        $unassignedReports = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->whereNotIn('status', ['completed', 'rejected'])
         ->with([
            'category', 'district',
            'evidences' => fn ($q) => $q->where('file_type','photo')->limit(1),
            'assignments.employee.user',
            'childReports',
         ])
         ->orderByRaw("(SELECT COUNT(*) FROM assignments WHERE assignments.report_id = reports.id) ASC")
         ->orderBy('sla_deadline', 'asc')
         ->get();

        // Semua field officer aktif di dept
        $officers = Employee::where('department_id', $deptId)
            ->where('position', 'field_officer')
            ->where('status', 'active')
            ->with(['user', 'department'])
            ->withCount([
                'assignments as active_tasks' => fn ($q) =>
                    $q->whereHas('report', fn ($r) =>
                        $r->whereNotIn('status', ['completed','rejected'])
                    ),
                'assignments as total_tasks',
            ])
            ->orderBy('active_tasks')
            ->get()
            ->map(function ($e) {
                // Avg hari selesai
                $avgHours = DB::table('reports')
                    ->join('assignments', 'assignments.report_id', '=', 'reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->where('reports.status', 'completed')
                    ->whereMonth('reports.updated_at', now()->month)
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                    ->value('avg_h');

                $avgRating = DB::table('reviews')
                    ->join('reports', 'reports.id', '=', 'reviews.report_id')
                    ->join('assignments', 'assignments.report_id', '=', 'reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->avg('reviews.rating');

                // Beban
                $e->avg_days    = $avgHours ? round($avgHours / 24, 1) : null;
                $e->avg_rating  = $avgRating ? round($avgRating, 1) : null;
                $e->load_label  = match(true) {
                    $e->active_tasks <= 2  => ['label' => 'Rendah',  'class' => 'text-success'],
                    $e->active_tasks <= 5  => ['label' => 'Sedang',  'class' => 'text-yellow-600'],
                    default                => ['label' => 'Tinggi',  'class' => 'text-error'],
                };
                // Tags dari kategori laporan aktif
                $e->categoryTags = \App\Models\Report::join('assignments as a2','a2.report_id','=','reports.id')
                    ->where('a2.employee_id', $e->id)
                    ->whereNotIn('reports.status',['completed','rejected'])
                    ->with('category')
                    ->get()
                    ->pluck('category.name')
                    ->unique()
                    ->take(3)
                    ->values();

                return $e;
            });

        // ── Serialize ke array plain untuk dipakai Alpine.js (search & pagination client-side) ──
        $reportsData = $unassignedReports->map(function ($report) {
            $assignedNames = $report->assignments
                ->map(fn ($a) => $a->employee?->user?->name)
                ->filter()
                ->values();
            $assignedIds = $report->assignments
                ->pluck('employee_id')
                ->map(fn ($id) => (int) $id)
                ->values();
            $ev = $report->evidences->first();

            return [
                'id'            => $report->id,
                'code'          => $report->code,
                'title'         => $report->title,
                'category'      => $report->category?->name,
                'district'      => $report->district?->name,
                'priority'      => $report->priority,
                'created'       => $report->created_at->translatedFormat('j M Y'),
                'assignedIds'   => $assignedIds,
                'assignedNames' => $assignedNames,
                'childCount'    => $report->childReports->count(),
                'photo'         => $ev ? Storage::url($ev->file_path) : null,
            ];
        })->values();

        $officersData = $officers->map(function ($officer) {
            return [
                'id'            => $officer->id,
                'name'          => $officer->user?->name,
                'picture'       => $officer->user?->picture ? Storage::url($officer->user->picture) : null,
                'categoryTags'  => $officer->categoryTags,
                'activeTasks'   => $officer->active_tasks,
                'totalTasks'    => $officer->total_tasks,
                'loadLabel'     => $officer->load_label['label'],
                'loadClass'     => $officer->load_label['class'],
                'avgDays'       => $officer->avg_days,
                'avgRating'     => $officer->avg_rating,
            ];
        })->values();

        return view('employee.shared.assignments.index', compact(
            'unassignedReports', 'officers', 'reportsData', 'officersData'
        ));
    }

    // ── Simpan Penugasan ──────────────────────────────────────────────────
    public function store(Request $request, WhatsAppService $whatsapp)
    {
        $request->validate([
            'report_id'    => 'required|exists:reports,id',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'priority'     => 'nullable|in:low,medium,high,critical',
            'est_days'     => 'nullable|integer|min:1|max:30',
            'notes'        => 'nullable|string|max:500',
        ]);

        $report = Report::with(['category','district','user.citizen'])
                        ->findOrFail($request->report_id);

        DB::transaction(function () use ($request, $report, $whatsapp) {
            // Update prioritas jika diubah
            if ($request->filled('priority') && $request->priority !== $report->priority) {
                $report->update(['priority' => $request->priority]);
            }

            foreach ($request->employee_ids as $empId) {
                $exists = Assignment::where('report_id',   $report->id)
                                    ->where('employee_id', $empId)
                                    ->exists();
                if ($exists) continue;

                Assignment::create([
                    'report_id'   => $report->id,
                    'employee_id' => $empId,
                ]);

                // Update status laporan ke in_progress jika masih pending
                if ($report->status === 'pending') {
                    $report->update(['status' => 'in_progress']);
                }

                // Notifikasi WA ke petugas
                $officer = Employee::with('user')->find($empId);
                if ($officer?->phone) {
                    $whatsapp->send($officer->phone,
                        "🔔 *Penugasan Baru — SILABU*\n\n"
                        . "Halo {$officer->user?->name},\n\n"
                        . "Anda mendapat penugasan laporan baru:\n"
                        . "📋 Tiket: *{$report->code}*\n"
                        . "📝 {$report->title}\n"
                        . "📍 {$report->location}\n"
                        . ($request->filled('notes') ? "📌 Catatan: {$request->notes}\n" : '')
                        . "\nSegera tindaklanjuti laporan ini."
                    );
                }
            }

            // Notifikasi WA ke pelapor
            $phone = $report->user?->citizen?->phone ?? $report->guest_phone;
            if ($phone) {
                $jumlah = count($request->employee_ids);
                $whatsapp->send($phone,
                    "✅ *Update Laporan {$report->code} — SILABU*\n\n"
                    . "{$jumlah} petugas telah ditugaskan untuk menangani laporan Anda.\n"
                    . "Kami akan mengirim update progress secara berkala."
                );
            }
        });

        return redirect()
            ->route('employee.supervisor.reports.index')
            ->with('success', 'Petugas berhasil ditugaskan.');
    }

    // ── Hapus Assignment ──────────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $request->validate([
            'report_id'   => 'required|exists:reports,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        Assignment::where('report_id',   $request->report_id)
                  ->where('employee_id', $request->employee_id)
                  ->delete();

        return back()->with('success', 'Petugas berhasil dihapus dari laporan.');
    }
}