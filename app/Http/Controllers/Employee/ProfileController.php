<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
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
        // Mengambil data user beserta relasi employee dan department
        $user     = User::with('employee.department')->findOrFail(Auth::user()->id);
        $employee = $user->employee;

        $sedangDikerjakan = 0;

        if($employee->position == 'field_officer') {
            $sedangDikerjakan = Assignment::where('employee_id', $employee->id)
                                ->whereHas('report', fn ($q) =>
                                    $q->whereIn('status', ['in_progress', 'under_review', 'waiting_for_materials'])
                                )->count();
        }

        abort_if(! $employee, 403, 'Data pegawai tidak ditemukan.');

        return view('employee.profile', compact('user', 'employee', 'sedangDikerjakan'));
    }

    // ── Update informasi kontak (email & phone di tabel employees) ────────
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $employee = $user->employee;

        abort_if(! $employee, 403);

        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($employee->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('employees', 'phone')->ignore($employee->id),
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan oleh pegawai lain.',
            'phone.required' => 'Nomor WhatsApp/HP wajib diisi.',
            'phone.unique'   => 'Nomor WhatsApp/HP ini sudah terdaftar di sistem.',
        ]);

        $employee->update([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Informasi kontak berhasil diperbarui.');
    }

    // ── Upload foto profil (di tabel users) ───────────────────────────────
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.max'      => 'Ukuran foto maksimal 2MB.',
            'photo.mimes'    => 'Format foto harus JPG atau PNG.',
            'photo.image'    => 'File yang diupload harus berupa gambar.',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        // Hapus foto lama dari storage jika ada
        if ($user->picture && Storage::disk('public')->exists($user->picture)) {
            Storage::disk('public')->delete($user->picture);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        
        $user->update(['picture' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    // ── Ubah password (di tabel users) ────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Tidak menggunakan bcrypt manual agar kompatibel dengan cast 'hashed' atau mutator bawaan Laravel
        $user->update(['password' => $request->password]);

        return back()->with('success', 'Password akun berhasil diubah.');
    }
}