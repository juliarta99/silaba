<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\{Assignment, Report, Employee};
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $deptId     = auth()->user()->employee->department_id;
        $unassigned = Report::whereDoesntHave('assignment')
                        ->with(['category','district'])->latest()->get();
        $assignments = Assignment::whereHas('employee', fn($q)=>$q->where('department_id',$deptId))
                        ->with(['report','employee.user'])->latest()->paginate(15);
        return view('employee.shared.assignments.index', compact('unassigned','assignments'));
    }

    public function store(Request $request)
    {
        $request->validate(['report_id' => 'required|exists:reports,id', 'employee_id' => 'required|exists:employees,id']);
        $deptId = auth()->user()->employee->department_id;
        $employee = Employee::where('id',$request->employee_id)->where('department_id',$deptId)
                     ->where('position','field_officer')->firstOrFail();
        Assignment::create(['report_id' => $request->report_id, 'employee_id' => $employee->id, 'status' => 'assigned']);
        Report::find($request->report_id)->update(['status' => 'in_progress']);
        // TODO: kirim notifikasi ke field officer
        return redirect()->route('employee.shared.assignments.index')->with('success','Laporan berhasil ditugaskan.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['report.category','report.district','report.progresses.employee.user','employee.user']);
        return view('employee.shared.assignments.show', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate(['employee_id' => 'nullable|exists:employees,id', 'status' => 'nullable|in:assigned,in_progress,completed']);
        $assignment->update($request->only(['employee_id','status']));
        return back()->with('success','Penugasan diperbarui.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->update(['status' => 'cancelled']);
        $assignment->report->update(['status' => 'pending']);
        return redirect()->route('employee.shared.assignments.index')->with('success','Penugasan dibatalkan.');
    }
}
