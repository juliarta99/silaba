<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    private array $roleRedirects = [
        'citizen'     => 'citizen.dashboard',
        'regent'      => 'regent.dashboard',
        'admin'       => 'admin.dashboard',
        'super_admin' => 'admin.dashboard',
    ];
    private array $positionRedirects = [
        'field_officer'      => 'employee.field-officer.dashboard',
        'supervisor'         => 'employee.supervisor.dashboard',
        'head_of_department' => 'employee.head.dashboard',
    ];

    public function showLogin()                   { return view('auth.login'); }

    public function login(Request $request)
    {
        $request->validate(['identifier' => 'required', 'password' => 'required']);
        $user = User::where('identifier', $request->identifier)->first();
        if (! $user || ! Auth::attempt(['identifier' => $request->identifier, 'password' => $request->password])) {
            return back()->withErrors(['identifier' => 'Identifier atau password salah.'])->withInput();
        }
        $request->session()->regenerate();
        if ($user->role === 'employee') {
            $route = $this->positionRedirects[$user->employee?->position] ?? 'home';
            return redirect()->intended(route($route));
        }
        return redirect()->intended(route($this->roleRedirects[$user->role] ?? 'home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
