@extends('layouts.app')
@section('title', 'Profil Saya — SILABU')

@section('content')

@php
$name     = $user->name;
$initials = strtoupper(substr(trim($name), 0, 2));
$picture  = $user->picture ? asset('storage/' . $user->picture) : null;
@endphp

<div class="bg-gray-50 min-h-[calc(100vh-68px)] pb-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-6">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('regent.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- Flash --}}
        @if (session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-success mb-5">
            <svg class="w-4 h-4 text-success shrink-0" fill="none" viewBox="0 0 16 16">
                <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-error mb-5">
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ══ CARD: Avatar ══ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
            <div class="flex items-center gap-5">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-primary-100 flex items-center
                                justify-center border-2 border-white shadow-sm">
                        @if ($picture)
                        <img id="avatar-preview" src="{{ $picture }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        @else
                        <span id="avatar-initials" class="text-2xl font-bold text-primary-600 select-none">{{ $initials }}</span>
                        @endif
                    </div>
                    <label for="photo-upload"
                           class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-primary-500 hover:bg-primary-700
                                  text-white flex items-center justify-center cursor-pointer shadow-md transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                            <path d="M14 12V5a1 1 0 00-1-1h-1.586a1 1 0 01-.707-.293l-.914-.914A1 1 0 009.086 2.5H6.914a1 1 0 00-.707.293l-.914.914A1 1 0 014.586 4H3a1 1 0 00-1 1v7a1 1 0 001 1h10a1 1 0 001-1z"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                    </label>
                    <form id="photo-form" action="{{ route('regent.profile.photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PATCH')
                        <input type="file" id="photo-upload" name="photo" accept="image/jpg,image/jpeg,image/png"
                               class="hidden" onchange="previewAndSubmit(this)">
                    </form>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $name }}</h2>
                    <p class="text-sm text-gray-500 mb-2">Bupati Kabupaten Badung</p>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full
                                 {{ $regent->status === 'active' ? 'bg-green-100 text-success' : 'bg-red-100 text-error' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $regent->status === 'active' ? 'bg-success' : 'bg-error' }}"></span>
                        {{ $regent->status === 'active' ? 'Aktif Menjabat' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ══ CARD: Informasi Jabatan ══ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5" x-data="{ editing: false }">

            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-900">Informasi Jabatan</h3>
                <button type="button" @@click="editing = !editing"
                        class="text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                    <span x-text="editing ? 'Batal' : 'Edit Kontak'"></span>
                </button>
            </div>

            {{-- VIEW --}}
            <div x-show="!editing" class="space-y-4">
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16">
                            <rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M5 8h6M5 5.5h3M5 10.5h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        NIP
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700 font-mono">
                        {{ $regent->nip }}
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16">
                            <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M2 14c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        Nama Lengkap
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700">
                        {{ $user->name }}
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16">
                            <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6c0-2.485-2.015-4.5-4.5-4.5zm0 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"
                                  stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                        </svg>
                        Wilayah Tugas
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700">
                        Kabupaten Badung, Bali
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Mulai Menjabat</label>
                        <div class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700">
                            {{ $regent->start_year }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Akhir Jabatan</label>
                        <div class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700">
                            {{ $regent->end_year ?? 'Masih menjabat' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 mb-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16">
                                <rect x="1.5" y="3.5" width="13" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                                <path d="M1.5 5.5l6.5 4 6.5-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                            </svg>
                            Email
                        </label>
                        <div class="px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900">
                            {{ $regent->email ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 mb-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 16 16">
                                <path d="M14 10.667c0 .226-.05.45-.153.666-.102.217-.255.423-.46.609-.34.307-.713.46-1.108.46-.284 0-.589-.068-.921-.207a9.223 9.223 0 01-.91-.49 15.17 15.17 0 01-.887-.666 14.83 14.83 0 01-.854-.858 15.07 15.07 0 01-.666-.88 9.345 9.345 0 01-.484-.904c-.136-.33-.204-.645-.204-.944 0-.29.063-.568.19-.82.125-.254.314-.483.572-.682.313-.234.65-.35.998-.35.135 0 .27.028.392.085.125.057.235.142.32.267l1.11 1.565c.085.12.148.23.192.336.044.104.068.203.068.294 0 .113-.034.227-.1.335a1.64 1.64 0 01-.268.335l-.363.378a.258.258 0 00-.073.193c0 .04.006.075.017.113.017.04.034.07.045.1.085.153.228.352.43.593.208.24.432.485.673.73.25.244.489.47.732.677.24.2.44.34.597.42.028.012.06.023.096.034a.377.377 0 00.107.017.265.265 0 00.198-.08l.363-.36c.107-.107.215-.19.318-.24a.638.638 0 01.3-.073c.09 0 .185.022.288.068.103.045.21.107.325.19l1.583 1.126c.124.085.21.187.26.307.046.12.073.24.073.373z"
                                      stroke="currentColor" stroke-width="1"/>
                            </svg>
                            Nomor WhatsApp
                        </label>
                        <div class="px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900">
                            {{ $regent->phone ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- EDIT --}}
            <div x-show="editing" x-cloak>
                <form action="{{ route('regent.profile.update') }}" method="POST" class="space-y-5">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP</label>
                        <input type="text" value="{{ $regent->nip }}" readonly
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100
                                      text-sm text-gray-500 cursor-not-allowed outline-none font-mono">
                        <p class="text-xs text-gray-400 mt-1">NIP tidak dapat diubah secara mandiri</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" readonly
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100
                                      text-sm text-gray-500 cursor-not-allowed outline-none">
                        <p class="text-xs text-gray-400 mt-1">Hubungi Admin untuk perubahan nama</p>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Email <span class="text-error">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $regent->email) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                      text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                        @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nomor WhatsApp <span class="text-error">*</span>
                        </label>
                        <input type="text" id="phone" name="phone"
                               value="{{ old('phone', $regent->phone) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                      text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                        @error('phone') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @@click="editing = false"
                                class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200
                                       text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-full sm:flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                                       text-white text-sm font-semibold transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ CARD: Keamanan Akun ══ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ changingPassword: false }">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M8 11V7a4 4 0 018 0v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Keamanan Akun</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Ubah password untuk menjaga keamanan akun</p>
                    </div>
                </div>
                <button type="button" @@click="changingPassword = !changingPassword"
                        class="text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors shrink-0">
                    <span x-text="changingPassword ? 'Batal' : 'Ubah Password'"></span>
                </button>
            </div>

            <div x-show="changingPassword" x-cloak class="mt-5 pt-5 border-t border-gray-100">
                <form action="{{ route('regent.profile.password') }}" method="POST" class="space-y-4"
                      x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf @method('PATCH')

                    @foreach ([
                        ['id' => 'current_password', 'name' => 'current_password', 'label' => 'Password Saat Ini',     'placeholder' => 'Masukkan password saat ini', 'show' => 'showCurrent'],
                        ['id' => 'password',         'name' => 'password',         'label' => 'Password Baru',         'placeholder' => 'Minimal 8 karakter',         'show' => 'showNew'],
                        ['id' => 'password_conf',    'name' => 'password_confirmation','label' => 'Konfirmasi Password Baru','placeholder' => 'Ulangi password baru',   'show' => 'showConfirm'],
                    ] as $f)
                    <div>
                        <label for="{{ $f['id'] }}" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            {{ $f['label'] }} <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <input :type="{{ $f['show'] }} ? 'text' : 'password'"
                                   id="{{ $f['id'] }}" name="{{ $f['name'] }}"
                                   placeholder="{{ $f['placeholder'] }}" required
                                   {{ $f['name'] === 'password' ? 'minlength=8' : '' }}
                                   class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 bg-gray-50
                                          text-sm text-gray-900 placeholder-gray-400 focus:bg-white
                                          focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                          outline-none transition-all">
                            <button type="button" @@click="{{ $f['show'] }} = !{{ $f['show'] }}"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        @if ($f['name'] === 'current_password')
                            @error('current_password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        @elseif ($f['name'] === 'password')
                            @error('password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>
                    @endforeach

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-3">
                        <button type="button" @@click="changingPassword = false"
                                class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200
                                       text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-full sm:flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                                       text-white text-sm font-semibold transition-colors">
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function previewAndSubmit(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];
    if (file.size > 2 * 1024 * 1024) { alert('Ukuran foto maksimal 2MB.'); input.value = ''; return; }
    var reader = new FileReader();
    reader.onload = function (e) {
        var preview = document.getElementById('avatar-preview');
        var initials = document.getElementById('avatar-initials');
        if (preview) { preview.src = e.target.result; }
        else if (initials) {
            var img = document.createElement('img');
            img.id = 'avatar-preview'; img.src = e.target.result;
            img.alt = 'Preview'; img.className = 'w-full h-full object-cover';
            initials.parentNode.replaceChild(img, initials);
        }
    };
    reader.readAsDataURL(file);
    document.getElementById('photo-form').submit();
}
</script>
@endsection