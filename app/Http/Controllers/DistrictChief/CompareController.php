<?php

namespace App\Http\Controllers\DistrictChief;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompareController extends Controller
{
    // ── Halaman Komparasi ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user          = Auth::user();
        $districtChief = $user->districtChief;
        abort_if(! $districtChief, 403);

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district?->name ?? '—';

        // ── Parse date range ──────────────────────────────────────────────
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        // ── Data per OPD (department) di kecamatan ini ───────────────────
        // Ambil dept yang punya laporan di kecamatan ini via JOIN langsung
        $deptIds = DB::table('reports')
            ->join('categories', 'categories.id', '=', 'reports.category_id')
            ->where('reports.district_id', $districtId)
            ->when($dateFrom, fn ($q) => $q->whereDate('reports.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn ($q) => $q->whereDate('reports.created_at', '<=', $dateTo))
            ->distinct()
            ->pluck('categories.department_id');

        $depts = Department::whereIn('id', $deptIds)->with('categories')->get();

        $data = $depts->map(function ($dept) use ($districtId, $dateFrom, $dateTo) {
            $base = fn () => Report::where('district_id', $districtId)
                ->whereHas('category', fn ($q) => $q->where('department_id', $dept->id))
                ->when($dateFrom, fn ($r) => $r->whereDate('created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($r) => $r->whereDate('created_at', '<=', $dateTo));

            $total    = ($base)()->count();
            $selesai  = ($base)()->where('status','completed')->count();
            $proses   = ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count();

            $terlambat = ($base)()
                ->whereNotIn('status',['completed','rejected'])
                ->whereNotNull('sla_deadline')
                ->where('sla_deadline','<', now())
                ->count();

            $completionRate = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

            // Avg waktu selesai (jam → hari)
            $avgH = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->where('reports.status','completed')
                ->when($dateFrom, fn ($q) => $q->whereDate('reports.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reports.created_at', '<=', $dateTo))
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                ->value('avg_h');
            $avgDays = $avgH ? round($avgH / 24, 1) : null;

            // Kepuasan
            $avgRating = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->when($dateFrom, fn ($q) => $q->whereDate('reviews.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reviews.created_at', '<=', $dateTo))
                ->avg('reviews.rating');
            $kepuasan = $avgRating ? round(($avgRating / 5) * 100, 1) : null;

            // Petugas aktif & total di kecamatan ini
            $petugasTotal  = Employee::where('department_id', $dept->id)
                ->where('position','field_officer')->count();
            $petugasAktif  = Employee::where('department_id', $dept->id)
                ->where('position','field_officer')
                ->where('status','active')
                ->whereHas('assignments', fn ($q) =>
                    $q->whereHas('report', fn ($r) =>
                        $r->where('district_id', $districtId)
                          ->whereNotIn('status',['completed','rejected'])
                    )
                )->count();

            // Trend vs bulan lalu
            $prevFrom = $dateFrom
                ? Carbon::parse($dateFrom)->subMonth()->toDateString()
                : now()->subMonth()->startOfMonth()->toDateString();
            $prevTo   = $dateFrom
                ? Carbon::parse($dateFrom)->subDay()->toDateString()
                : now()->subMonth()->endOfMonth()->toDateString();

            $prevSelesai = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->where('reports.status','completed')
                ->whereDate('reports.created_at', '>=', $prevFrom)
                ->whereDate('reports.created_at', '<=', $prevTo)
                ->count();
            $prevTotal = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->whereDate('reports.created_at', '>=', $prevFrom)
                ->whereDate('reports.created_at', '<=', $prevTo)
                ->count();
            $prevRate  = $prevTotal > 0 ? round(($prevSelesai / $prevTotal) * 100, 1) : null;
            $trend     = ($prevRate !== null) ? round($completionRate - $prevRate, 1) : null;

            // Singkatan dept
            $abbr = preg_replace('/[^A-Z]/', '', strtoupper($dept->name));
            if (strlen($abbr) > 6) $abbr = substr($abbr, 0, 6);

            return (object) compact(
                'dept', 'total', 'selesai', 'proses', 'terlambat',
                'completionRate', 'avgDays', 'kepuasan',
                'petugasTotal', 'petugasAktif', 'trend', 'abbr'
            );
        })->filter(fn ($d) => $d->total > 0);

        // ── Sort ──────────────────────────────────────────────────────────
        $sortBy  = $request->get('sort_by',  'completion');
        $sortDir = $request->get('sort_dir', 'desc');

        $data = $data->sortBy(
            fn ($d) => match($sortBy) {
                'total'    => $d->total,
                'selesai'  => $d->selesai,
                'waktu'    => $d->avgDays ?? 9999,
                'kepuasan' => $d->kepuasan ?? 0,
                'petugas'  => $d->petugasAktif,
                'trend'    => $d->trend ?? -9999,
                default    => $d->completionRate,
            },
            SORT_REGULAR,
            $sortDir === 'desc' // parameter ketiga = $descending: desc→true, asc→false
        )->values();

        // ── Summary stats ─────────────────────────────────────────────────
        $totalOPD      = $data->count();
        $avgCompletion = $data->avg('completionRate');
        $avgWaktu      = $data->avg(fn ($d) => $d->avgDays);
        $avgKepuasan   = $data->avg(fn ($d) => $d->kepuasan);
        $perluPerhatian = $data->where('completionRate', '<', 85)->count();

        // ── Distribusi performa ────────────────────────────────────────────
        $distribusi = [
            'excellent' => $data->where('completionRate', '>=', 90)->count(),
            'good'      => $data->filter(fn ($d) => $d->completionRate >= 85 && $d->completionRate < 90)->count(),
            'poor'      => $data->where('completionRate', '<', 85)->count(),
        ];

        // ── Insight ───────────────────────────────────────────────────────
        $topPerformer   = $data->first();
        $bottomPerformer = $data->last();
        $gap = $topPerformer && $bottomPerformer
            ? round($topPerformer->completionRate - $bottomPerformer->completionRate, 1)
            : 0;

        return view('district_chief.compare', compact(
            'data', 'districtName', 'districtId',
            'totalOPD', 'avgCompletion', 'avgWaktu', 'avgKepuasan', 'perluPerhatian',
            'distribusi', 'topPerformer', 'bottomPerformer', 'gap',
            'sortBy', 'sortDir',
            'dateFrom', 'dateTo',
        ));
    }

    // ── Export Excel (CSV) ────────────────────────────────────────────────
    public function export(Request $request)
    {
        $user          = Auth::user();
        $districtChief = $user->districtChief;
        abort_if(! $districtChief, 403);

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district?->name ?? 'kecamatan';

        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        // Ambil data sama seperti index
        $deptIds = DB::table('reports')
            ->join('categories', 'categories.id', '=', 'reports.category_id')
            ->where('reports.district_id', $districtId)
            ->distinct()
            ->pluck('categories.department_id');

        $depts = Department::whereIn('id', $deptIds)->get();

        $rows = [];
        foreach ($depts as $dept) {
            $base = fn () => Report::where('district_id', $districtId)
                ->whereHas('category', fn ($q) => $q->where('department_id', $dept->id))
                ->when($dateFrom, fn ($r) => $r->whereDate('created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($r) => $r->whereDate('created_at', '<=', $dateTo));

            $total = ($base)()->count();
            if ($total === 0) continue;

            $selesai  = ($base)()->where('status','completed')->count();
            $proses   = ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count();
            $terlambat = ($base)()->whereNotIn('status',['completed','rejected'])
                ->whereNotNull('sla_deadline')->where('sla_deadline','<', now())->count();
            $rate     = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

            $avgH = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->where('reports.status','completed')
                ->when($dateFrom, fn ($q) => $q->whereDate('reports.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reports.created_at', '<=', $dateTo))
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                ->value('avg_h');
            $avgDays = $avgH ? round($avgH / 24, 1) : 0;

            $avgRating = DB::table('reviews')
                ->join('reports','reports.id','=','reviews.report_id')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId)
                ->when($dateFrom, fn ($q) => $q->whereDate('reviews.created_at', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('reviews.created_at', '<=', $dateTo))
                ->avg('reviews.rating');
            $kepuasan = $avgRating ? round(($avgRating / 5) * 100, 1) : 0;

            $rows[] = [
                $dept->name, $total, $selesai, $proses, $terlambat,
                $rate . '%', $avgDays . ' hari', $kepuasan . '%',
            ];
        }

        $periodLabel = $dateFrom && $dateTo
            ? Carbon::parse($dateFrom)->format('d-m-Y') . '_sd_' . Carbon::parse($dateTo)->format('d-m-Y')
            : now()->format('d-m-Y');
        $filename = 'komparasi-instansi-' . str($districtName)->slug() . '-' . $periodLabel . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 agar Excel terbaca
            fputcsv($h, ['Instansi','Total Laporan','Selesai','Diproses','Terlambat','Completion Rate','Rata-rata Waktu','Kepuasan'], ';');
            foreach ($rows as $row) fputcsv($h, $row, ';');
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ── Helper parse date range ───────────────────────────────────────────
    private function parseDateRange(Request $request): array
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : null;
        $dateTo   = $request->filled('date_to')   ? $request->date_to   : null;

        // Preset
        if ($request->filled('preset')) {
            $today = now();
            match($request->preset) {
                'today'     => [$dateFrom, $dateTo] = [$today->toDateString(), $today->toDateString()],
                '7days'     => [$dateFrom, $dateTo] = [$today->copy()->subDays(6)->toDateString(), $today->toDateString()],
                '30days'    => [$dateFrom, $dateTo] = [$today->copy()->subDays(29)->toDateString(), $today->toDateString()],
                'this_month'=> [$dateFrom, $dateTo] = [$today->copy()->startOfMonth()->toDateString(), $today->toDateString()],
                'last_month'=> [$dateFrom, $dateTo] = [
                    $today->copy()->subMonth()->startOfMonth()->toDateString(),
                    $today->copy()->subMonth()->endOfMonth()->toDateString(),
                ],
                'this_year' => [$dateFrom, $dateTo] = [$today->copy()->startOfYear()->toDateString(), $today->toDateString()],
                default     => null,
            };
        }

        return [$dateFrom, $dateTo];
    }
}