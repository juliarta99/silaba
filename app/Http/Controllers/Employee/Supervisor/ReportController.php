<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function deptScope()
    {
        return auth()->user()->employee->department_id;
    }

    public function index(Request $request)
    {
        $deptId = $this->deptScope();
        $query  = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                    ->with(['category','district','assignment.employee.user']);
        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category_id', $request->category);
        if ($request->search)   $query->where('title', 'like', "%{$request->search}%");
        $reports = $query->latest()->paginate(15);
        return view('employee.shared.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['category','district','evidences','progresses.employee.user','assignment.employee.user']);
        return view('employee.shared.reports.show', compact('report'));
    }

    public function map()
    {
        $deptId  = $this->deptScope();
        $reports = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                    ->whereNotNull('latitude')->with(['category','district'])->get();
        return view('employee.shared.reports.map', compact('reports'));
    }
}
