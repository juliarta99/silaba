<?php

namespace App\Http\Controllers\DistrictChief;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $user         = Auth::user();
        $districtChief = $user->districtChief->load('district');

        abort_if(! $districtChief, 403, 'Data camat tidak ditemukan.');

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district->name;
        $now          = Carbon::now();

        // ── Helper: base query semua laporan di kecamatan ini ────────────
        $base = fn () => Report::where('district_id', $districtId);

        // ── KPI Row 1 ─────────────────────────────────────────────────────
        $totalLaporan   = ($base)()->count();
        $sedangDiproses = ($base)()->whereIn('status', ['in_progress','waiting_for_materials','under_review'])->count();

        $selesaiBulanIni = ($base)()->where('status','completed')
            ->whereMonth('updated_at', $now->month)
            ->whereYear('updated_at',  $now->year)->count();

        $selesaiBulanLalu = ($base)()->where('status','completed')
            ->whereMonth('updated_at', $now->copy()->subMonth()->month)
            ->whereYear('updated_at',  $now->copy()->subMonth()->year)->count();

        $growthPct = $selesaiBulanLalu > 0
            ? round((($selesaiBulanIni - $selesaiBulanLalu) / $selesaiBulanLalu) * 100, 1)
            : null;

        $terlambat = ($base)()->whereNotIn('status',['completed','rejected'])
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline','<', $now)->count();

        // ── KPI Row 2 ─────────────────────────────────────────────────────
        $avgHoursRaw = DB::table('reports')
            ->where('district_id', $districtId)
            ->where('status','completed')
            ->whereMonth('updated_at', $now->month)
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_h')
            ->value('avg_h');
        $avgDays = $avgHoursRaw ? round($avgHoursRaw / 24, 1) : 0;

        // Kepuasan
        $avgRating = DB::table('reviews')
            ->join('reports','reports.id','=','reviews.report_id')
            ->where('reports.district_id', $districtId)
            ->avg('reviews.rating');
        $kepuasan = $avgRating ? round(($avgRating / 5) * 100) : 0;

        // Kepatuhan SLA
        $totalSelesai = ($base)()->where('status','completed')->count();
        $tepatWaktu   = ($base)()->where('status','completed')
            ->whereNotNull('sla_deadline')
            ->whereRaw('updated_at <= sla_deadline')->count();
        $kepatuhan    = $totalSelesai > 0 ? round(($tepatWaktu / $totalSelesai) * 100) : 0;

        // SLA Compliance (hanya dari yg punya deadline)
        $dgSla       = ($base)()->where('status','completed')->whereNotNull('sla_deadline')->count();
        $slaCompliance = $dgSla > 0 ? round(($tepatWaktu / $dgSla) * 100, 1) : 0;

        // ── Grafik 6 bulan ────────────────────────────────────────────────
        $chartData = collect(range(5, 0))->map(function ($i) use ($base, $districtId, $now) {
            $month = $now->copy()->subMonths($i);
            $total = ($base)()->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            $done  = ($base)()->where('status','completed')->whereMonth('updated_at',$month->month)->whereYear('updated_at',$month->year)->count();
            return [
                'label'   => $month->translatedFormat('M'),
                'total'   => $total,
                'selesai' => $done,
            ];
        });

        // ── Kategori masalah terbanyak di kecamatan ini ───────────────────
        $kategoriStats = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->where('reports.district_id', $districtId)
            ->selectRaw('categories.name, COUNT(*) as total')
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($r) use ($districtId) {
                $r->pct = DB::table('reports')
                    ->where('district_id', $districtId)->count();
                $r->pct = $r->pct > 0 ? round(($r->total / $r->pct) * 100) : 0;
                return $r;
            });

        // Tren bulanan (untuk line chart)
        $trendData = collect(range(5, 0))->map(function ($i) use ($base, $districtId, $now) {
            $month = $now->copy()->subMonths($i);
            $total = ($base)()->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            $done  = ($base)()->where('status','completed')->whereMonth('updated_at',$month->month)->whereYear('updated_at',$month->year)->count();
            $rating = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->where('reports.district_id', $districtId)
                ->whereMonth('reviews.created_at',$month->month)
                ->whereYear('reviews.created_at',$month->year)
                ->avg('reviews.rating');
            return [
                'label'   => $month->translatedFormat('M'),
                'total'   => $total,
                'selesai' => $done,
                'kepuasan'=> $rating ? round(($rating/5)*100) : 0,
            ];
        });

        // ── Laporan perlu perhatian ───────────────────────────────────────
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

        // ── Top petugas di kecamatan ini bulan ini ───────────────────────
        $topOfficers = DB::table('employees')
            ->join('users','users.id','=','employees.user_id')
            ->join('assignments','assignments.employee_id','=','employees.id')
            ->join('reports','reports.id','=','assignments.report_id')
            ->where('reports.district_id', $districtId)
            ->where('reports.status','completed')
            ->whereMonth('reports.updated_at', $now->month)
            ->whereYear('reports.updated_at',  $now->year)
            ->select(
                'employees.id',
                'users.name',
                'users.picture',
                DB::raw('COUNT(reports.id) as done_tasks'),
                DB::raw('(SELECT COUNT(*) FROM assignments a2 JOIN reports r2 ON r2.id=a2.report_id WHERE a2.employee_id=employees.id) as total_tasks')
            )
            ->groupBy('employees.id','users.name','users.picture')
            ->orderByDesc('done_tasks')
            ->limit(3)
            ->get()
            ->map(function ($e) use ($districtId, $now) {
                $rating = DB::table('reviews')
                    ->join('reports','reports.id','=','reviews.report_id')
                    ->join('assignments','assignments.report_id','=','reports.id')
                    ->where('assignments.employee_id', $e->id)
                    ->where('reports.district_id', $districtId)
                    ->whereMonth('reviews.created_at', $now->month)
                    ->avg('reviews.rating');
                $e->satisfaction = $rating ? round(($rating/5)*100) : null;
                return $e;
            });

        // ── Periode laporan ───────────────────────────────────────────────
        $periodeBulanIni  = ($base)()->whereMonth('created_at',$now->month)->whereYear('created_at',$now->year)->count();
        $periodeBulanLalu = ($base)()->whereMonth('created_at',$now->copy()->subMonth()->month)->whereYear('created_at',$now->copy()->subMonth()->year)->count();
        $avgPerBulan      = $totalLaporan > 0 ? round($totalLaporan / max(1, $now->month)) : 0;

        return view('district_chief.dashboard', compact(
            'user','districtChief','districtName',
            'totalLaporan','sedangDiproses','selesaiBulanIni','terlambat',
            'growthPct','avgDays','kepuasan','kepatuhan','slaCompliance',
            'chartData','trendData','kategoriStats',
            'urgentReports','topOfficers',
            'periodeBulanIni','periodeBulanLalu','avgPerBulan',
        ));
    }
}