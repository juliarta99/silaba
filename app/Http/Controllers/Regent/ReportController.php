<?php
namespace App\Http\Controllers\Regent;
use App\Http\Controllers\Controller;
use App\Models\{Report, Category, Department};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['category','district','assignment.employee.department']);
        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category_id', $request->category);
        if ($request->dept)     $query->whereHas('assignment.employee', fn($q)=>$q->where('department_id',$request->dept));
        $reports    = $query->latest()->paginate(20);
        $categories = Category::all();
        $departments= Department::all();
        return view('regent.reports.index', compact('reports','categories','departments'));
    }

    public function show(Report $report)
    {
        $report->load(['category','district','progresses.employee.user','assignment.employee.department','evidences','review']);
        return view('regent.reports.show', compact('report'));
    }

    public function map()
    {
        $reports    = Report::whereNotNull('latitude')->with(['category','district'])->get();
        $categories = Category::all();
        return view('regent.reports.map', compact('reports','categories'));
    }

    public function priority()
    {
        // Laporan pending terlama
        $urgentReports = Report::where('status','pending')
            ->orderBy('created_at')->take(10)->with(['category','district'])->get();
        // OPD dengan SLA miss terbanyak — TODO: hitung real SLA
        $underperforming = Department::take(5)->get();
        // Kategori paling banyak laporan pending
        $topCategories = Category::withCount(['reports' => fn($q)=>$q->where('status','pending')])
            ->orderByDesc('reports_count')->take(5)->get();
        return view('regent.reports.priority', compact('urgentReports','underperforming','topCategories'));
    }
}
