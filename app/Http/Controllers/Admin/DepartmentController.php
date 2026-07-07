<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount([
            'employees as total_petugas' => fn ($q) =>
                $q->where('position', 'field_officer')->where('status', 'active'),
            'categories',
        ])->with([
            'headOfDepartment.user', // relasi ke employee position=head_of_department aktif
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhereHas('headOfDepartment.user', fn ($r) =>
                      $r->where('name', 'like', "%{$s}%")
                  )
            );
        }

        $departments = $query->orderBy('name')->get()
            ->map(function ($dept) {
                $dept->has_head = $dept->headOfDepartment !== null;
                return $dept;
            });

        $stats = [
            'total'         => $departments->count(),
            'total_petugas' => $departments->sum('total_petugas'),
            'aktif'         => $departments->where('has_head', true)->count(),
            'belum_kadis'   => $departments->where('has_head', false)->count(),
        ];

        // Daftar user yang bisa dijadikan Kadis (employee head_of_dept tanpa dept atau dari dept ini)
        $candidateUsers = User::where('role', 'employee')
            ->whereHas('employee', fn ($q) =>
                $q->where('position', 'head_of_department')
                  ->where('status', 'active')
            )
            ->with('employee.department')
            ->get();

        return view('admin.departments.index', compact(
            'departments', 'stats', 'candidateUsers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100|unique:departments,name',
            'code'    => 'required|string|max:100|unique:departments,code',
            'phone'   => 'required|string|max:25|unique:departments,phone',
            'email'   => 'required|email|max:100|unique:departments,email',
            'address' => 'nullable|string|max:255',
        ], [
            'name.unique'  => 'Nama OPD sudah terdaftar.',
            'code.unique'  => 'Kode OPD sudah digunakan.',
            'phone.unique' => 'Nomor telepon sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        Department::create([
            'name'    => $request->name,
            'code'    => strtoupper($request->code),
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        return back()->with('success', "OPD {$request->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'    => ['required','string','max:100', Rule::unique('departments','name')->ignore($department->id)],
            'code'    => ['required','string','max:100', Rule::unique('departments','code')->ignore($department->id)],
            'phone'   => ['required','string','max:25',  Rule::unique('departments','phone')->ignore($department->id)],
            'email'   => ['required','email','max:100',  Rule::unique('departments','email')->ignore($department->id)],
            'address' => 'nullable|string|max:255',
        ]);

        $department->update([
            'name'    => $request->name,
            'code'    => strtoupper($request->code),
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        return back()->with('success', "OPD {$department->name} berhasil diperbarui.");
    }

    public function destroy(Department $department)
    {
        $activeReports = $department->categories()
            ->withCount(['reports as active_count' => fn ($q) =>
                $q->whereNotIn('status', ['completed','rejected'])
            ])
            ->get()
            ->sum('active_count');

        if ($activeReports > 0) {
            return back()->withErrors([
                'delete' => "OPD {$department->name} tidak dapat dihapus karena masih memiliki {$activeReports} laporan aktif.",
            ]);
        }

        $name = $department->name;
        $department->delete();
        return back()->with('success', "OPD {$name} berhasil dihapus.");
    }

    // ── Tugaskan / Ubah Kadis ─────────────────────────────────────────────
    public function assignHead(Request $request, Department $department)
    {
        $request->validate([
            'mode'          => ['required', Rule::in(['existing', 'new'])],
            // Mode existing: pilih employee user yang sudah ada
            'employee_user_id' => 'required_if:mode,existing|nullable|exists:users,id',
            // Mode new: buat user Kadis baru
            'name'          => 'required_if:mode,new|nullable|string|max:100',
            'nip'           => 'required_if:mode,new|nullable|string|max:20|unique:employees,nip',
            'identifier'    => 'required_if:mode,new|nullable|string|max:50|unique:users,identifier',
            'email'         => 'required_if:mode,new|nullable|email|unique:employees,email',
            'phone'         => 'required_if:mode,new|nullable|string|max:20|unique:employees,phone',
            'password'      => 'required_if:mode,new|nullable|string|min:8',
        ], [
            'nip.unique'        => 'NIP sudah terdaftar.',
            'identifier.unique' => 'NIP/Username sudah digunakan di akun.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->mode === 'existing') {
                // Pindahkan employee ke dept ini & set position head_of_department
                $user = User::findOrFail($request->employee_user_id);
                $emp  = $user->employee;
                abort_if(! $emp, 422, 'User tidak memiliki data pegawai.');

                // Nonaktifkan Kadis lama di dept ini (jika ada)
                Employee::where('department_id', $department->id)
                    ->where('position', 'head_of_department')
                    ->update(['position' => 'supervisor']); // turunkan ke supervisor

                $emp->update([
                    'department_id' => $department->id,
                    'position'      => 'head_of_department',
                    'status'        => 'active',
                ]);

            } else {
                // Buat user & employee Kadis baru
                // Nonaktifkan Kadis lama
                Employee::where('department_id', $department->id)
                    ->where('position', 'head_of_department')
                    ->update(['position' => 'supervisor']);

                $user = User::create([
                    'name'            => $request->name,
                    'role'            => 'employee',
                    'identifier'      => $request->nip,
                    'identifier_type' => 'nip',
                    'password'        => Hash::make($request->password),
                ]);

                Employee::create([
                    'user_id'       => $user->id,
                    'nip'           => $request->nip,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'position'      => 'head_of_department',
                    'department_id' => $department->id,
                    'status'        => 'active',
                ]);
            }

            DB::commit();
            return back()->with('success', "Kepala Dinas {$department->name} berhasil diperbarui.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['head_error' => 'Gagal menugaskan Kadis: ' . $e->getMessage()])
                ->with('modal_dept_id', $department->id);
        }
    }
}