<?php

namespace App\Http\Controllers\Regent;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403);

        // ── Base query: SEMUA laporan se-Kabupaten Badung ─────────────────
        $query = Report::with([
            'category', 'district', 'tags',
            'evidences'   => fn ($q) => $q->where('file_type','photo')->limit(1),
            'assignments' => fn ($q) => $q->with('employee.user'),
            'childReports',
            'user',
        ]);

        // ── Filters ───────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('title',    'like', "%{$s}%")
                  ->orWhere('code',   'like', "%{$s}%")
                  ->orWhere('location','like', "%{$s}%")
            );
        }
        if ($request->filled('status'))   $query->where('status',      $request->status);
        if ($request->filled('priority')) $query->where('priority',    $request->priority);
        if ($request->filled('district')) $query->where('district_id', $request->district);
        if ($request->filled('category')) $query->where('category_id', $request->category);

        // ── Stats (tanpa filter, seluruh kabupaten) ───────────────────────
        $stats = [
            'total'      => Report::count(),
            'unassigned' => Report::whereDoesntHave('assignments')
                                  ->whereNotIn('status',['completed','rejected'])->count(),
            'inProgress' => Report::whereIn('status',['in_progress','waiting_for_materials','under_review'])->count(),
            'completed'  => Report::where('status','completed')->count(),
            'overdue'    => Report::whereNotIn('status',['completed','rejected'])
                                  ->whereNotNull('sla_deadline')
                                  ->where('sla_deadline','<', now())->count(),
        ];

        $reports    = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();

        return view('regent.reports.index', compact(
            'reports', 'stats', 'categories', 'districts'
        ));
    }

    public function show(string $code)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403);

        $report = Report::where('code', $code)
            ->with([
                'category.department', 'district', 'tags', 'user',
                'evidences',
                'assignments.employee.user',
                'progresses.employee.user',
                'review',
                'childReports',
            ])
            ->firstOrFail();

        return view('regent.reports.show', compact('report'));
    }

    public function export(Request $request)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403);

        $query = Report::with(['category','district','assignments.employee.user','user'])
            ->orderByDesc('created_at');

        if ($request->filled('status'))   $query->where('status',      $request->status);
        if ($request->filled('priority')) $query->where('priority',    $request->priority);
        if ($request->filled('district')) $query->where('district_id', $request->district);
        if ($request->filled('category')) $query->where('category_id', $request->category);

        $reports  = $query->get();
        $filename = 'laporan-kabupaten-badung-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($reports) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($h, [
                'Kode','Judul','Status','Prioritas','Kategori',
                'Kecamatan','Pelapor','Petugas','Deadline SLA','Dibuat',
            ], ';');
            foreach ($reports as $r) {
                fputcsv($h, [
                    $r->code,
                    $r->title,
                    $r->status,
                    $r->priority,
                    $r->category?->name ?? '—',
                    $r->district?->name ?? '—',
                    $r->user?->name ?? ($r->guest_name ?? 'Tamu'),
                    $r->assignments->map(fn ($a) => $a->employee?->user?->name)->filter()->implode(', ') ?: '—',
                    $r->sla_deadline ? Carbon::parse($r->sla_deadline)->format('d/m/Y H:i') : '—',
                    $r->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}