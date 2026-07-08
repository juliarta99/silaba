<?php

namespace App\Http\Controllers\Employee\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\District;
use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $employee = $user->employee;

        abort_if(! $employee, 403);

        $deptId = $employee->department_id;
        $now    = Carbon::now();

        // ── Stat cards row 1 ────────────────────────────────────────────
        $totalLaporan = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->count();

        $sedangDiproses = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->whereIn('status', ['in_progress', 'waiting_for_materials', 'under_review'])
         ->count();

        $selesaiBulanIni = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->where('status', 'completed')
         ->whereMonth('updated_at', $now->month)
         ->whereYear('updated_at',  $now->year)
         ->count();

        // Persentase naik dari bulan lalu
        $selesaiBulanLalu = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->where('status', 'completed')
         ->whereMonth('updated_at', $now->copy()->subMonth()->month)
         ->whereYear('updated_at',  $now->copy()->subMonth()->year)
         ->count();

        $growthPct = $selesaiBulanLalu > 0
            ? round((($selesaiBulanIni - $selesaiBulanLalu) / $selesaiBulanLalu) * 100, 1)
            : null;

        $terlambat = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->whereNotIn('status', ['completed', 'rejected'])
         ->whereNotNull('sla_deadline')
         ->where('sla_deadline', '<', $now)
         ->count();

        // ── Stat cards row 2 ────────────────────────────────────────────
        // Rata-rata waktu selesai (dalam hari)
        $avgDaysRaw = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->where('status', 'completed')
         ->whereMonth('updated_at', $now->month)
         ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
         ->value('avg_hours');

        $avgDays = $avgDaysRaw ? round($avgDaysRaw / 24, 1) : 0;

        // Tingkat kepuasan (avg rating review laporan di dept ini)
        $avgRating = DB::table('reviews')
            ->join('reports', 'reports.id', '=', 'reviews.report_id')
            ->join('categories', 'categories.id', '=', 'reports.category_id')
            ->where('categories.department_id', $deptId)
            ->whereMonth('reviews.created_at', $now->month)
            ->avg('reviews.rating');

        $satisfactionPct = $avgRating ? round(($avgRating / 5) * 100) : 0;

        // Total petugas & aktif
        $totalPetugas  = Employee::where('department_id', $deptId)->count();
        $petugasAktif  = Employee::where('department_id', $deptId)
                                 ->where('status', 'active')
                                 ->where('position', 'field_officer')
                                 ->count();

        // ── Laporan perlu perhatian: SLA mepet atau belum ditugaskan ────
        $urgentReports = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->whereNotIn('status', ['completed', 'rejected'])
         ->with([
            'category', 'district',
            'assignments' => fn ($q) => $q->with('employee.user'),
         ])
         ->where(fn ($q) =>
             $q->whereRaw('sla_deadline <= ?', [$now->copy()->addDays(3)])
               ->orWhereDoesntHave('assignments')
         )
         ->orderByRaw('assignments_count ASC')
         ->orderBy('sla_deadline', 'asc')
         ->withCount('assignments')
         ->limit(5)
         ->get();

        // ── Top petugas bulan ini ────────────────────────────────────────
        $topOfficers = Employee::where('department_id', $deptId)
            ->where('position', 'field_officer')
            ->where('status', 'active')
            ->with('user')
            ->withCount([
                'assignments as total_tasks',
                'assignments as completed_tasks' => fn ($q) =>
                    $q->whereHas('report', fn ($r) =>
                        $r->where('status', 'completed')
                          ->whereMonth('updated_at', $now->month)
                    ),
            ])
            ->having('total_tasks', '>', 0)
            ->orderBy('completed_tasks', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($e) use ($deptId, $now) {
                // Avg SLA dari laporan selesai bulan ini
                $avgHours = DB::table('reports')
                    ->join('assignments', 'assignments.report_id', '=', 'reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->where('reports.status', 'completed')
                    ->whereMonth('reports.updated_at', $now->month)
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                    ->value('avg_h');

                // Rating avg dari laporan petugas ini
                $avgRating = DB::table('reviews')
                    ->join('reports', 'reports.id', '=', 'reviews.report_id')
                    ->join('assignments', 'assignments.report_id', '=', 'reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->whereMonth('reviews.created_at', $now->month)
                    ->avg('reviews.rating');

                $e->avg_days     = $avgHours ? round($avgHours / 24, 1) : null;
                $e->satisfaction = $avgRating ? round(($avgRating / 5) * 100) : null;
                return $e;
            });

        // ── Statistik per kecamatan ──────────────────────────────────────
        $districtStats = District::withCount([
            'reports as total_reports' => fn ($q) =>
                $q->whereHas('category', fn ($c) => $c->where('department_id', $deptId)),
            'reports as completed_reports' => fn ($q) =>
                $q->whereHas('category', fn ($c) => $c->where('department_id', $deptId))
                  ->where('status', 'completed'),
        ])
        ->having('total_reports', '>', 0)
        ->orderBy('completed_reports', 'desc')
        ->get()
        ->map(function ($d) {
            $d->completion_pct = $d->total_reports > 0
                ? round(($d->completed_reports / $d->total_reports) * 100)
                : 0;
            return $d;
        });

        // ── Periode laporan ──────────────────────────────────────────────
        $periodeBulanIni  = $selesaiBulanIni;
        $periodeBulanLalu = $selesaiBulanLalu;

        return view('employee.supervisor.dashboard', compact(
            'user', 'employee',
            'totalLaporan', 'sedangDiproses', 'selesaiBulanIni', 'terlambat',
            'growthPct', 'avgDays', 'satisfactionPct', 'totalPetugas', 'petugasAktif',
            'urgentReports', 'topOfficers', 'districtStats',
            'periodeBulanIni', 'periodeBulanLalu',
        ));
    }
}