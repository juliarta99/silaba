<?php

namespace App\Http\Controllers\Employee\HeadOfDepartment;

use App\Http\Controllers\Controller;
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
        $employee = $user->employee->load('department');
        abort_if(! $employee, 403);

        $deptId = $employee->department_id;
        $now    = Carbon::now();

        // ── Helper closure: base query laporan dept ───────────────────────
        $base = fn () => Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        );

        // ── KPI Row 1 ─────────────────────────────────────────────────────
        $totalLaporan   = ($base)()->count();
        $sedangDiproses = ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count();

        $selesaiBulanIni = ($base)()->where('status','completed')
            ->whereMonth('updated_at', $now->month)
            ->whereYear('updated_at',  $now->year)
            ->count();

        $selesaiBulanLalu = ($base)()->where('status','completed')
            ->whereMonth('updated_at', $now->copy()->subMonth()->month)
            ->whereYear('updated_at',  $now->copy()->subMonth()->year)
            ->count();

        $growthPct = $selesaiBulanLalu > 0
            ? round((($selesaiBulanIni - $selesaiBulanLalu) / $selesaiBulanLalu) * 100, 1)
            : null;

        $terlambat = ($base)()->whereNotIn('status',['completed','rejected'])
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline','<', $now)
            ->count();

        // ── KPI Row 2 ─────────────────────────────────────────────────────
        // Rata-rata waktu selesai (jam → hari)
        $avgHoursRaw = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->where('categories.department_id', $deptId)
            ->where('reports.status','completed')
            ->whereMonth('reports.updated_at', $now->month)
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
            ->value('avg_h');

        $avgDays = $avgHoursRaw ? round($avgHoursRaw / 24, 1) : 0;

        // Tingkat kepuasan (rating /5 * 100)
        $avgRating = DB::table('reviews')
            ->join('reports','reports.id','=','reviews.report_id')
            ->join('categories','categories.id','=','reports.category_id')
            ->where('categories.department_id', $deptId)
            ->avg('reviews.rating');
        $kepuasan = $avgRating ? round(($avgRating / 5) * 100) : 0;

        // Tingkat kepatuhan SLA (laporan yg selesai sebelum deadline / total selesai)
        $totalSelesai = ($base)()->where('status','completed')->count();
        $tepatWaktu   = ($base)()->where('status','completed')
            ->whereNotNull('sla_deadline')
            ->whereRaw('updated_at <= sla_deadline')
            ->count();
        $kepatuhan = $totalSelesai > 0 ? round(($tepatWaktu / $totalSelesai) * 100) : 0;

        // SLA compliance % (selesai sebelum SLA dari yang punya SLA)
        $dgSla       = ($base)()->where('status','completed')->whereNotNull('sla_deadline')->count();
        $slaCompliance = $dgSla > 0 ? round(($tepatWaktu / $dgSla) * 100, 1) : 0;

        // ── Grafik 6 bulan terakhir ────────────────────────────────────────
        $chartData = collect(range(5, 0))->map(function ($i) use ($base, $now) {
            $month = $now->copy()->subMonths($i);
            $total = ($base)()->whereMonth('created_at', $month->month)
                              ->whereYear('created_at',  $month->year)->count();
            $done  = ($base)()->where('status','completed')
                              ->whereMonth('updated_at', $month->month)
                              ->whereYear('updated_at',  $month->year)->count();
            $rating = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', request()->user()->employee?->department_id)
                ->whereMonth('reviews.created_at', $month->month)
                ->whereYear('reviews.created_at',  $month->year)
                ->avg('reviews.rating');
            return [
                'label'   => $month->translatedFormat('M'),
                'total'   => $total,
                'selesai' => $done,
                'kepuasan'=> $rating ? round(($rating / 5) * 100) : 0,
            ];
        });

        // ── Statistik per kategori ────────────────────────────────────────
        $kategoriStats = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->where('categories.department_id', $deptId)
            ->selectRaw('
                categories.name,
                COUNT(*) as total,
                SUM(CASE WHEN reports.status = "completed" THEN 1 ELSE 0 END) as selesai
            ')
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('total')
            ->get()
            ->map(function ($r) {
                $r->pct = $r->total > 0 ? round(($r->selesai / $r->total) * 100) : 0;
                return $r;
            });

        // ── Sebaran per kecamatan ─────────────────────────────────────────
        $districtStats = District::withCount([
            'reports as total_reports' => fn ($q) =>
                $q->whereHas('category', fn ($c) => $c->where('department_id', $deptId)),
            'reports as done_reports' => fn ($q) =>
                $q->whereHas('category', fn ($c) => $c->where('department_id', $deptId))
                  ->where('status','completed'),
        ])
        ->having('total_reports', '>', 0)
        ->get()
        ->map(fn ($d) => tap($d, fn ($d) =>
            $d->pct = $d->total_reports > 0
                ? round(($d->done_reports / $d->total_reports) * 100) : 0
        ))
        ->sortByDesc('pct');

        // ── Laporan perlu perhatian ────────────────────────────────────────
        $urgentReports = ($base)()
            ->whereNotIn('status',['completed','rejected'])
            ->with(['category','district','assignments.employee.user'])
            ->where(fn ($q) =>
                $q->whereRaw('sla_deadline <= ?', [$now->copy()->addDays(3)])
                  ->orWhereDoesntHave('assignments')
            )
            ->orderByRaw("FIELD(priority,'critical','high','medium','low')")
            ->orderBy('sla_deadline')
            ->limit(3)
            ->get();

        // ── Top petugas bulan ini ─────────────────────────────────────────
        $topOfficers = Employee::where('department_id', $deptId)
            ->where('position','field_officer')
            ->where('status','active')
            ->with('user')
            ->withCount([
                'assignments as total_tasks',
                'assignments as done_tasks' => fn ($q) =>
                    $q->whereHas('report', fn ($r) =>
                        $r->where('status','completed')
                          ->whereMonth('updated_at', $now->month)
                    ),
            ])
            ->having('total_tasks','>',0)
            ->orderByDesc('done_tasks')
            ->limit(3)
            ->get()
            ->map(function ($e) use ($now) {
                $rating = DB::table('reviews')
                    ->join('reports','reports.id','=','reviews.report_id')
                    ->join('assignments','assignments.report_id','=','reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->whereMonth('reviews.created_at', $now->month)
                    ->avg('reviews.rating');
                $e->satisfaction = $rating ? round(($rating / 5) * 100) : null;
                return $e;
            });

        // ── Periode ───────────────────────────────────────────────────────
        $periodeBulanIni   = ($base)()->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $periodeBulanLalu  = ($base)()->whereMonth('created_at', $now->copy()->subMonth()->month)->whereYear('created_at', $now->copy()->subMonth()->year)->count();
        $avgPerBulan       = $totalLaporan > 0 ? round($totalLaporan / max(1, $now->month)) : 0;

        return view('employee.head-of-department.dashboard', compact(
            'user','employee',
            'totalLaporan','sedangDiproses','selesaiBulanIni','terlambat',
            'growthPct','avgDays','kepuasan','kepatuhan','slaCompliance',
            'chartData','kategoriStats','districtStats',
            'urgentReports','topOfficers',
            'periodeBulanIni','periodeBulanLalu','avgPerBulan',
        ));
    }
}