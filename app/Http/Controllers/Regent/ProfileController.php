<?php

namespace App\Http\Controllers\Regent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user   = User::with('regent')->findOrFail(Auth::user()->id);
        $regent = $user->regent;
        abort_if(! $regent, 403, 'Data bupati tidak ditemukan.');
        return view('regent.profile', compact('user', 'regent'));
    }

    public function update(Request $request)
    {
        $regent = Auth::user()->regent;
        abort_if(! $regent, 403);

        $request->validate([
            'email' => ['required','email', Rule::unique('regents','email')->ignore($regent->id)],
            'phone' => ['required','string','max:20', Rule::unique('regents','phone')->ignore($regent->id)],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.unique'   => 'Nomor WhatsApp sudah terdaftar.',
        ]);

        $regent->update(['email' => $request->email, 'phone' => $request->phone]);
        return back()->with('success', 'Informasi kontak berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.max'      => 'Ukuran foto maksimal 2MB.',
            'photo.mimes'    => 'Format foto harus JPG atau PNG.',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        if ($user->picture && Storage::disk('public')->exists($user->picture)) {
            Storage::disk('public')->delete($user->picture);
        }
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['picture' => $path]);
        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }
        $user->update(['password' => $request->password]);
        return back()->with('success', 'Password berhasil diubah.');
    }
}