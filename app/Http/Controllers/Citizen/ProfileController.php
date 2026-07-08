<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // ── Tampilkan profil ──────────────────────────────────────────────────
    public function index()
    {
        $user    = User::with('citizen')->find(Auth::user()->id);
        $citizen = $user->citizen;

        return view('citizen.profile', compact('user', 'citizen'));
    }

    // ── Update informasi dasar (email saja yang bisa diubah) ─────────────
    public function update(Request $request)
    {
        $user    = Auth::user();
        $citizen = $user->citizen;

        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('citizens', 'email')->ignore($citizen->id),
            ],
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $citizen->update(['email' => $request->email]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Upload foto profil ────────────────────────────────────────────────
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'photo.max'   => 'Ukuran foto maksimal 2MB.',
            'photo.mimes' => 'Format foto harus JPG atau PNG.',
        ]);

        $user = User::find(Auth::user()->id);

        // Hapus foto lama
        if ($user->picture) {
            Storage::disk('public')->delete($user->picture);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['picture' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    // ── Ubah password ─────────────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'  => 'required|string',
            'password'          => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::find(Auth::user()->id);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}