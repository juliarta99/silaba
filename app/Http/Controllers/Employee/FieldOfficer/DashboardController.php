<?php
namespace App\Http\Controllers\Employee\FieldOfficer;
use App\Http\Controllers\Controller;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $employee    = auth()->user()->employee;
        $activeCount = Assignment::where('employee_id', $employee->id)->whereIn('status',['assigned','in_progress'])->count();
        $doneToday   = Assignment::where('employee_id', $employee->id)->where('status','completed')
                          ->whereDate('updated_at', today())->count();
        $prioritized = Assignment::where('employee_id', $employee->id)
                          ->whereIn('status',['assigned','in_progress'])
                          ->with(['report.category','report.district'])
                          ->orderByDesc('created_at')->take(3)->get();
        $recent      = Assignment::where('employee_id', $employee->id)
                          ->with(['report'])->latest()->take(5)->get();
        return view('employee.field-officer.dashboard', compact('employee','activeCount','doneToday','prioritized','recent'));
    }
}
