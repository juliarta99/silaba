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
    public function showRegister()                { return view('auth.register'); }
    public function showRegisterStep2()
    {
        if (! session()->has('register.nik')) return redirect()->route('register');
        return view('auth.register_step2');
    }
    public function showVerifyOtp()
    {
        if (! session()->has('register.user_id')) return redirect()->route('register');
        return view('auth.verify_otp');
    }
    public function showVerifyOtpSuccess()        { return view('auth.verify_otp_success'); }

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

    public function register(Request $request)
    {
        $request->validate(['nik' => 'required|digits:16|unique:users,identifier', 'name' => 'required|max:100', 'phone' => 'required|max:20']);
        session(['register.nik' => $request->nik, 'register.name' => $request->name, 'register.phone' => $request->phone]);
        // TODO: kirim OTP via WA API
        return redirect()->route('register.step2');
    }

    public function registerStep2(Request $request)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $user = User::create([
            'name' => session('register.name'), 'identifier' => session('register.nik'),
            'identifier_type' => 'nik', 'password' => $request->password, 'role' => 'citizen',
        ]);
        // TODO: create Citizen profile record
        session(['register.user_id' => $user->id]);
        return redirect()->route('verify.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        // TODO: validate OTP dari WA gateway
        $userId = session('register.user_id');
        session()->forget(['register.nik','register.name','register.phone','register.user_id']);
        Auth::loginUsingId($userId);
        return redirect()->route('verify.otp.success');
    }
}
