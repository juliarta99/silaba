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

    public function export()
    {
        $departments = Department::with([
            'employees.user',
            'categories' => fn ($q) => $q->withCount('reports')->orderBy('name'),
            'headOfDepartment.user',
        ])
        ->withCount([
            'employees',
            'employees as employees_active_count'   => fn ($q) => $q->where('status', 'active'),
            'employees as employees_supervisor_count'=> fn ($q) => $q->where('position', 'supervisor'),
            'categories',
            'categories as reports_count' => fn ($q) => $q->join('reports', 'categories.id', '=', 'reports.category_id'),
        ])
        ->orderBy('name')
        ->get();

        // Hitung total laporan per dept via query terpisah (lebih akurat)
        $reportCounts = \App\Models\Report::join('categories', 'reports.category_id', '=', 'categories.id')
            ->whereNotNull('categories.department_id')
            ->selectRaw('categories.department_id, COUNT(*) as total,
                        SUM(CASE WHEN reports.status = "pending" THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN reports.status IN ("in_progress","under_review","waiting_for_materials") THEN 1 ELSE 0 END) as progress,
                        SUM(CASE WHEN reports.status = "completed" THEN 1 ELSE 0 END) as completed,
                        SUM(CASE WHEN reports.status = "rejected" THEN 1 ELSE 0 END) as rejected')
            ->groupBy('categories.department_id')
            ->get()
            ->keyBy('department_id');

        $filename = 'data-opd-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($departments, $reportCounts) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ── SECTION 1: Ringkasan OPD ──────────────────────────────────────
            fputcsv($h, ['══ RINGKASAN OPD ══'], ';');
            fputcsv($h, [
                'No', 'Nama OPD', 'Kode', 'Email', 'Telepon',
                'Kepala Dinas',
                'Total Pegawai', 'Pegawai Aktif', 'Supervisor',
                'Jumlah Kategori',
                'Total Laporan', 'Baru', 'Diproses', 'Selesai', 'Ditolak',
            ], ';');

            $no = 1;
            foreach ($departments as $dept) {
                $rc    = $reportCounts->get($dept->id);
                $kepala = $dept->headOfDepartment?->user?->name ?? '(Belum ada)';

                fputcsv($h, [
                    $no++,
                    $dept->name,
                    $dept->code,
                    $dept->email,
                    $dept->phone,
                    $kepala,
                    $dept->employees_count,
                    $dept->employees_active_count,
                    $dept->employees_supervisor_count,
                    $dept->categories_count,
                    $rc?->total    ?? 0,
                    $rc?->pending  ?? 0,
                    $rc?->progress ?? 0,
                    $rc?->completed?? 0,
                    $rc?->rejected ?? 0,
                ], ';');
            }

            // ── SECTION 2: Detail Pegawai per OPD ────────────────────────────
            fputcsv($h, [], ';');
            fputcsv($h, ['══ DETAIL PEGAWAI PER OPD ══'], ';');

            $posLabel = [
                'field_officer'      => 'Petugas Lapangan',
                'supervisor'         => 'Supervisor',
                'head_of_department' => 'Kepala Dinas',
            ];
            $statusLabel = [
                'active'   => 'Aktif',
                'inactive' => 'Nonaktif',
                'on_leave' => 'Cuti',
            ];

            foreach ($departments as $dept) {
                fputcsv($h, [], ';');
                fputcsv($h, ["── {$dept->name} ({$dept->code}) ──"], ';');
                fputcsv($h, ['No', 'Nama Pegawai', 'NIP', 'Posisi', 'Email', 'No. HP', 'Status'], ';');

                if ($dept->employees->count() === 0) {
                    fputcsv($h, ['', '(Belum ada pegawai)', '', '', '', '', ''], ';');
                    continue;
                }

                $no = 1;
                foreach ($dept->employees->sortBy('user.name') as $emp) {
                    fputcsv($h, [
                        $no++,
                        $emp->user?->name ?? '—',
                        $emp->nip,
                        $posLabel[$emp->position] ?? $emp->position,
                        $emp->email,
                        $emp->phone,
                        $statusLabel[$emp->status] ?? $emp->status,
                    ], ';');
                }
            }

            // ── SECTION 3: Kategori per OPD dengan jumlah laporan ────────────
            fputcsv($h, [], ';');
            fputcsv($h, ['══ KATEGORI PER OPD ══'], ';');

            foreach ($departments as $dept) {
                fputcsv($h, [], ';');
                fputcsv($h, ["── {$dept->name} ──"], ';');
                fputcsv($h, ['No', 'Nama Kategori', 'Slug', 'Total Laporan'], ';');

                if ($dept->categories->count() === 0) {
                    fputcsv($h, ['', '(Belum ada kategori)', '', ''], ';');
                    continue;
                }

                $no = 1;
                foreach ($dept->categories as $cat) {
                    fputcsv($h, [
                        $no++,
                        $cat->name,
                        $cat->slug,
                        $cat->reports_count,
                    ], ';');
                }
            }

            fclose($h);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}