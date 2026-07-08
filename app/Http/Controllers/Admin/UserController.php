<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Employee;
use App\Models\District_Chief;
use App\Models\Regent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isSuperAdmin = $authUser->role === 'super_admin';

        // Statistik per role
        $stats = [
            'citizen'        => User::where('role','citizen')->count(),
            'employee'       => User::where('role','employee')->count(),
            'supervisor'     => User::where('role','employee')
                ->whereHas('employee', fn ($q) => $q->where('position','supervisor'))->count(),
            'head_of_dept'   => User::where('role','employee')
                ->whereHas('employee', fn ($q) => $q->where('position','head_of_department'))->count(),
            'district_chief' => User::where('role','district_chief')->count(),
            'regent'         => User::where('role','regent')->count(),
            'admin'          => User::where('role','admin')->count(),
            'super_admin'    => User::where('role','super_admin')->count(),
        ];

        // Query utama
        $query = User::with([
            'employee.department',
            'districtChief.district',
            'regent',
        ]);

        // Super admin tidak terlihat oleh admin biasa
        if (! $isSuperAdmin) {
            $query->where('role', '!=', 'super_admin');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('name',       'like', "%{$s}%")
                  ->orWhere('identifier','like', "%{$s}%")
                  ->orWhereHas('employee',      fn ($r) => $r->where('email','like',"%{$s}%")->orWhere('nip','like',"%{$s}%"))
                  ->orWhereHas('districtChief',fn ($r) => $r->where('email','like',"%{$s}%")->orWhere('nip','like',"%{$s}%"))
                  ->orWhereHas('regent',         fn ($r) => $r->where('email','like',"%{$s}%")->orWhere('nip','like',"%{$s}%"))
            );
        }

        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'supervisor') {
                $query->where('role','employee')
                      ->whereHas('employee', fn ($q) => $q->where('position','supervisor'));
            } elseif ($role === 'field_officer') {
                $query->where('role','employee')
                      ->whereHas('employee', fn ($q) => $q->where('position','field_officer'));
            } elseif ($role === 'head_of_department') {
                $query->where('role','employee')
                      ->whereHas('employee', fn ($q) => $q->where('position','head_of_department'));
            } else {
                $query->where('role', $role);
            }
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->where(fn ($q) =>
                $q->whereHas('employee',       fn ($r) => $r->where('status', $status))
                  ->orWhereHas('districtChief',fn ($r) => $r->where('status', $status))
                  ->orWhereHas('regent',         fn ($r) => $r->where('status', $status))
            );
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $departments = Department::orderBy('name')->get();
        $districts   = District::orderBy('name')->get();

        return view('admin.users.index', compact(
            'users', 'stats', 'departments', 'districts', 'isSuperAdmin'
        ));
    }

    // ── Create form ───────────────────────────────────────────────────────
    public function create()
    {
        $isSuperAdmin = Auth::user()->role === 'super_admin';
        $departments  = Department::orderBy('name')->get();
        $districts    = District::orderBy('name')->get();
        return view('admin.users.create', compact('isSuperAdmin','departments','districts'));
    }

    // ── Store ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $isSuperAdmin = Auth::user()->role === 'super_admin';

        // Admin biasa TIDAK bisa tambah admin/super_admin
        // Hanya super_admin yang bisa tambah admin & super_admin
        $allowedRoles = ['citizen','employee','district_chief','regent'];
        if ($isSuperAdmin) {
            $allowedRoles[] = 'admin';
            $allowedRoles[] = 'super_admin';
        }

        $request->validate([
            'name'       => 'required|string|max:100',
            'role'       => ['required', Rule::in($allowedRoles)],
            'identifier' => 'required|string|max:50|unique:users,identifier',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $identifierType = match($request->role) {
                'citizen'                       => 'nik',
                'employee','district_chief','regent','admin','super_admin' => 'nip',
                default                         => 'username',
            };

            $user = User::create([
                'name'            => $request->name,
                'role'            => $request->role,
                'identifier'      => $request->identifier,
                'identifier_type' => $identifierType,
                'password'        => Hash::make($request->password),
            ]);

            // Buat profile table sesuai role
            if ($request->role === 'employee') {
                $request->validate([
                    'nip'           => 'required|string|max:20|unique:employees,nip',
                    'email'         => 'required|email|unique:employees,email',
                    'phone'         => 'required|string|max:20',
                    'position'      => ['required', Rule::in(['field_officer','supervisor','head_of_department'])],
                    'department_id' => 'required|exists:departments,id',
                ]);
                Employee::create([
                    'user_id'       => $user->id,
                    'nip'           => $request->nip,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'position'      => $request->position,
                    'department_id' => $request->department_id,
                    'status'        => 'active',
                ]);
            } elseif ($request->role === 'district_chief') {
                $request->validate([
                    'nip'         => 'required|string|max:20|unique:district__chiefs,nip',
                    'email'       => 'required|email|unique:district__chiefs,email',
                    'phone'       => 'required|string|max:20',
                    'district_id' => 'required|exists:districts,id',
                    'start_year'  => 'required|digits:4',
                ]);
                District_Chief::create([
                    'user_id'     => $user->id,
                    'nip'         => $request->nip,
                    'email'       => $request->email,
                    'phone'       => $request->phone,
                    'district_id' => $request->district_id,
                    'start_year'  => $request->start_year,
                    'status'      => 'active',
                ]);
            } elseif ($request->role === 'regent') {
                $request->validate([
                    'nip'        => 'required|string|max:20|unique:regents,nip',
                    'email'      => 'required|email|unique:regents,email',
                    'phone'      => 'required|string|max:20',
                    'start_year' => 'required|digits:4',
                ]);
                Regent::create([
                    'user_id'    => $user->id,
                    'nip'        => $request->nip,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                    'start_year' => $request->start_year,
                    'status'     => 'active',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    // ── Edit form ─────────────────────────────────────────────────────────
    public function edit(User $user)
    {
        $isSuperAdmin = Auth::user()->role === 'super_admin';
        if ($user->role === 'super_admin' && ! $isSuperAdmin) abort(403);

        $user->load(['employee.department','districtChief.district','regent']);
        $departments = Department::orderBy('name')->get();
        $districts   = District::orderBy('name')->get();

        return view('admin.users.edit', compact('user','isSuperAdmin','departments','districts'));
    }

    // ── Update ────────────────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $isSuperAdmin = Auth::user()->role === 'super_admin';
        if ($user->role === 'super_admin' && ! $isSuperAdmin) abort(403);

        $request->validate([
            'name'       => 'required|string|max:100',
            'identifier' => ['required','string','max:50', Rule::unique('users','identifier')->ignore($user->id)],
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $updateData = ['name' => $request->name, 'identifier' => $request->identifier];
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            $user->update($updateData);

            // Update profile table
            if ($user->role === 'employee' && $user->employee) {
                $request->validate([
                    'email'    => ['required','email', Rule::unique('employees','email')->ignore($user->employee->id)],
                    'phone'    => 'required|string|max:20',
                    'position' => ['required', Rule::in(['field_officer','supervisor','head_of_department'])],
                    'department_id' => 'required|exists:departments,id',
                    'status'   => ['required', Rule::in(['active','on_leave','inactive'])],
                ]);
                $user->employee->update([
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'position'      => $request->position,
                    'department_id' => $request->department_id,
                    'status'        => $request->status,
                ]);
            } elseif ($user->role === 'district_chief' && $user->districtChief) {
                $request->validate([
                    'email'       => ['required','email', Rule::unique('district__chiefs','email')->ignore($user->districtChief->id)],
                    'phone'       => 'required|string|max:20',
                    'district_id' => 'required|exists:districts,id',
                    'status'      => ['required', Rule::in(['active','inactive'])],
                ]);
                $user->districtChief->update([
                    'email'       => $request->email,
                    'phone'       => $request->phone,
                    'district_id' => $request->district_id,
                    'status'      => $request->status,
                ]);
            } elseif ($user->role === 'regent' && $user->regent) {
                $request->validate([
                    'email'  => ['required','email', Rule::unique('regents','email')->ignore($user->regent->id)],
                    'phone'  => 'required|string|max:20',
                    'status' => ['required', Rule::in(['active','inactive'])],
                ]);
                $user->regent->update([
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'status' => $request->status,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', "Data {$user->name} berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    // ── Delete ────────────────────────────────────────────────────────────
    public function destroy(User $user)
    {
        $isSuperAdmin = Auth::user()->role === 'super_admin';
        if ($user->role === 'super_admin' && ! $isSuperAdmin) abort(403);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['general' => 'Tidak dapat menghapus akun sendiri.']);
        }

        DB::beginTransaction();
        try {
            $user->employee?->delete();
            $user->districtChief?->delete();
            $user->regent?->delete();
            $user->delete();
            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', "Pengguna {$user->name} berhasil dihapus.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['general' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}