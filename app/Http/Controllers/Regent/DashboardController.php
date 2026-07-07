<?php

namespace App\Http\Controllers\Regent;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403, 'Data bupati tidak ditemukan.');

        $now = Carbon::now();

        // ── Parse date range ──────────────────────────────────────────────
        [$dateFrom, $dateTo, $activePreset] = $this->parseDateRange($request);

        // Base query dengan date filter
        $base = fn () => Report::when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
                                ->when($dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

        // ── KPI utama ─────────────────────────────────────────────────────
        $totalLaporan   = ($base)()->count();
        $selesaiBulanIni = ($base)()->where('status','completed')->count();
        $sedangDiproses  = ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count();
        $terlambat       = ($base)()->whereNotIn('status',['completed','rejected'])
                                    ->whereNotNull('sla_deadline')
                                    ->where('sla_deadline','<', $now)->count();

        // Growth vs periode sebelumnya (hanya jika ada date filter)
        $growthSelesai = null;
        $growthTotal   = null;
        $growthKepuasan = null;

        if ($dateFrom && $dateTo) {
            $daysDiff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
            $prevFrom = Carbon::parse($dateFrom)->subDays($daysDiff)->toDateString();
            $prevTo   = Carbon::parse($dateFrom)->subDay()->toDateString();

            $prevBase = fn () => Report::whereDate('created_at', '>=', $prevFrom)
                                       ->whereDate('created_at', '<=', $prevTo);

            $prevTotal   = ($prevBase)()->count();
            $prevSelesai = ($prevBase)()->where('status','completed')->count();

            $growthTotal   = $prevTotal   > 0 ? round((($totalLaporan - $prevTotal)   / $prevTotal)   * 100, 1) : null;
            $growthSelesai = $prevSelesai > 0 ? round((($selesaiBulanIni - $prevSelesai) / $prevSelesai) * 100, 1) : null;
        } else {
            // Default: bandingkan bulan ini vs bulan lalu
            $selesaiBulanLalu = Report::where('status','completed')
                ->whereMonth('updated_at', $now->copy()->subMonth()->month)
                ->whereYear('updated_at',  $now->copy()->subMonth()->year)->count();
            $growthSelesai = $selesaiBulanLalu > 0
                ? round((($selesaiBulanIni - $selesaiBulanLalu) / $selesaiBulanLalu) * 100, 1) : null;

            $totalBulanLalu = Report::whereMonth('created_at',$now->copy()->subMonth()->month)
                ->whereYear('created_at',$now->copy()->subMonth()->year)->count();
            $totalBulanIni  = Report::whereMonth('created_at',$now->month)
                ->whereYear('created_at',$now->year)->count();
            $growthTotal = $totalBulanLalu > 0
                ? round((($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100, 1) : null;
        }

        // Rata-rata hari sedang diproses
        $avgHSedang = DB::table('reports')
            ->whereIn('status',['in_progress','waiting_for_materials','under_review'])
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, NOW())) as avg_h')
            ->value('avg_h');
        $avgHariSedang = $avgHSedang ? round($avgHSedang / 24, 1) : 0;

        // Kepuasan
        $avgRating = DB::table('reviews')
            ->join('reports','reports.id','=','reviews.report_id')
            ->when($dateFrom, fn ($q) => $q->whereDate('reviews.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn ($q) => $q->whereDate('reviews.created_at', '<=', $dateTo))
            ->avg('reviews.rating');
        $kepuasan = $avgRating ? round(($avgRating / 5) * 100, 1) : 0;

        // Kepuasan growth
        if (! $dateFrom) {
            $kepuasanLaluRaw = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->whereMonth('reviews.created_at', $now->copy()->subMonth()->month)
                ->whereYear('reviews.created_at',  $now->copy()->subMonth()->year)
                ->avg('reviews.rating');
            $kepuasanLalu   = $kepuasanLaluRaw ? round(($kepuasanLaluRaw / 5) * 100, 1) : null;
            $growthKepuasan = $kepuasanLalu ? round($kepuasan - $kepuasanLalu, 1) : null;
        }

        // ── Top & Poor OPD ───────────────────────────────────────────────
        $allDepts  = Department::with('categories')->get();
        $deptStats = $allDepts->map(function ($dept) use ($dateFrom, $dateTo) {
            $base = fn () => Report::whereHas('category', fn ($q) => $q->where('department_id', $dept->id))
                ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

            $total   = ($base)()->count();
            if ($total === 0) return null;

            $selesai = ($base)()->where('status','completed')->count();
            $rate    = round(($selesai / $total) * 100, 1);

            $avgH = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.status','completed')
                ->when($dateFrom, fn ($q) => $q->whereDate('reports.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reports.created_at', '<=', $dateTo))
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                ->value('avg_h');

            $avgRating = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->when($dateFrom, fn ($q) => $q->whereDate('reviews.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reviews.created_at', '<=', $dateTo))
                ->avg('reviews.rating');

            $abbr = preg_replace('/[^A-Z]/', '', strtoupper($dept->name));
            if (strlen($abbr) > 5) $abbr = substr($abbr, 0, 5);

            return (object) [
                'dept'     => $dept,
                'total'    => $total,
                'selesai'  => $selesai,
                'rate'     => $rate,
                'avgHari'  => $avgH ? round($avgH / 24, 1) : 0,
                'kepuasan' => $avgRating ? round(($avgRating / 5) * 100, 1) : 0,
                'abbr'     => $abbr,
            ];
        })->filter()->sortByDesc('rate');

        $topOPD  = $deptStats->take(3)->values();
        $poorOPD = $deptStats->filter(fn ($d) => $d->rate < 85)->sortBy('rate')->take(2)->values()
            ->map(function ($d) use ($dateFrom, $dateTo) {
                $terlambat = Report::whereHas('category', fn ($q) => $q->where('department_id', $d->dept->id))
                    ->whereNotIn('status',['completed','rejected'])
                    ->whereNotNull('sla_deadline')->where('sla_deadline','<', now())
                    ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
                    ->when($dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
                    ->count();
                $d->terlambat = $terlambat;
                $d->masalah = $d->kepuasan < 85
                    ? 'Kepuasan rendah, perlu evaluasi kualitas layanan'
                    : ($terlambat > 10 ? 'Kekurangan petugas, SLA sering terlampaui' : 'Koordinasi antar unit perlu ditingkatkan');
                return $d;
            });

        // ── Performa per Kecamatan ────────────────────────────────────────
        $districtStats = District::withCount([
            'reports as total_r' => fn ($q) =>
                $q->when($dateFrom, fn ($r) => $r->whereDate('created_at', '>=', $dateFrom))
                  ->when($dateTo,   fn ($r) => $r->whereDate('created_at', '<=', $dateTo)),
            'reports as done_r' => fn ($q) =>
                $q->where('status','completed')
                  ->when($dateFrom, fn ($r) => $r->whereDate('created_at', '>=', $dateFrom))
                  ->when($dateTo,   fn ($r) => $r->whereDate('created_at', '<=', $dateTo)),
        ])->having('total_r','>',0)->get()
        ->map(function ($d) use ($dateFrom, $dateTo, $now) {
            $pct      = $d->total_r > 0 ? round(($d->done_r / $d->total_r) * 100, 1) : 0;
            $prevTotal = Report::where('district_id',$d->id)
                ->whereMonth('created_at',$now->subMonth()->month)->whereYear('created_at',$now->subMonth()->year)->count();
            $prevDone  = Report::where('district_id',$d->id)->where('status','completed')
                ->whereMonth('updated_at',$now->subMonth()->month)->whereYear('updated_at',$now->subMonth()->year)->count();
            $prevPct   = $prevTotal > 0 ? round(($prevDone / $prevTotal) * 100, 1) : null;
            return (object) [
                'id'      => $d->id,
                'name'    => $d->name,
                'total'   => $d->total_r,
                'selesai' => $d->done_r,
                'pct'     => $pct,
                'trend'   => $prevPct !== null ? round($pct - $prevPct, 1) : null,
            ];
        })->sortByDesc('pct')->values();

        // ── Kategori ─────────────────────────────────────────────────────
        $kategoriStats = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->when($dateFrom, fn ($q) => $q->whereDate('reports.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn ($q) => $q->whereDate('reports.created_at', '<=', $dateTo))
            ->selectRaw('categories.name, COUNT(*) as total')
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('total')
            ->limit(5)->get();
        $totalForPct = $totalLaporan;

        $totalOPD     = Department::count();
        $totalPetugas = Employee::where('position','field_officer')->where('status','active')->count();

        return view('regent.dashboard', compact(
            'user','regent',
            'totalLaporan','selesaiBulanIni','growthSelesai',
            'sedangDiproses','avgHariSedang','terlambat',
            'kepuasan','growthKepuasan','growthTotal',
            'topOPD','poorOPD','districtStats',
            'kategoriStats','totalForPct',
            'totalOPD','totalPetugas',
            'dateFrom','dateTo','activePreset',
        ));
    }

    // ── Helper ────────────────────────────────────────────────────────────
    private function parseDateRange(Request $request): array
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : null;
        $dateTo   = $request->filled('date_to')   ? $request->date_to   : null;
        $preset   = $request->get('preset', '');

        if ($preset) {
            $today = Carbon::today();
            match($preset) {
                'today'      => [$dateFrom, $dateTo] = [$today->toDateString(), $today->toDateString()],
                '7days'      => [$dateFrom, $dateTo] = [$today->copy()->subDays(6)->toDateString(), $today->toDateString()],
                '30days'     => [$dateFrom, $dateTo] = [$today->copy()->subDays(29)->toDateString(), $today->toDateString()],
                'this_month' => [$dateFrom, $dateTo] = [$today->copy()->startOfMonth()->toDateString(), $today->toDateString()],
                'last_month' => [$dateFrom, $dateTo] = [
                    $today->copy()->subMonth()->startOfMonth()->toDateString(),
                    $today->copy()->subMonth()->endOfMonth()->toDateString(),
                ],
                'this_year'  => [$dateFrom, $dateTo] = [$today->copy()->startOfYear()->toDateString(), $today->toDateString()],
                default      => null,
            };
        }

        return [$dateFrom, $dateTo, $preset];
    }
}