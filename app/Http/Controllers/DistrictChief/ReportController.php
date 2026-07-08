<?php

namespace App\Http\Controllers\DistrictChief;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user          = Auth::user();
        $districtChief = $user->districtChief;
        abort_if(! $districtChief, 403);

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district?->name ?? '—';

        // ── Base query: laporan di kecamatan ini saja ─────────────────────
        $query = Report::where('district_id', $districtId)
            ->with([
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
                $q->where('title',   'like', "%{$s}%")
                  ->orWhere('code',  'like', "%{$s}%")
                  ->orWhere('location','like', "%{$s}%")
            );
        }
        if ($request->filled('status'))   $query->where('status',      $request->status);
        if ($request->filled('priority')) $query->where('priority',    $request->priority);
        if ($request->filled('category')) $query->where('category_id', $request->category);

        // ── Stats (tanpa filter, base district saja) ─────────────────────
        $base   = fn () => Report::where('district_id', $districtId);
        $stats  = [
            'total'      => ($base)()->count(),
            'unassigned' => ($base)()->whereDoesntHave('assignments')
                                     ->whereNotIn('status',['completed','rejected'])->count(),
            'inProgress' => ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count(),
            'completed'  => ($base)()->where('status','completed')->count(),
            'overdue'    => ($base)()->whereNotIn('status',['completed','rejected'])
                                     ->whereNotNull('sla_deadline')
                                     ->where('sla_deadline','<', now())->count(),
        ];

        $reports    = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('district_chief.reports.index', compact(
            'reports', 'stats', 'categories', 'districtName'
        ));
    }
}