<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Department, Employee};
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->get();
        return view('admin.departments.index', compact('departments'));
    }
    public function create() { return view('admin.departments.create'); }
    public function store(Request $r)
    {
        $r->validate(['name'=>'required|unique:departments','code'=>'nullable']);
        Department::create($r->only('name','code','head_name','address','phone','email'));
        return redirect()->route('admin.departments.index')->with('success','OPD ditambahkan.');
    }
    public function show(Department $department)
    {
        $department->load(['employees.user']);
        $supervisors   = $department->employees->where('position','supervisor');
        $fieldOfficers = $department->employees->where('position','field_officer');
        return view('admin.departments.show', compact('department','supervisors','fieldOfficers'));
    }
    public function edit(Department $department) { return view('admin.departments.edit', compact('department')); }
    public function update(Request $r, Department $department)
    {
        $department->update($r->only('name','code','head_name','address','phone','email'));
        return redirect()->route('admin.departments.index')->with('success','OPD diperbarui.');
    }
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success','OPD dihapus.');
    }
}
