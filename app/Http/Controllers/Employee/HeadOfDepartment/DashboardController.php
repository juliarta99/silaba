<?php
namespace App\Http\Controllers\Employee\HeadOfDepartment;
use App\Http\Controllers\Controller;
use App\Models\{Report, Employee, Review};

class DashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;
        $deptId   = $employee->department_id;
        $total      = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$deptId))->count();
        $supervisors= Employee::where('department_id',$deptId)->where('position','supervisor')->count();
        $fieldOfficers = Employee::where('department_id',$deptId)->where('position','field_officer')->count();
        $avgRating  = Review::whereHas('report.assignment.employee', fn($q)=>$q->where('department_id',$deptId))->avg('rating');
        $slaTarget  = 7;
        // TODO: hitung SLA compliance
        return view('employee.head-of-department.dashboard', compact('employee','total','supervisors','fieldOfficers','avgRating','slaTarget'));
    }
}
