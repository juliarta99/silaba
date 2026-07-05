<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Employee;
use App\Models\Report;
use App\Models\ReportProgress;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Instansi (department) milik supervisor yang sedang login
        $employee = $request->user()->employee;
        $departmentId = $employee->department_id;
 
        // ----- FILTER -----
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $districtId = $request->input('district_id');
 
        $periodStart = Carbon::parse($startDate)->startOfDay();
        $periodEnd = Carbon::parse($endDate)->endOfDay();
 
        // Periode sebelumnya dihitung dengan panjang rentang yang sama persis,
        // langsung sebelum $periodStart, untuk perbandingan pertumbuhan yang adil.
        $rangeDays = $periodStart->diffInDays($periodEnd) + 1;
        $prevEnd = $periodStart->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($rangeDays - 1)->startOfDay();
 
        $reportBaseQuery = function () use ($departmentId, $districtId) {
            $q = Report::whereHas('category', function ($c) use ($departmentId) {
                $c->where('department_id', $departmentId);
            });
            if ($districtId) {
                $q->where('district_id', $districtId);
            }
            return $q;
        };
 
        // ----- 1. TOTAL LAPORAN BULAN INI + PERTUMBUHAN -----
        $totalThisMonth = $reportBaseQuery()->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalPrevMonth = $reportBaseQuery()->whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $growthPercent = $totalPrevMonth > 0
            ? round((($totalThisMonth - $totalPrevMonth) / $totalPrevMonth) * 100, 1)
            : null;
 
        // ----- 2. TINGKAT PENYELESAIAN -----
        $totalInPeriod = $reportBaseQuery()->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $completedInPeriod = $reportBaseQuery()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('status', 'completed')
            ->count();
        $completionRate = $totalInPeriod > 0 ? round($completedInPeriod / $totalInPeriod * 100, 1) : 0;
 
        // ----- 3. RATA-RATA WAKTU PENYELESAIAN (hari) -----
        // dihitung dari created_at laporan sampai entri report_progress berstatus 'completed'
        $avgHours = $reportBaseQuery()
            ->join('report_progress', function ($join) {
                $join->on('report_progress.report_id', '=', 'reports.id')
                     ->where('report_progress.status', '=', 'completed');
            })
            ->whereBetween('reports.created_at', [$periodStart, $periodEnd])
            ->avg(DB::raw('TIMESTAMPDIFF(HOUR, reports.created_at, report_progress.created_at)'));
        $avgCompletionDays = $avgHours ? round($avgHours / 24, 1) : 0;
 
        // ----- 4. TINGKAT KEPUASAN (dari reviews, skala 1-5 -> persen) -----
        $avgRating = Review::whereHas('report', function ($q) use ($departmentId, $districtId) {
                $q->whereHas('category', fn ($c) => $c->where('department_id', $departmentId));
                if ($districtId) {
                    $q->where('district_id', $districtId);
                }
            })
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->avg('rating');
        $satisfactionRate = $avgRating ? round($avgRating / 5 * 100) : 0;
 
        // ----- 5. TREN LAPORAN 6 BULAN TERAKHIR -----
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $count = $reportBaseQuery()->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $trend[] = [
                'label' => ucfirst($monthStart->translatedFormat('M')),
                'value' => $count,
            ];
        }
 
        // ----- 6. PERFORMA PER KATEGORI -----
        $categoryPerf = Category::where('department_id', $departmentId)
            ->get()
            ->map(function ($cat) use ($periodStart, $periodEnd, $districtId) {
                $q = Report::where('category_id', $cat->id)
                    ->whereBetween('created_at', [$periodStart, $periodEnd]);
                if ($districtId) {
                    $q->where('district_id', $districtId);
                }
                $total = (clone $q)->count();
                $completed = (clone $q)->where('status', 'completed')->count();
 
                return [
                    'name' => $cat->name,
                    'completed' => $completed,
                    'total' => $total,
                    'rate' => $total > 0 ? round($completed / $total * 100) : 0,
                ];
            })
            ->filter(fn ($c) => $c['total'] > 0)
            ->sortByDesc('total')
            ->values();
 
        // ----- 7. PERFORMA PER KECAMATAN -----
        $districtPerf = District::all()
            ->map(function ($d) use ($departmentId, $periodStart, $periodEnd) {
                $q = Report::whereHas('category', fn ($c) => $c->where('department_id', $departmentId))
                    ->where('reports.district_id', $d->id)
                    ->whereBetween('reports.created_at', [$periodStart, $periodEnd]);
 
                $total = (clone $q)->count();
                $completed = (clone $q)->where('status', 'completed')->count();
                $rate = $total > 0 ? round($completed / $total * 100) : 0;
 
                $avgHoursDistrict = (clone $q)
                    ->join('report_progress', function ($join) {
                        $join->on('report_progress.report_id', '=', 'reports.id')
                             ->where('report_progress.status', '=', 'completed');
                    })
                    ->avg(DB::raw('TIMESTAMPDIFF(HOUR, reports.created_at, report_progress.created_at)'));
                $avgDaysDistrict = $avgHoursDistrict ? round($avgHoursDistrict / 24, 1) : 0;
 
                $satisfactionDistrict = Review::whereHas('report', function ($rq) use ($d, $departmentId) {
                        $rq->where('district_id', $d->id)
                           ->whereHas('category', fn ($c) => $c->where('department_id', $departmentId));
                    })
                    ->avg('rating');
                $satisfactionDistrict = $satisfactionDistrict ? round($satisfactionDistrict / 5 * 100) : 0;
 
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'completed' => $completed,
                    'total' => $total,
                    'rate' => $rate,
                    'avg_days' => $avgDaysDistrict,
                    'satisfaction' => $satisfactionDistrict,
                ];
            })
            ->filter(fn ($d) => $d['total'] > 0)
            ->sortByDesc('total')
            ->values();
 
        // ----- 8. LEADERBOARD PETUGAS (field officer) -----
        $leaderboard = Employee::where('department_id', $departmentId)
            ->where('position', 'field_officer')
            ->with('user')
            ->get()
            ->map(function ($emp) use ($periodStart, $periodEnd) {
                $reportIds = $emp->assignments()
                    ->whereBetween('assignments.created_at', [$periodStart, $periodEnd])
                    ->pluck('report_id');
 
                $reports = Report::whereIn('id', $reportIds)->get();
                $total = $reports->count();
                $selesai = $reports->where('status', 'completed')->count();
                $tepatWaktu = $reports->filter(function ($r) {
                    return $r->status === 'completed'
                        && $r->sla_deadline
                        && $r->updated_at <= $r->sla_deadline;
                })->count();
 
                $completedProgress = ReportProgress::where('employee_id', $emp->id)
                    ->where('status', 'completed')
                    ->whereIn('report_id', $reportIds)
                    ->get();
 
                $avgDays = $completedProgress->isNotEmpty()
                    ? round($completedProgress->avg(function ($rp) use ($reports) {
                        $report = $reports->firstWhere('id', $rp->report_id);
                        return $report ? $report->created_at->diffInHours($rp->created_at) / 24 : 0;
                    }), 1)
                    : 0;
 
                $satisfaction = Review::whereIn('report_id', $reportIds)->avg('rating');
 
                return [
                    'name' => $emp->user->name,
                    'total_tugas' => $total,
                    'selesai' => $selesai,
                    'tepat_waktu' => $tepatWaktu,
                    'rata_rata' => $avgDays,
                    'kepuasan' => $satisfaction ? round($satisfaction / 5 * 100) : 0,
                ];
            })
            ->filter(fn ($e) => $e['total_tugas'] > 0)
            ->sortByDesc('selesai')
            ->take(5)
            ->values();
 
        // ----- 9. REVIEW MASYARAKAT (SECTION BARU, DI BAWAH HALAMAN) -----
        $reviews = Review::with(['user', 'report.category', 'report.district'])
            ->whereHas('report', function ($q) use ($departmentId, $districtId) {
                $q->whereHas('category', fn ($c) => $c->where('department_id', $departmentId));
                if ($districtId) {
                    $q->where('district_id', $districtId);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
 
        $reviewSummary = [
            'average' => round(Review::whereHas('report.category', fn ($c) => $c->where('department_id', $departmentId))->avg('rating') ?? 0, 2),
            'total' => Review::whereHas('report.category', fn ($c) => $c->where('department_id', $departmentId))->count(),
            'distribution' => Review::whereHas('report.category', fn ($c) => $c->where('department_id', $departmentId))
                ->select('rating', DB::raw('count(*) as jumlah'))
                ->groupBy('rating')
                ->pluck('jumlah', 'rating'),
        ];
 
        $districtsForFilter = District::orderBy('name')->get();
 
        return view('employee.shared.reviews.index', [
            'totalThisMonth' => $totalThisMonth,
            'growthPercent' => $growthPercent,
            'completionRate' => $completionRate,
            'avgCompletionDays' => $avgCompletionDays,
            'satisfactionRate' => $satisfactionRate,
            'trend' => $trend,
            'categoryPerf' => $categoryPerf,
            'districtPerf' => $districtPerf,
            'leaderboard' => $leaderboard,
            'reviews' => $reviews,
            'reviewSummary' => $reviewSummary,
            'districtsForFilter' => $districtsForFilter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'districtId' => $districtId,
        ]);
    }
 
    /**
     * Export ringkasan performa instansi (bulan berjalan) ke CSV.
     * Ini implementasi sederhana dulu (CSV) — silakan ganti ke PDF/Excel
     * (mis. pakai package barryvdh/laravel-dompdf atau maatwebsite/excel)
     * kalau butuh format laporan resmi.
     */
    public function export(Request $request)
    {
        $employee = $request->user()->employee;
        $departmentId = $employee->department_id;
 
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $districtId = $request->input('district_id');
 
        $periodStart = Carbon::parse($startDate)->startOfDay();
        $periodEnd = Carbon::parse($endDate)->endOfDay();
 
        $query = Report::whereHas('category', fn ($c) => $c->where('department_id', $departmentId))
            ->with(['category', 'district'])
            ->whereBetween('reports.created_at', [$periodStart, $periodEnd]);
 
        if ($districtId) {
            $query->where('reports.district_id', $districtId);
        }
 
        $reports = $query->orderBy('reports.created_at')->get();
 
        $filename = 'performa-instansi-' . $startDate . '_to_' . $endDate . '.csv';
 
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
 
        $callback = function () use ($reports) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode', 'Judul', 'Kategori', 'Kecamatan', 'Status', 'Prioritas', 'Tanggal Masuk']);
 
            foreach ($reports as $r) {
                fputcsv($handle, [
                    $r->code,
                    $r->title,
                    $r->category->name ?? '-',
                    $r->district->name ?? '-',
                    $r->status,
                    $r->priority,
                    $r->created_at->format('d-m-Y H:i'),
                ]);
            }
 
            fclose($handle);
        };
 
        return response()->stream($callback, 200, $headers);
    }
}