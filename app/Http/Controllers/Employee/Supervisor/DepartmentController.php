<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\{Department, Employee};
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private function myDept(): Department
    {
        return auth()->user()->employee->department;
    }

    public function index()
    {
        $department = $this->myDept()->load(['employees.user']);
        $supervisors = $department->employees->where('position','supervisor');
        $fieldOfficers = $department->employees->where('position','field_officer');
        return view('employee.shared.departments.index', compact('department','supervisors','fieldOfficers'));
    }

    public function show(Department $department)
    {
        $department->load(['employees.user']);
        return view('employee.shared.departments.show', compact('department'));
    }

    public function store(Request $request)
    {
        // HoD: tambah petugas/supervisor ke departemen sendiri
        $request->validate(['user_id' => 'required|exists:users,id', 'position' => 'required|in:field_officer,supervisor', 'nip' => 'required|unique:employees,nip', 'phone' => 'required', 'email' => 'required|email']);
        Employee::create(array_merge($request->all(), ['department_id' => $this->myDept()->id, 'status' => 'active']));
        return back()->with('success','Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {
        $department->update($request->only(['name','address','phone','email']));
        return back()->with('success','Instansi diperbarui.');
    }

    public function destroy(Department $department)
    {
        // Soft: nonaktifkan semua petugas → jarang dipakai
        abort(403, 'Tidak diizinkan menghapus instansi.');
    }
}
