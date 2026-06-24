<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\{Report, Assignment};

class DashboardController extends Controller
{
    public function index()
    {
        $employee   = auth()->user()->employee;
        $deptId     = $employee->department_id;
        $total      = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))->count();
        $pending    = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                        ->where('status','pending')->count();
        $inProgress = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                        ->where('status','in_progress')->count();
        $completed  = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                        ->where('status','completed')->count();
        return view('employee.supervisor.dashboard', compact('employee','total','pending','inProgress','completed'));
    }
}
