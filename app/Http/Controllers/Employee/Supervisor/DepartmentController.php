<?php

namespace App\Http\Controllers\Employee\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * Ambil data pegawai (employee) milik user yang sedang login.
     * Dipakai di semua method supaya konsisten & aman (tidak percaya input dari client).
     */
    protected function currentEmployee(): Employee
    {
        return Auth::user()->employee;
    }

    /**
     * Pastikan pegawai yang login adalah Kepala Dinas / Supervisor.
     * Kalau bukan salah satu dari itu, tolak akses.
     */
    protected function assertCanManage(Employee $me): void
    {
        abort_unless(in_array($me->position, ['head_of_department', 'supervisor']), 403);
    }

    /**
     * Halaman utama "Kelola Instansi".
     * Tampilan & data yang dikirim menyesuaikan posisi user (kadis vs supervisor).
     */
    public function index()
    {
        $me = $this->currentEmployee();
        $this->assertCanManage($me);

        $isKadis = $me->position === 'head_of_department';
        $department = $me->department;

        // Kepala Dinas & Supervisor aktif di instansi ini (untuk kartu "Informasi Instansi")
        $kepalaDinas = Employee::with('user')
            ->where('department_id', $department->id)
            ->where('position', 'head_of_department')
            ->first();

        $supervisorList = Employee::with('user')
            ->where('department_id', $department->id)
            ->where('position', 'supervisor')
            ->orderBy('created_at')
            ->get();

        $officerList = Employee::with('user')
            ->where('department_id', $department->id)
            ->where('position', 'field_officer')
            ->orderBy('created_at')
            ->get();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        // Statistik tugas & "spesialisasi" (kategori laporan yang paling sering ditangani) per petugas.
        $officerIds = $officerList->pluck('id');
        $assignmentStats = DB::table('assignments')
            ->join('reports', 'reports.id', '=', 'assignments.report_id')
            ->join('categories', 'categories.id', '=', 'reports.category_id')
            ->whereIn('assignments.employee_id', $officerIds)
            ->select(
                'assignments.employee_id',
                'categories.name as category_name',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when reports.status in ('pending','in_progress','under_review','waiting_for_materials') then 1 else 0 end) as active_total")
            )
            ->groupBy('assignments.employee_id', 'categories.name')
            ->get()
            ->groupBy('employee_id');

        $officersData = $officerList->map(function (Employee $officer) use ($assignmentStats) {
            $stats = $assignmentStats->get($officer->id, collect());
            $totalTasks = (int) $stats->sum('total');
            $activeTasks = (int) $stats->sum('active_total');
            $specializations = $stats->sortByDesc('total')->take(2)->pluck('category_name')->values();

            return [
                'id' => $officer->id,
                'name' => $officer->user->name,
                'nip' => $officer->nip,
                'email' => $officer->email,
                'phone' => $officer->phone,
                'status' => $officer->status,
                'statusLabel' => $this->statusLabel($officer->status),
                'joined' => optional($officer->created_at)->translatedFormat('d M Y'),
                'specializations' => $specializations,
                'activeTasks' => $activeTasks,
                'totalTasks' => $totalTasks,
                'picture' => $officer->user->picture ? asset('storage/' . $officer->user->picture) : null,
            ];
        })->values();

        $supervisorsData = $supervisorList->map(function (Employee $supervisor) {
            return [
                'id' => $supervisor->id,
                'name' => $supervisor->user->name,
                'nip' => $supervisor->nip,
                'email' => $supervisor->email,
                'phone' => $supervisor->phone,
                'status' => $supervisor->status,
                'statusLabel' => $this->statusLabel($supervisor->status),
                'joined' => optional($supervisor->created_at)->translatedFormat('d M Y'),
                'picture' => $supervisor->user->picture ? asset('storage/' . $supervisor->user->picture) : null,
            ];
        })->values();

        return view('employee.shared.departments.index', [
            'isKadis' => $isKadis,
            'department' => $department,
            'kepalaDinas' => $kepalaDinas,
            'categories' => $categories,
            'supervisorsData' => $supervisorsData,
            'officersData' => $officersData,
        ]);
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Aktif',
            'on_leave' => 'Cuti',
            'inactive' => 'Nonaktif',
            default => ucfirst($status),
        };
    }

    /**
     * Update informasi instansi + data Kepala Dinas.
     * Hanya Kepala Dinas yang boleh melakukan ini.
     */
    public function updateDepartment(Request $request)
    {
        $me = $this->currentEmployee();
        abort_unless($me->position === 'head_of_department', 403, 'Hanya Kepala Dinas yang dapat mengubah informasi instansi.');

        $department = $me->department;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'email' => ['required', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'kadis_name' => ['required', 'string', 'max:100'],
            'kadis_nip' => [
                'required', 'string', 'max:20',
                Rule::unique('users', 'identifier')->ignore($me->user_id),
            ],
        ]);

        DB::transaction(function () use ($validated, $department, $me) {
            $department->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'] ?? null,
            ]);

            $me->user()->update([
                'name' => $validated['kadis_name'],
                'identifier' => $validated['kadis_nip'],
            ]);
        });

        return back()->with('success', 'Informasi instansi berhasil diperbarui.');
    }

    /**
     * Tambah pegawai baru (supervisor atau petugas lapangan), tergantung siapa yang mengakses:
     * - Kepala Dinas -> boleh menambah 'supervisor' atau 'field_officer'
     * - Supervisor   -> hanya boleh menambah 'field_officer'
     */
    public function storeEmployee(Request $request)
    {
        $me = $this->currentEmployee();
        $this->assertCanManage($me);

        $allowedPositions = $me->position === 'head_of_department'
            ? ['supervisor', 'field_officer']
            : ['field_officer'];

        $validated = $request->validate([
            'position' => ['required', Rule::in($allowedPositions)],
            'name' => ['required', 'string', 'max:100'],
            'nip' => ['required', 'string', 'max:20', 'unique:users,identifier'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'status' => ['required', Rule::in(['active', 'on_leave', 'inactive'])],
        ]);

        DB::transaction(function () use ($validated, $me) {
            $user = User::create([
                'name' => $validated['name'],
                'identifier' => $validated['nip'],
                'identifier_type' => 'nip',
                'password' => Hash::make($validated['nip']), // password awal = NIP, wajib diganti saat login pertama
                'role' => 'employee',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => $validated['status'],
                'position' => $validated['position'],
                'department_id' => $me->department_id,
            ]);
        });

        $label = $validated['position'] === 'supervisor' ? 'Supervisor' : 'Petugas';

        return back()->with('success', "{$label} baru berhasil ditambahkan.");
    }

    /**
     * Ubah data pegawai (supervisor atau petugas), dengan aturan hak akses yang sama seperti storeEmployee.
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $me = $this->currentEmployee();
        $this->assertCanManage($me);

        // Harus satu instansi yang sama
        abort_unless($employee->department_id === $me->department_id, 403);

        // Supervisor hanya boleh mengubah petugas lapangan, bukan sesama supervisor / kadis
        if ($me->position === 'supervisor') {
            abort_unless($employee->position === 'field_officer', 403, 'Anda hanya dapat mengelola petugas lapangan.');
        } else {
            // Kadis tidak mengedit dirinya sendiri lewat form ini
            abort_if($employee->id === $me->id, 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'nip' => ['required', 'string', 'max:20', Rule::unique('users', 'identifier')->ignore($employee->user_id)],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee->id)],
            'status' => ['required', Rule::in(['active', 'on_leave', 'inactive'])],
        ]);

        DB::transaction(function () use ($validated, $employee) {
            $employee->user()->update([
                'name' => $validated['name'],
                'identifier' => $validated['nip'],
            ]);

            $employee->update([
                'nip' => $validated['nip'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);
        });

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus pegawai (supervisor atau petugas), dengan aturan hak akses yang sama.
     */
    public function destroyEmployee(Employee $employee)
    {
        $me = $this->currentEmployee();
        $this->assertCanManage($me);

        abort_unless($employee->department_id === $me->department_id, 403);

        if ($me->position === 'supervisor') {
            abort_unless($employee->position === 'field_officer', 403, 'Anda hanya dapat mengelola petugas lapangan.');
        } else {
            abort_if($employee->id === $me->id, 403, 'Tidak dapat menghapus akun sendiri.');
        }

        // Cegah hapus jika masih ada tugas aktif berjalan, supaya data laporan tidak yatim.
        $hasActiveAssignment = DB::table('assignments')
            ->join('reports', 'reports.id', '=', 'assignments.report_id')
            ->where('assignments.employee_id', $employee->id)
            ->whereIn('reports.status', ['pending', 'in_progress', 'under_review', 'waiting_for_materials'])
            ->exists();

        if ($hasActiveAssignment) {
            throw ValidationException::withMessages([
                'employee' => 'Tidak dapat menghapus pegawai yang masih memiliki tugas aktif. Alihkan tugasnya terlebih dahulu.',
            ]);
        }

        DB::transaction(function () use ($employee) {
            $userId = $employee->user_id;
            $employee->delete();
            User::whereKey($userId)->delete();
        });

        return back()->with('success', 'Data berhasil dihapus.');
    }
}