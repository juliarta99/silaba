<?php
namespace App\Http\Controllers\Employee\FieldOfficer;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ReportProgress;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    private function myAssignment(int $id): Assignment
    {
        $employee = auth()->user()->employee;
        return Assignment::where('employee_id', $employee->id)->findOrFail($id);
    }

    public function index()
    {
        $employee    = auth()->user()->employee;
        $assignments = Assignment::where('employee_id', $employee->id)
            ->with(['report.category','report.district'])
            ->latest()->paginate(10);
        return view('employee.field-officer.assignments.index', compact('assignments'));
    }

    public function show(int $id)
    {
        $assignment = $this->myAssignment($id);
        $assignment->load(['report.category','report.district','report.evidences',
                           'report.progresses.employee.user']);
        return view('employee.field-officer.assignments.show', compact('assignment'));
    }

    public function updateProgress(int $id)
    {
        $assignment = $this->myAssignment($id);
        return view('employee.field-officer.assignments.update_progress', compact('assignment'));
    }

    public function storeProgress(Request $request, int $id)
    {
        $assignment = $this->myAssignment($id);
        $request->validate([
            'notes'  => 'required|string|max:1000',
            'status' => 'required|in:in_progress,completed',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:4096',
        ]);

        $progress = ReportProgress::create([
            'report_id'   => $assignment->report_id,
            'employee_id' => auth()->user()->employee->id,
            'notes'       => $request->notes,
            'status'      => $request->status,
        ]);

        // TODO: upload photos → ReportEvidence
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('evidences', 'public');
                // ReportEvidence::create(['report_id' => $assignment->report_id, 'photo_url' => $path]);
            }
        }

        // Update report status
        $assignment->report->update(['status' => $request->status]);
        if ($request->status === 'completed') {
            $assignment->update(['status' => 'completed']);
        }

        return redirect()->route('employee.field-officer.assignments.show', $id)
            ->with('success', 'Progress berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $assignment = $this->myAssignment($id);
        $request->validate(['status' => 'required|in:assigned,in_progress,completed']);
        $assignment->update(['status' => $request->status]);
        return back()->with('success', 'Status diperbarui.');
    }
}
