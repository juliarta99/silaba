@extends('layouts.admin')
@section('title', 'Manajemen OPD')

@section('content')

<div x-data="deptModal()" class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen OPD</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola Organisasi Perangkat Daerah dan Kepala Dinas</p>
        </div>
        <button type="button" @@click="openCreate()"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                       text-white text-sm font-semibold transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah OPD
        </button>
    </div>

    {{-- ── Flash / Error ── --}}
    @if (session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-sm text-success">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 16 16">
            <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->has('delete'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first('delete') }}
    </div>
    @endif
    @if ($errors->has('head_error'))
    <div x-init="openHeadById({{ session('modal_dept_id', 0) }})"
         class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first('head_error') }}
    </div>
    @endif
    @if ($errors->any() && !$errors->has('delete') && !$errors->has('head_error'))
    <div x-init="{{ session('modal_mode') === 'edit' ? 'openEditById('.session('modal_dept_id',0).')' : 'openCreate()' }}"
         class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach ([
            ['label' => 'Total OPD',        'val' => $stats['total'],         'clr' => 'text-blue-600',   'ibg' => 'bg-blue-50',   'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['label' => 'Total Petugas',    'val' => $stats['total_petugas'], 'clr' => 'text-success',    'ibg' => 'bg-green-50',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'OPD Aktif',        'val' => $stats['aktif'],         'clr' => 'text-purple-600', 'ibg' => 'bg-purple-50', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Belum Ada Kadis',  'val' => $stats['belum_kadis'],   'clr' => $stats['belum_kadis'] > 0 ? 'text-orange-600' : 'text-gray-300', 'ibg' => $stats['belum_kadis'] > 0 ? 'bg-orange-50' : 'bg-gray-10', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ] as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl {{ $s['ibg'] }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 {{ $s['clr'] }}" fill="none" viewBox="0 0 24 24">
                    <path d="{{ $s['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Search ── --}}
    <form method="GET" action="{{ route('admin.departments.index') }}">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama OPD atau kepala dinas..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm
                              placeholder-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
            </div>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold">Cari</button>
            @if (request('search'))
            <a href="{{ route('admin.departments.index') }}"
               class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-10">Reset</a>
            @endif
        </div>
    </form>

    {{-- ── Cards Grid ── --}}
    @if ($departments->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach ($departments as $dept)
        @php
        $head    = $dept->headOfDepartment;
        $hasHead = $head !== null;

        $editData = json_encode([
            'id'      => $dept->id,
            'name'    => $dept->name,
            'code'    => $dept->code,
            'phone'   => $dept->phone,
            'email'   => $dept->email,
            'address' => $dept->address ?? '',
        ]);

        $headData = json_encode([
            'id'      => $dept->id,
            'name'    => $dept->name,
            'hasHead' => $hasHead,
            'headName'=> $head?->user?->name ?? '',
        ]);
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h3 class="text-base font-bold text-gray-900 leading-snug truncate">{{ $dept->name }}</h3>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">{{ $dept->code }}</p>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-1
                                 {{ $hasHead ? 'bg-green-100 text-success' : 'bg-orange-100 text-orange-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $hasHead ? 'bg-success' : 'bg-orange-400' }}"></span>
                        {{ $hasHead ? 'Aktif' : 'Belum ada Kadis' }}
                    </span>
                </div>
            </div>

            {{-- Kepala Dinas --}}
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Kepala Dinas</p>
                @if ($hasHead)
                <p class="text-sm font-bold text-gray-900">{{ $head->user->name }}</p>
                <p class="text-xs text-gray-400">NIP: {{ $head->nip }}</p>
                @else
                <p class="text-sm font-semibold text-orange-500">Belum ditugaskan</p>
                @endif
            </div>

            {{-- Alamat & Kontak --}}
            <div class="space-y-1.5">
                @if ($dept->address)
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-0.5">Alamat</p>
                    <p class="text-sm text-gray-600">{{ $dept->address }}</p>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-0.5">Telepon</p>
                        <p class="text-sm text-gray-700">{{ $dept->phone }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-0.5">Email</p>
                        <p class="text-sm text-gray-700 truncate">{{ $dept->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-6 pt-2 pb-1 border-t border-gray-50">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                        <circle cx="8" cy="5" r="2.5" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M2 14c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <p class="text-[10px] text-gray-400">Petugas</p>
                        <p class="text-sm font-bold text-gray-900">{{ $dept->total_petugas }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                        <path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <p class="text-[10px] text-gray-400">Kategori</p>
                        <p class="text-sm font-bold text-gray-900">{{ $dept->categories_count }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                {{-- Tugaskan / Ubah Kadis --}}
                <button type="button" @@click="openHead({{ $headData }})"
                        class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold
                               transition-colors border
                               {{ $hasHead
                                   ? 'border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-600'
                                   : 'border-orange-200 bg-orange-50 hover:bg-orange-100 text-orange-600' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <circle cx="7" cy="4.5" r="2.5" stroke="currentColor" stroke-width="1.1"/>
                        <path d="M1.5 13c0-3.038 2.462-5.5 5.5-5.5S12.5 9.962 12.5 13" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                    </svg>
                    {{ $hasHead ? 'Ubah Kadis' : 'Tugaskan Kadis' }}
                </button>

                <button type="button" @@click="openEdit({{ $editData }})"
                        class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                               border border-gray-200 bg-gray-10 hover:bg-gray-100 text-gray-700
                               text-sm font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M9.917 2.333a1.65 1.65 0 112.333 2.334L4.667 12.25H2.333V9.917L9.917 2.333z"
                              stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit
                </button>

                <button type="button"
                        @@click="openDelete({{ $dept->id }}, '{{ addslashes($dept->name) }}')"
                        class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                               border border-red-200 bg-red-50 hover:bg-red-100 text-error
                               text-sm font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M1.75 3.5h10.5M4.667 3.5V2.333h4.666V3.5M5.833 6.417v3.5M8.167 6.417v3.5M2.917 3.5l.583 8.167h7l.583-8.167"
                              stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Hapus
                </button>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada OPD</p>
        <p class="text-xs text-gray-400">Klik "Tambah OPD" untuk menambahkan data baru</p>
    </div>
    @endif


    {{-- ════ MODAL TAMBAH OPD ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'create'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Tambah OPD Baru</h2>
                <button type="button" @@click="close()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="label-sm">Nama OPD <span class="text-error">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Dinas Pekerjaan Umum..." class="input-field">
                            @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label-sm">Kode OPD <span class="text-error">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" required
                                   placeholder="Contoh: PUPR" class="input-field uppercase">
                            @error('code') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label-sm">Nomor Telepon <span class="text-error">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   placeholder="0361-XXXXXX" class="input-field">
                            @error('phone') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="label-sm">Email Kantor <span class="text-error">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="opd@badungkab.go.id" class="input-field">
                            @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="label-sm">Alamat</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   placeholder="Jl. ..." class="input-field">
                        </div>
                    </div>
                    <div class="px-4 py-3 rounded-xl bg-blue-50 border border-blue-100 text-xs text-blue-700">
                        OPD baru akan berstatus <strong>Belum Aktif</strong> sampai Kepala Dinas ditugaskan.
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                    <button type="button" @@click="close()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">Simpan OPD</button>
                </div>
            </form>
        </div>
    </div>
    </template>


    {{-- ════ MODAL EDIT OPD ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'edit'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Edit OPD: <span x-text="editData?.name" class="text-primary-500"></span></h2>
                <button type="button" @@click="close()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
            </div>

            <template x-if="editData">
                <form :action="`{{ url('admin/opd') }}/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label-sm">Nama OPD <span class="text-error">*</span></label>
                                <input type="text" name="name" :value="editData.name" required class="input-field">
                            </div>
                            <div>
                                <label class="label-sm">Kode OPD <span class="text-error">*</span></label>
                                <input type="text" name="code" id="edit-dept-code" required class="input-field uppercase">
                            </div>
                            <div>
                                <label class="label-sm">Nomor Telepon <span class="text-error">*</span></label>
                                <input type="text" name="phone" id="edit-dept-phone" required class="input-field">
                            </div>
                            <div class="col-span-2">
                                <label class="label-sm">Email Kantor <span class="text-error">*</span></label>
                                <input type="email" name="email" id="edit-dept-email" required class="input-field">
                            </div>
                            <div class="col-span-2">
                                <label class="label-sm">Alamat</label>
                                <input type="text" name="address" id="edit-dept-address" class="input-field">
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button" @@click="close()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    </template>


    {{-- ════ MODAL TUGASKAN / UBAH KADIS ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'head'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-900"
                        x-text="headData?.hasHead ? 'Ubah Kepala Dinas' : 'Tugaskan Kepala Dinas'"></h2>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="headData?.name"></p>
                </div>
                <button type="button" @@click="close()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
            </div>

            <template x-if="headData">
                <form :action="`{{ url('admin/opd') }}/${headData.id}/kadis`" method="POST"
                      x-data="{ mode: 'existing' }">
                    @csrf
                    <div class="px-6 py-5 space-y-4">

                        {{-- Kadis saat ini --}}
                        <div x-show="headData.hasHead"
                             class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-10 border border-gray-200">
                            <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 16 16">
                                    <circle cx="8" cy="5" r="2.5" stroke="currentColor" stroke-width="1.2"/>
                                    <path d="M2 14c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wide">Kadis Saat Ini</p>
                                <p class="text-sm font-bold text-gray-900" x-text="headData.headName"></p>
                            </div>
                        </div>

                        {{-- Tab mode --}}
                        <div class="flex gap-2 p-1 bg-gray-100 rounded-xl">
                            <button type="button" @@click="mode = 'existing'"
                                    :class="mode === 'existing' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all">
                                Pilih dari Pegawai
                            </button>
                            <button type="button" @@click="mode = 'new'"
                                    :class="mode === 'new' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all">
                                Tambah Pegawai Baru
                            </button>
                        </div>

                        <input type="hidden" name="mode" :value="mode">

                        {{-- Mode: pilih pegawai existing --}}
                        <div x-show="mode === 'existing'" class="space-y-3">
                            <div>
                                <label class="label-sm">Pilih Pegawai <span class="text-error">*</span></label>
                                <select name="employee_user_id" id="head-user-select" class="input-field">
                                    <option value="">-- Pilih Kepala Dinas --</option>
                                    @foreach ($candidateUsers as $cu)
                                    <option value="{{ $cu->id }}">
                                        {{ $cu->name }} — {{ $cu->employee?->department?->name ?? 'Belum ada dept' }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Hanya menampilkan pegawai dengan posisi Kepala Dinas</p>
                            </div>
                        </div>

                        {{-- Mode: buat pegawai baru --}}
                        <div x-show="mode === 'new'" class="space-y-3">
                            <div>
                                <label class="label-sm">Nama Lengkap <span class="text-error">*</span></label>
                                <input type="text" name="name" placeholder="Nama Kepala Dinas" class="input-field">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label-sm">NIP <span class="text-error">*</span></label>
                                    <input type="text" name="nip" placeholder="19XXXXXXXXXXXXXXXX" class="input-field font-mono">
                                </div>
                                <div>
                                    <label class="label-sm">Password Awal <span class="text-error">*</span></label>
                                    <input type="password" name="password" placeholder="Min. 8 karakter" class="input-field">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label-sm">Email <span class="text-error">*</span></label>
                                    <input type="email" name="email" class="input-field">
                                </div>
                                <div>
                                    <label class="label-sm">Nomor HP <span class="text-error">*</span></label>
                                    <input type="text" name="phone" class="input-field">
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('nip') || $errors->has('email'))
                        <p class="text-xs text-error">{{ $errors->first() }}</p>
                        @endif
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button" @@click="close()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold"
                                x-text="headData?.hasHead ? 'Simpan Perubahan Kadis' : 'Tugaskan sebagai Kadis'"></button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    </template>


    {{-- ════ MODAL HAPUS ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'delete'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-error" fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Hapus OPD?</h3>
                <p class="text-sm text-gray-500 mb-1">
                    Anda akan menghapus OPD <span class="font-semibold text-gray-900" x-text="deleteData?.name"></span>.
                </p>
                <p class="text-xs text-gray-400 mt-3">Semua kategori dan data terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" @@click="close()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">Batal</button>
                <template x-if="deleteData">
                    <form :action="`{{ url('admin/opd') }}/${deleteData.id}`" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-error hover:bg-red-700 text-white text-sm font-semibold">Ya, Hapus</button>
                    </form>
                </template>
            </div>
        </div>
    </div>
    </template>

</div>

<style>
.input-field { width:100%; padding:.75rem 1rem; border-radius:.75rem; border:1px solid #E5E7EB; background:#F9FAFB; font-size:.875rem; color:#111827; outline:none; transition:all .15s; }
.input-field:focus { background:white; border-color:#C01818; box-shadow:0 0 0 1px #C01818; }
.label-sm { display:block; font-size:.75rem; font-weight:600; color:#374151; margin-bottom:.375rem; }
[x-cloak] { display:none !important; }
</style>

<script>
function deptModal() {
    return {
        openMode:   null,
        editData:   null,
        headData:   null,
        deleteData: null,

        openCreate() {
            this.openMode = 'create'; this.editData = null; this.headData = null; this.deleteData = null;
            document.body.style.overflow = 'hidden';
        },

        openEdit(data) {
            this.openMode = 'edit'; this.editData = data; this.headData = null; this.deleteData = null;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                this.$nextTick(() => {
                    const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val || ''; };
                    set('edit-dept-code',    data.code);
                    set('edit-dept-phone',   data.phone);
                    set('edit-dept-email',   data.email);
                    set('edit-dept-address', data.address);
                });
            });
        },

        openEditById(id) { /* fallback saat error redirect */ },

        openHead(data) {
            this.openMode = 'head'; this.headData = data; this.editData = null; this.deleteData = null;
            document.body.style.overflow = 'hidden';
        },

        openHeadById(id) { /* fallback */ },

        openDelete(id, name) {
            this.openMode = 'delete'; this.deleteData = { id, name }; this.editData = null; this.headData = null;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.openMode = null; this.editData = null; this.headData = null; this.deleteData = null;
            document.body.style.overflow = '';
        },
    };
}
</script>

@endsection