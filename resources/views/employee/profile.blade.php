@extends('layouts.app')

@section('title', 'Profil Saya — SILABA')

@section('content')

@php
$name     = $user->name;
$initials = strtoupper(substr(trim($name), 0, 2));
$picture  = $user->picture ? asset('storage/' . $user->picture) : null;
$employee = $user->employee;

// Label Posisi
$positionLabel = match($employee?->position) {
    'field_officer'      => 'Petugas Lapangan',
    'supervisor'         => 'Supervisor',
    'head_of_department' => 'Kepala Dinas',
    default              => 'Pegawai',
};
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-24">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        <div class="mb-6">
            <a href="{{ route('employee.' . (auth()->user()->employee->position === 'head_of_department' ? 'head' : str_replace('_', '-', auth()->user()->employee->position)) . '.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- ── Flash messages ── --}}
        @if (session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100
                    text-sm text-green-800 mb-5">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-600 mb-5">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ════════════════════════════════════════
             CARD: Avatar + Nama + Badge
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
            <div class="flex items-center gap-5">

                {{-- Avatar + tombol ganti foto --}}
                <div class="relative shrink-0" x-data>
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-primary-100 flex items-center justify-center border-2 border-white shadow-sm">
                        @if ($picture)
                        <img id="avatar-preview" src="{{ $picture }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        @else
                        <span id="avatar-initials" class="text-2xl font-bold text-primary-500 select-none">
                            {{ $initials }}
                        </span>
                        @endif
                    </div>

                    {{-- Tombol kamera --}}
                    <label for="photo-upload"
                           class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-primary-500 hover:bg-primary-700 text-white flex items-center justify-center cursor-pointer shadow-md transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M14 12V5a1 1 0 00-1-1h-1.586a1 1 0 01-.707-.293l-.914-.914A1 1 0 009.086 2.5H6.914a1 1 0 00-.707.293l-.914.914A1 1 0 014.586 4H3a1 1 0 00-1 1v7a1 1 0 001 1h10a1 1 0 001-1z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                        <span class="sr-only">Ganti foto</span>
                    </label>

                    {{-- Form upload foto --}}
                    <form id="photo-form" action="{{ route('employee.profile.photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="file" id="photo-upload" name="photo" accept="image/jpg,image/jpeg,image/png" class="hidden" onchange="previewAndSubmit(this)">
                    </form>
                </div>

                {{-- Info nama + badge --}}
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $name }}</h2>
                    <p class="text-sm text-gray-500 mb-2">{{ $positionLabel }}</p>
                    
                    @if ($employee?->status === 'active')
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Pegawai Aktif
                    </span>
                    @elseif ($employee?->status === 'on_leave')
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Sedang Cuti
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-red-100 text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Tidak Aktif
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             CARD: Informasi Dasar
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5" x-data="{ editing: false }">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-gray-900">Informasi Kepegawaian</h3>
                <button type="button" @click="editing = !editing" class="text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                    <span x-text="editing ? 'Batal' : 'Edit Kontak'"></span>
                </button>
            </div>

            {{-- VIEW MODE --}}
            <div x-show="!editing" class="space-y-5">
                
                {{-- NIP --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        NIP / ID Pegawai
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-700 font-mono">
                        {{ $user->identifier }}
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M2 14c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        Nama Lengkap
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-700">
                        {{ $user->name }}
                    </div>
                </div>

                {{-- Instansi / OPD --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Instansi / Bagian</label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-700">
                        {{ $employee?->department?->name ?? 'Belum ada departemen' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Email --}}
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 mb-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <rect x="1.5" y="3.5" width="13" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                                <path d="M1.5 5.5l6.5 4 6.5-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                            </svg>
                            Alamat Email
                        </label>
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900">
                            {{ $employee?->email ?? '—' }}
                        </div>
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 mb-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M14 10.667c0 .226-.05.45-.153.666-.102.217-.255.423-.46.609-.34.307-.713.46-1.108.46-.284 0-.589-.068-.921-.207a9.223 9.223 0 01-.91-.49 15.17 15.17 0 01-.887-.666 14.83 14.83 0 01-.854-.858 15.07 15.07 0 01-.666-.88 9.345 9.345 0 01-.484-.904c-.136-.33-.204-.645-.204-.944 0-.29.063-.568.19-.82.125-.254.314-.483.572-.682.313-.234.65-.35.998-.35.135 0 .27.028.392.085.125.057.235.142.32.267l1.11 1.565c.085.12.148.23.192.336.044.104.068.203.068.294 0 .113-.034.227-.1.335a1.64 1.64 0 01-.268.335l-.363.378a.258.258 0 00-.073.193c0 .04.006.075.017.113.017.04.034.07.045.1.085.153.228.352.43.593.208.24.432.485.673.73.25.244.489.47.732.677.24.2.44.34.597.42.028.012.06.023.096.034a.377.377 0 00.107.017.265.265 0 00.198-.08l.363-.36c.107-.107.215-.19.318-.24a.638.638 0 01.3-.073c.09 0 .185.022.288.068.103.045.21.107.325.19l1.583 1.126c.124.085.21.187.26.307.046.12.073.24.073.373z" stroke="currentColor" stroke-width="1"/>
                            </svg>
                            Nomor WhatsApp
                        </label>
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900">
                            {{ $employee?->phone ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- EDIT MODE --}}
            <div x-show="editing" x-cloak>
                <form action="{{ route('employee.profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- NIP (Readonly) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP / ID Pegawai</label>
                        <input type="text" value="{{ $user->identifier }}" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-sm text-gray-500 cursor-not-allowed outline-none font-mono">
                        <p class="text-xs text-gray-400 mt-1.5">NIP tidak dapat diubah secara mandiri</p>
                    </div>

                    {{-- Nama (Readonly) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-sm text-gray-500 cursor-not-allowed outline-none">
                        <p class="text-xs text-gray-400 mt-1.5">Hubungi Admin jika terdapat perubahan nama</p>
                    </div>

                    {{-- Email (EDITABLE) --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $employee?->email) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm text-gray-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                        @error('email') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- No HP (EDITABLE) --}}
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor Telepon/WA <span class="text-red-500">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $employee?->phone) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm text-gray-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                        @error('phone') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="editing = false"
                                class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-full sm:flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             CARD: Keamanan Akun
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ changingPassword: false }">

            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M8 11V7a4 4 0 018 0v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Keamanan Akun</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Ubah password untuk menjaga keamanan akun Anda</p>
                    </div>
                </div>
                <button type="button" @click="changingPassword = !changingPassword"
                        class="text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors shrink-0">
                    <span x-text="changingPassword ? 'Batal' : 'Ubah Password'"></span>
                </button>
            </div>

            {{-- Form ubah password --}}
            <div x-show="changingPassword" x-cloak class="mt-5 pt-5 border-t border-gray-100">
                <form action="{{ route('employee.profile.password') }}" method="POST" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf
                    @method('PATCH')

                    {{-- Password saat ini --}}
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-gray-600 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" placeholder="Masukkan password saat ini" required
                                   class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                            <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password baru --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" id="password" name="password" placeholder="Minimal 8 karakter" required minlength="8"
                                   class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                            <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Konfirmasi password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required
                                   class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-3">
                        <button type="button" @click="changingPassword = false"
                                class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-full sm:flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($employee->position == 'field_officer')
    {{-- Navbar Bottom khusus Pegawai (Opsional, pastikan komponen ada) --}}
    <x-employee.bottom-nav active="profile" :badge="$sedangDikerjakan" />
@endif

{{-- Preview foto sebelum upload --}}
<script>
function previewAndSubmit(input) {
    if (!input.files || !input.files[0]) return;

    var file  = input.files[0];
    var maxMB = 2;

    if (file.size > maxMB * 1024 * 1024) {
        alert('Ukuran foto maksimal 2MB.');
        input.value = '';
        return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
        var preview = document.getElementById('avatar-preview');
        var initials = document.getElementById('avatar-initials');

        if (preview) {
            preview.src = e.target.result;
        } else if (initials) {
            var img = document.createElement('img');
            img.id  = 'avatar-preview';
            img.src = e.target.result;
            img.alt = 'Preview';
            img.className = 'w-full h-full object-cover';
            initials.parentNode.replaceChild(img, initials);
        }
    };
    reader.readAsDataURL(file);

    document.getElementById('photo-form').submit();
}
</script>

@endsection