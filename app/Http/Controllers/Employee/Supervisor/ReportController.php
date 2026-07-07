<?php

namespace App\Http\Controllers\Employee\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // ── Daftar Laporan ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);
        $deptId = $employee->department_id;

        $query = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        )->with([
            'category', 'district', 'tags',
            'evidences'   => fn ($q) => $q->where('file_type', 'photo')->limit(1),
            'assignments' => fn ($q) => $q->with('employee.user'),
            'childReports',
            'user',
        ]);

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('title',    'like', "%{$s}%")
                  ->orWhere('code',   'like', "%{$s}%")
                  ->orWhere('location','like', "%{$s}%")
            );
        }
        if ($request->filled('status'))   $query->where('status',   $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('district')) $query->where('district_id', $request->district);
        if ($request->filled('category')) $query->where('category_id', $request->category);

        // Stat counts (sebelum filter)
        $baseQuery = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $deptId)
        );
        $stats = [
            'total'       => (clone $baseQuery)->count(),
            'unassigned'  => (clone $baseQuery)->whereDoesntHave('assignments')->whereNotIn('status',['completed','rejected'])->count(),
            'inProgress'  => (clone $baseQuery)->whereIn('status', ['in_progress','waiting_for_materials','under_review'])->count(),
            'completed'   => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue'     => (clone $baseQuery)->whereNotIn('status',['completed','rejected'])->whereNotNull('sla_deadline')->where('sla_deadline','<', now())->count(),
        ];

        $reports    = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $districts  = District::orderBy('name')->get();
        $categories = Category::where('department_id', $deptId)->orderBy('name')->get();

        return view('employee.shared.reports.index', compact(
            'reports', 'stats', 'districts', 'categories'
        ));
    }

    // ── Tambah Assignment dari detail laporan ──────────────────────────────
    public function addOfficer(Request $request, string $code)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $report = Report::where('code', $code)->firstOrFail();

        $exists = Assignment::where('report_id',   $report->id)
                            ->where('employee_id', $request->employee_id)
                            ->exists();

        if (! $exists) {
            Assignment::create([
                'report_id'   => $report->id,
                'employee_id' => $request->employee_id,
            ]);
        }

        return back()->with('success', 'Petugas berhasil ditambahkan.');
    }

    // ── Hapus Assignment ──────────────────────────────────────────────────
    public function removeOfficer(Request $request, string $code)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $report = Report::where('code', $code)->firstOrFail();

        Assignment::where('report_id',   $report->id)
                  ->where('employee_id', $request->employee_id)
                  ->delete();

        return back()->with('success', 'Petugas berhasil dihapus dari laporan.');
    }

    // ── Export CSV ────────────────────────────────────────────────────────
    public function export(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403);

        $reports = Report::whereHas('category', fn ($q) =>
            $q->where('department_id', $employee->department_id)
        )->with(['category','district','assignments.employee.user','user'])
         ->orderByDesc('created_at')
         ->get();

        $filename = 'laporan-' . now()->format('Ymd-His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reports) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode','Judul','Status','Prioritas','Kategori','Kecamatan','Pelapor','Petugas','Deadline','Dibuat']);
            foreach ($reports as $r) {
                fputcsv($handle, [
                    $r->code,
                    $r->title,
                    $r->status,
                    $r->priority,
                    $r->category?->name,
                    $r->district?->name,
                    $r->user?->name ?? $r->guest_name,
                    $r->assignments->map(fn ($a) => $a->employee?->user?->name)->filter()->implode(', '),
                    $r->sla_deadline ? Carbon::parse($r->sla_deadline)->format('d/m/Y H:i') : '',
                    $r->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calcSla(Report $report): array
    {
        if (! $report->sla_deadline) {
            return ['text' => '—', 'percent' => 0, 'urgent' => false, 'expired' => false, 'total_hours' => 0];
        }
        $deadline   = Carbon::parse($report->sla_deadline);
        $created    = $report->created_at;
        $totalHours = max(1, $created->diffInHours($deadline));
        $remaining  = now()->diffInHours($deadline, false);
        $percent    = min(100, round(($created->diffInHours(now()) / $totalHours) * 100));
        $expired    = $remaining <= 0;
        $urgent     = ! $expired && $remaining <= 24;

        if ($expired) {
            $oh = abs((int) $remaining);
            if ($oh < 24)       $text = "{$oh} jam terlambat";
            elseif ($oh < 168)  $text = intdiv($oh,24) . ' hari ' . ($oh%24) . ' jam terlambat';
            elseif ($oh < 720)  { $w = intdiv($oh,168); $d = intdiv($oh%168,24); $text = $d ? "{$w} minggu {$d} hari terlambat" : "{$w} minggu terlambat"; }
            else                { $m = intdiv($oh,720);  $d = intdiv($oh%720,24); $text = $d ? "{$m} bulan {$d} hari terlambat" : "{$m} bulan terlambat"; }
        } elseif ($remaining < 1)  { $text = '< 1 jam tersisa'; }
        elseif ($remaining < 24)   { $text = (int)$remaining . ' jam tersisa'; }
        elseif ($remaining < 48)   { $text = '1 hari tersisa'; }
        else                       { $text = ceil($remaining/24) . ' hari tersisa'; }

        return compact('text', 'percent', 'urgent', 'expired', 'total_hours') + ['total_hours' => $totalHours];
    }
}