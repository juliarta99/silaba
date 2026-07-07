@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@section('content')

@php
$roleConfig = [
    'super_admin'    => ['label' => 'Super Admin',  'bg' => 'bg-gray-900',   'text' => 'text-white'],
    'admin'          => ['label' => 'Admin Sistem', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    'regent'         => ['label' => 'Bupati/Sekda', 'bg' => 'bg-red-100',   'text' => 'text-error'],
    'district_chief' => ['label' => 'Camat',        'bg' => 'bg-teal-100',  'text' => 'text-teal-700'],
    'employee'       => ['label' => 'Petugas',      'bg' => 'bg-green-100', 'text' => 'text-success'],
    'citizen'        => ['label' => 'Warga',        'bg' => 'bg-blue-100',  'text' => 'text-blue-700'],
];
$positionLabel = [
    'field_officer'      => 'Petugas',
    'supervisor'         => 'Supervisor',
    'head_of_department' => 'Kepala Dinas',
];
$statusConfig = [
    'active'   => ['label' => 'Aktif',    'bg' => 'bg-green-50 border-green-200',  'dot' => 'bg-success', 'text' => 'text-success'],
    'inactive' => ['label' => 'Nonaktif', 'bg' => 'bg-red-50 border-red-200',      'dot' => 'bg-error',   'text' => 'text-error'],
    'on_leave' => ['label' => 'Cuti',     'bg' => 'bg-yellow-50 border-yellow-200','dot' => 'bg-yellow-400','text' => 'text-yellow-700'],
];
// Siapkan data edit jika ada error redirect kembali ke form edit
$editUser = session('edit_user_id') ? $users->firstWhere('id', session('edit_user_id')) : null;
@endphp

{{-- ══ Alpine state utama ══ --}}
<div x-data="userModal()" x-init="init()" class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua akun pengguna sistem dari berbagai role</p>
        </div>
        <button type="button" @@click="openCreate()"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                       text-white text-sm font-semibold transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    {{-- ── Flash ── --}}
    @if (session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-sm text-success">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 16 16">
            <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->any())
    <div x-init="$nextTick(() => { @if(session('modal_mode') === 'edit') openEditById({{ session('modal_user_id', 0) }}) @else open = true; mode = 'create' @endif })"
         class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
        @foreach ([
            ['label' => 'Warga',       'val' => $stats['citizen'],        'clr' => 'text-blue-600'],
            ['label' => 'Petugas',     'val' => $stats['employee'],       'clr' => 'text-success'],
            ['label' => 'Supervisor',  'val' => $stats['supervisor'],     'clr' => 'text-purple-600'],
            ['label' => 'Kepala Dinas','val' => $stats['head_of_dept'],   'clr' => 'text-orange-600'],
            ['label' => 'Camat',       'val' => $stats['district_chief'], 'clr' => 'text-teal-600'],
            ['label' => 'Bupati',      'val' => $stats['regent'],         'clr' => 'text-error'],
        ] as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold {{ $s['clr'] }} tabular-nums">{{ number_format($s['val']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Filter ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                        <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, email, atau NIP..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                  placeholder-gray-400 focus:bg-white focus:border-primary-500 focus:ring-1
                                  focus:ring-primary-500 outline-none transition-all">
                </div>
                <select name="role" onchange="this.form.submit()"
                        class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700
                               focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
                    <option value="">Semua Role</option>
                    <option value="citizen"            {{ request('role') === 'citizen'            ? 'selected' : '' }}>Warga</option>
                    <option value="field_officer"      {{ request('role') === 'field_officer'      ? 'selected' : '' }}>Petugas Lapangan</option>
                    <option value="supervisor"         {{ request('role') === 'supervisor'         ? 'selected' : '' }}>Supervisor</option>
                    <option value="head_of_department" {{ request('role') === 'head_of_department' ? 'selected' : '' }}>Kepala Dinas</option>
                    <option value="district_chief"     {{ request('role') === 'district_chief'     ? 'selected' : '' }}>Camat</option>
                    <option value="regent"             {{ request('role') === 'regent'             ? 'selected' : '' }}>Bupati/Sekda</option>
                    <option value="admin"              {{ request('role') === 'admin'              ? 'selected' : '' }}>Admin</option>
                    @if ($isSuperAdmin)
                    <option value="super_admin"        {{ request('role') === 'super_admin'        ? 'selected' : '' }}>Super Admin</option>
                    @endif
                </select>
                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700
                               focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>Cuti</option>
                </select>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold">Cari</button>
                @if (request()->hasAny(['search','role','status']))
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50">Reset</a>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-3">
                Menampilkan <span class="font-semibold text-gray-700">{{ number_format($users->total()) }}</span> pengguna
            </p>
        </form>
    </div>

    {{-- ── Tabel ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama & Kontak</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Role</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">NIP / NIK</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Instansi/Kecamatan</th>
                        <th class="text-center px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-center px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $u)
                    @php
                    $rc       = $roleConfig[$u->role] ?? ['label' => $u->role, 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                    $emp      = $u->employee;
                    $dc       = $u->district_chief;
                    $reg      = $u->regent;
                    $email    = $emp?->email ?? $dc?->email ?? $reg?->email ?? null;
                    $phone    = $emp?->phone ?? $dc?->phone ?? $reg?->phone ?? null;
                    $nip      = $emp?->nip   ?? $dc?->nip   ?? $reg?->nip   ?? null;
                    $instansi = $emp ? $emp->department?->name : ($dc ? $dc->district?->name : ($reg ? 'Kab. Badung' : null));
                    $roleLabel = $u->role === 'employee' && $emp ? ($positionLabel[$emp->position] ?? $rc['label']) : $rc['label'];
                    $statusKey = $emp?->status ?? $dc?->status ?? $reg?->status ?? null;
                    $sc        = $statusKey ? ($statusConfig[$statusKey] ?? null) : null;
                    $initials  = strtoupper(substr(trim($u->name), 0, 2));

                    // Data untuk modal edit — encode ke JSON
                    $editData = json_encode([
                        'id'            => $u->id,
                        'name'          => $u->name,
                        'identifier'    => $u->identifier,
                        'role'          => $u->role,
                        'email'         => $email,
                        'phone'         => $phone,
                        'nip'           => $nip,
                        'position'      => $emp?->position ?? '',
                        'department_id' => $emp?->department_id ?? '',
                        'district_id'   => $dc?->district_id ?? '',
                        'start_year'    => $dc?->start_year ?? $reg?->start_year ?? '',
                        'status'        => $statusKey ?? '',
                    ]);
                    @endphp
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full shrink-0 overflow-hidden bg-primary-100
                                            flex items-center justify-center text-xs font-bold text-primary-600">
                                    @if ($u->picture)
                                    <img src="{{ asset('storage/' . $u->picture) }}" class="w-full h-full object-cover" alt="">
                                    @else{{ $initials }}@endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 leading-snug">{{ $u->name }}</p>
                                    @if ($email) <p class="text-xs text-gray-400">{{ $email }}</p> @endif
                                    @if ($phone) <p class="text-xs text-gray-400">{{ $phone }}</p> @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $rc['bg'] }} {{ $rc['text'] }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-4 font-mono text-xs text-gray-600">{{ $nip ?? $u->identifier ?? '—' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $instansi ?? '—' }}</td>
                        <td class="px-4 py-4 text-center">
                            @if ($sc)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1
                                         rounded-full border {{ $sc['bg'] }} {{ $sc['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                {{ $sc['label'] }}
                            </span>
                            @else <span class="text-xs text-gray-400">—</span> @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        @@click="openEdit({{ $editData }})"
                                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-blue-100 flex items-center
                                               justify-center text-gray-500 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                        <path d="M11.333 2.667a1.886 1.886 0 112.667 2.667L5.333 14H2.667v-2.667L11.333 2.667z"
                                              stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                @if ($u->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                      onsubmit="return confirm('Hapus {{ addslashes($u->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-red-100 flex items-center
                                                   justify-center text-gray-500 hover:text-error transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                            <path d="M2 4h12M5.333 4V2.667h5.334V4M6.667 7.333v4M9.333 7.333v4M3.333 4l.667 9.333h8L12.667 4"
                                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-14 text-sm text-gray-400">Tidak ada pengguna ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════
         MODAL CREATE / EDIT
    ══════════════════════════════════════════ --}}
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-start justify-center pt-16 pb-8 px-4 overflow-y-auto"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl" @@click.stop>

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900"
                    x-text="mode === 'create' ? 'Tambah Pengguna Baru' : 'Edit Pengguna'"></h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            {{-- CREATE FORM --}}
            <div x-show="mode === 'create'">
                <form method="POST" action="{{ route('admin.users.store') }}"
                      @@submit="disableHiddenInputs($el)">
                    @csrf
                    <div class="px-6 py-5 space-y-4 max-h-[65vh] overflow-y-auto">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="input-field">
                            @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Identifier --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP / NIK / Username <span class="text-error">*</span></label>
                            <input type="text" name="identifier" value="{{ old('identifier') }}" required
                                   class="input-field font-mono">
                            @error('identifier') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Role --}}
                        <div x-data="{ role: '{{ old('role','') }}' }">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role <span class="text-error">*</span></label>
                            <select name="role" x-model="role" required class="input-field">
                                <option value="">-- Pilih Role --</option>
                                <option value="citizen">Warga</option>
                                <option value="employee">Pegawai (Petugas/Supervisor/Kadis)</option>
                                <option value="district_chief">Camat</option>
                                <option value="regent">Bupati/Sekda</option>
                                @if ($isSuperAdmin)
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                                @endif
                            </select>
                            @error('role') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror

                            {{-- ── Employee fields ── --}}
                            <div x-show="role === 'employee'" class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Detail Pegawai</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">NIP <span class="text-error">*</span></label>
                                        <input type="text" name="nip" value="{{ old('nip') }}" class="input-field font-mono">
                                        @error('nip') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="label-sm">Posisi <span class="text-error">*</span></label>
                                        <select name="position" class="input-field">
                                            <option value="">Pilih Posisi</option>
                                            <option value="field_officer"      {{ old('position') === 'field_officer'      ? 'selected':'' }}>Petugas Lapangan</option>
                                            <option value="supervisor"         {{ old('position') === 'supervisor'         ? 'selected':'' }}>Supervisor</option>
                                            <option value="head_of_department" {{ old('position') === 'head_of_department' ? 'selected':'' }}>Kepala Dinas</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="label-sm">Dinas/OPD <span class="text-error">*</span></label>
                                    <select name="department_id" class="input-field">
                                        <option value="">-- Pilih Dinas --</option>
                                        @foreach ($departments as $d)
                                        <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected':'' }}>{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">Email <span class="text-error">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="input-field">
                                    </div>
                                    <div>
                                        <label class="label-sm">Nomor HP <span class="text-error">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="input-field">
                                    </div>
                                </div>
                            </div>

                            {{-- ── District Chief fields ── --}}
                            <div x-show="role === 'district_chief'" class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Detail Camat</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">NIP <span class="text-error">*</span></label>
                                        <input type="text" name="nip" value="{{ old('nip') }}" class="input-field font-mono">
                                    </div>
                                    <div>
                                        <label class="label-sm">Kecamatan <span class="text-error">*</span></label>
                                        <select name="district_id" class="input-field">
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach ($districts as $d)
                                            <option value="{{ $d->id }}" {{ old('district_id') == $d->id ? 'selected':'' }}>{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">Email <span class="text-error">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="input-field">
                                    </div>
                                    <div>
                                        <label class="label-sm">Nomor HP <span class="text-error">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="input-field">
                                    </div>
                                </div>
                                <div>
                                    <label class="label-sm">Tahun Mulai <span class="text-error">*</span></label>
                                    <input type="number" name="start_year" value="{{ old('start_year', date('Y')) }}"
                                           min="2000" max="{{ date('Y')+5 }}" class="input-field">
                                </div>
                            </div>

                            {{-- ── Regent fields ── --}}
                            <div x-show="role === 'regent'" class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Detail Bupati/Sekda</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">NIP <span class="text-error">*</span></label>
                                        <input type="text" name="nip" value="{{ old('nip') }}" class="input-field font-mono">
                                    </div>
                                    <div>
                                        <label class="label-sm">Tahun Mulai <span class="text-error">*</span></label>
                                        <input type="number" name="start_year" value="{{ old('start_year', date('Y')) }}"
                                               min="2000" max="{{ date('Y')+5 }}" class="input-field">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">Email <span class="text-error">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="input-field">
                                    </div>
                                    <div>
                                        <label class="label-sm">Nomor HP <span class="text-error">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="input-field">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="border-t border-gray-100 pt-4 space-y-3" x-data="{ sp: false, sc: false }">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Password</p>
                            <div>
                                <label class="label-sm">Password <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input :type="sp ? 'text' : 'password'" name="password" required minlength="8"
                                           placeholder="Minimal 8 karakter" class="input-field pr-11">
                                    <button type="button" @@click="sp=!sp" class="eye-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label-sm">Konfirmasi Password <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input :type="sc ? 'text' : 'password'" name="password_confirmation"
                                           placeholder="Ulangi password" class="input-field pr-11">
                                    <button type="button" @@click="sc=!sc" class="eye-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button" @@click="close()"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50">Batal</button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">
                            Tambah Pengguna
                        </button>
                    </div>
                </form>
            </div>

            {{-- EDIT FORM --}}
            <div x-show="mode === 'edit'">
                <template x-if="editData">
                    <form :action="`{{ url('admin/pengguna') }}/${editData.id}`" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" :value="editData.id">

                        <div class="px-6 py-5 space-y-4 max-h-[65vh] overflow-y-auto">

                            <div>
                                <label class="label-sm">Nama Lengkap <span class="text-error">*</span></label>
                                <input type="text" name="name" :value="editData.name" required class="input-field">
                            </div>

                            <div>
                                <label class="label-sm">NIP / NIK / Username <span class="text-error">*</span></label>
                                <input type="text" name="identifier" :value="editData.identifier" required class="input-field font-mono">
                            </div>

                            <div>
                                <label class="label-sm">Role</label>
                                <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-sm text-gray-500 cursor-not-allowed"
                                     x-text="roleLabelMap[editData.role] || editData.role"></div>
                                <input type="hidden" name="role" :value="editData.role">
                            </div>

                            {{-- Kontak --}}
                            <div class="border-t border-gray-100 pt-4 space-y-3"
                                 x-show="editData.role !== 'citizen' && editData.role !== 'admin' && editData.role !== 'super_admin'">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Informasi Tambahan</p>

                                <div class="grid grid-cols-2 gap-3" x-show="editData.role === 'employee'">
                                    <div>
                                        <label class="label-sm">Posisi</label>
                                        <select name="position" class="input-field">
                                            <option value="field_officer"      :selected="editData.position === 'field_officer'">Petugas Lapangan</option>
                                            <option value="supervisor"         :selected="editData.position === 'supervisor'">Supervisor</option>
                                            <option value="head_of_department" :selected="editData.position === 'head_of_department'">Kepala Dinas</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="label-sm">Dinas/OPD</label>
                                        <select name="department_id" class="input-field">
                                            @foreach ($departments as $d)
                                            <option value="{{ $d->id }}" :selected="editData.department_id == {{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div x-show="editData.role === 'district_chief'">
                                    <label class="label-sm">Kecamatan</label>
                                    <select name="district_id" class="input-field">
                                        @foreach ($districts as $d)
                                        <option value="{{ $d->id }}" :selected="editData.district_id == {{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="label-sm">Email</label>
                                        <input type="email" name="email" :value="editData.email" class="input-field">
                                    </div>
                                    <div>
                                        <label class="label-sm">Nomor HP</label>
                                        <input type="text" name="phone" :value="editData.phone" class="input-field">
                                    </div>
                                </div>

                                <div>
                                    <label class="label-sm">Status</label>
                                    <select name="status" class="input-field">
                                        <option value="active"   :selected="editData.status === 'active'">Aktif</option>
                                        <option value="on_leave" :selected="editData.status === 'on_leave'">Cuti</option>
                                        <option value="inactive" :selected="editData.status === 'inactive'">Nonaktif</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Password opsional --}}
                            <div class="border-t border-gray-100 pt-4 space-y-3" x-data="{ sp: false, sc: false }">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Ubah Password <span class="font-normal normal-case text-gray-400">(opsional)</span></p>
                                <div>
                                    <label class="label-sm">Password Baru</label>
                                    <div class="relative">
                                        <input :type="sp ? 'text' : 'password'" name="password" minlength="8"
                                               placeholder="Kosongkan jika tidak ingin mengubah" class="input-field pr-11">
                                        <button type="button" @@click="sp=!sp" class="eye-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="label-sm">Konfirmasi Password</label>
                                    <div class="relative">
                                        <input :type="sc ? 'text' : 'password'" name="password_confirmation"
                                               placeholder="Ulangi password baru" class="input-field pr-11">
                                        <button type="button" @@click="sc=!sc" class="eye-btn">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                            <button type="button" @@click="close()"
                                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                    class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </template>
            </div>

        </div>
    </div>

</div>

{{-- ── Tailwind utility classes reused in modal ── --}}
<style>
.input-field {
    width:100%; padding:.75rem 1rem; border-radius:.75rem;
    border:1px solid #E5E7EB; background:#F9FAFB; font-size:.875rem; color:#111827;
    outline:none; transition:all .15s;
}
.input-field:focus { background:white; border-color:#C01818; box-shadow:0 0 0 1px #C01818; }
.label-sm { display:block; font-size:.75rem; font-weight:600; color:#374151; margin-bottom:.375rem; }
.eye-btn  { position:absolute; inset-y:0; right:0; padding-right:1rem; display:flex; align-items:center; color:#9CA3AF; }
.eye-btn:hover { color:#6B7280; }
[x-cloak] { display:none !important; }
</style>

{{-- ── Alpine Component ── --}}
<script>
function userModal() {
    return {
        open:     false,
        mode:     'create', // 'create' | 'edit'
        editData: null,
        roleLabelMap: {
            citizen: 'Warga', employee: 'Pegawai', district_chief: 'Camat',
            regent: 'Bupati/Sekda', admin: 'Admin', super_admin: 'Super Admin',
        },

        init() {
            // Jika ada validation error, buka modal yang sesuai
            const hasError = document.querySelector('.text-error') !== null
                && !document.querySelector('[x-show]')?.closest('[x-data="userModal()"]');
            const modalMode = '{{ session("modal_mode", "") }}';
            const modalUserId = {{ session("modal_user_id", 0) }};

            if (document.querySelector('.bg-red-50') && modalMode === 'edit') {
                // Re-open edit modal with previous data
                // Data sudah di-pass via session, kita ambil dari tabel
                // Alpine akan handle via x-init pada error div
            }
        },

        openCreate() {
            this.mode = 'create';
            this.editData = null;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        openEdit(data) {
            this.mode = 'edit';
            this.editData = data;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        openEditById(id) {
            // Cari editData dari DOM (jika ada error redirect)
            // Fallback: tidak buka modal, user perlu klik edit lagi
        },

        close() {
            this.open = false;
            this.editData = null;
            document.body.style.overflow = '';
        },

        disableHiddenInputs(form) {
            // Disable input yang ada di section tersembunyi sebelum submit
            // sehingga nama field NIP tidak conflict
            form.querySelectorAll('[x-show]').forEach(function(el) {
                if (window.getComputedStyle(el).display === 'none') {
                    el.querySelectorAll('input, select, textarea').forEach(function(inp) {
                        inp.disabled = true;
                    });
                }
            });
        }
    };
}
</script>

@endsection