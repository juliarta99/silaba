<?php

namespace App\Http\Controllers\Employee\FieldOfficer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ReportProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $employee = $user->employee;

        abort_if(! $employee, 403, 'Akun tidak terhubung ke data petugas.');

        $empId = $employee->id;

        // ── Stat cards ──────────────────────────────────────────────────
        $totalTugas = Assignment::where('employee_id', $empId)->count();

        $sedangDikerjakan = Assignment::where('employee_id', $empId)
            ->whereHas('report', fn ($q) =>
                $q->whereIn('status', ['in_progress', 'under_review', 'waiting_for_materials'])
            )->count();

        $selesai = Assignment::where('employee_id', $empId)
            ->whereHas('report', fn ($q) => $q->where('status', 'completed'))
            ->count();

        $terlambat = Assignment::where('employee_id', $empId)
            ->whereHas('report', fn ($q) =>
                $q->whereNotIn('status', ['completed', 'rejected'])
                  ->whereNotNull('sla_deadline')
                  ->where('sla_deadline', '<', now())
            )->count();

        // ── Tugas aktif (urut SLA terdekat) ─────────────────────────────
        $activeAssignments = Assignment::where('employee_id', $empId)
            ->whereHas('report', fn ($q) =>
                $q->whereNotIn('status', ['completed', 'rejected'])
            )
            ->with([
                'report' => fn ($q) => $q->with([
                    'category',
                    'district',
                    'user',
                    'evidences' => fn ($e) => $e->where('file_type', 'photo')->limit(1),
                ]),
            ])
            ->get()
            ->sortBy(fn ($a) =>
                $a->report?->sla_deadline
                    ? Carbon::parse($a->report->sla_deadline)->timestamp
                    : PHP_INT_MAX
            )
            ->take(5);

        // ── Aktivitas terbaru ────────────────────────────────────────────
        $recentActivities = ReportProgress::where('employee_id', $empId)
            ->with(['report:id,code,title'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Tips ─────────────────────────────────────────────────────────
        $tips = match(true) {
            $terlambat > 0        => "Anda memiliki {$terlambat} laporan yang melewati batas SLA. Segera tindaklanjuti.",
            $sedangDikerjakan > 0 => 'Prioritaskan tugas dengan SLA yang hampir habis untuk menghindari keterlambatan.',
            default               => 'Semua tugas aktif tertangani dengan baik. Tetap semangat!',
        };

        return view('employee.field-officer.dashboard', compact(
            'user', 'employee',
            'totalTugas', 'sedangDikerjakan', 'selesai', 'terlambat',
            'activeAssignments', 'recentActivities', 'tips'
        ));
    }
}