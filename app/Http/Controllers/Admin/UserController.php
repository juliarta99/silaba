<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User, Employee, Department};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['employee.department']);
        if ($request->role)   $query->where('role', $request->role);
        if ($request->search) $query->where('name','like',"%{$request->search}%");
        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['employee.department','citizen']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        // Hanya super_admin — sudah diproteksi middleware
        $departments = Department::all();
        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100', 'identifier' => 'required|unique:users',
            'identifier_type' => 'required|in:nik,nip,username',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,super_admin,employee,district_chief,regent',
        ]);
        $user = User::create($request->only('name','identifier','identifier_type','password','role'));
        if ($request->role === 'employee' && $request->department_id) {
            Employee::create(['user_id'=>$user->id,'nip'=>$request->nip,'phone'=>$request->phone,
                              'email'=>$request->email,'position'=>$request->position,
                              'department_id'=>$request->department_id,'status'=>'active']);
        }
        return redirect()->route('admin.users.index')->with('success','Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        return view('admin.users.edit', compact('user','departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(['name' => 'required|max:100', 'role' => 'required']);
        $user->update($request->only('name','role'));
        if ($user->employee && $request->has('position')) {
            $user->employee->update($request->only('position','status','department_id'));
        }
        return redirect()->route('admin.users.index')->with('success','Pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        // Soft: nonaktifkan employee, atau hapus user
        if ($user->employee) $user->employee->update(['status' => 'inactive']);
        return redirect()->route('admin.users.index')->with('success','Pengguna dinonaktifkan.');
    }
}
