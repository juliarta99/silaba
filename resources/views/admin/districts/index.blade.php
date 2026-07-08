@extends('layouts.admin')
@section('title', 'Manajemen Kecamatan')

@section('content')

<div x-data="districtModal()" class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kecamatan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data kecamatan di Kabupaten Badung</p>
        </div>
        <button type="button" @@click="openCreate()"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                       text-white text-sm font-semibold transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Kecamatan
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
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 16 16">
            <path d="M8 5v4M8 10.5v.5M1.5 8a6.5 6.5 0 1013 0 6.5 6.5 0 00-13 0z"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        {{ $errors->first('delete') }}
    </div>
    @endif
    @if ($errors->any() && ! $errors->has('delete'))
    <div x-init="
        @if(session('modal_mode') === 'edit')
            openEdit({{ session('modal_data', '{}') }})
        @else
            openCreate()
        @endif
    " class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-400 mt-0.5">Total Kecamatan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-success" fill="none" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ number_format($stats['total_reports']) }}</p>
                <p class="text-sm text-gray-400 mt-0.5">Total Laporan</p>
            </div>
        </div>
    </div>

    {{-- ── Search ── --}}
    <form method="GET" action="{{ route('admin.districts.index') }}">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama kecamatan atau camat..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm
                              placeholder-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
            </div>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold transition-colors">
                Cari
            </button>
            @if (request('search'))
            <a href="{{ route('admin.districts.index') }}"
               class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-10 transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- ── Cards Grid ── --}}
    @if ($districts->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($districts as $district)
        @php
        $chief = $district->activeChief;
        $chiefName = $chief?->user?->name ?? '—';
        $reportCount = $district->reports_count ?? 0;

        $cardData = json_encode([
            'id'    => $district->id,
            'name'  => $district->name,
            'email' => $district->email,
            'phone' => $district->phone,
        ]);
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-4
                    hover:shadow-md transition-shadow">

            {{-- Card Header --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">{{ $district->name }}</h3>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5
                                     rounded-full bg-green-100 text-success mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Aktif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Camat Info --}}
            <div class="space-y-1.5">
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Camat</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $chiefName }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Alamat & Kontak</p>
                    <div class="flex items-center gap-1.5 text-sm text-gray-600 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 14 14">
                            <path d="M13 9.333c0 .196-.044.39-.133.577-.088.188-.221.366-.399.529-.295.267-.618.4-.961.4-.246 0-.511-.06-.798-.18a7.996 7.996 0 01-.789-.425 13.147 13.147 0 01-.769-.578 12.855 12.855 0 01-.74-.744 13.062 13.062 0 01-.578-.763 8.1 8.1 0 01-.419-.783c-.118-.287-.177-.56-.177-.819 0-.252.055-.493.165-.712.109-.22.272-.419.496-.591.272-.203.564-.304.866-.304.117 0 .234.025.34.074.109.05.204.123.277.231l.963 1.357c.073.105.128.2.166.291.038.09.059.176.059.255 0 .098-.03.197-.087.291a1.42 1.42 0 01-.232.29l-.315.329a.223.223 0 00-.063.167c0 .034.005.065.015.098.015.035.03.061.039.087.073.132.197.305.372.514.18.209.374.421.583.633.217.212.424.408.634.587.208.174.381.295.518.364.024.01.052.02.083.03a.327.327 0 00.093.014.23.23 0 00.171-.07l.315-.312c.093-.093.186-.165.276-.208a.553.553 0 01.26-.063c.078 0 .16.019.249.059.09.039.182.093.282.165l1.373.977c.107.073.181.162.225.266.04.104.063.208.063.323z"
                                  stroke="currentColor" stroke-width=".9"/>
                        </svg>
                        {{ $district->phone }}
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-gray-600 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 14 14">
                            <rect x="1.167" y="3.5" width="11.667" height="7.583" rx="1.167" stroke="currentColor" stroke-width="1.05"/>
                            <path d="M1.167 5.25l5.25 3.208L11.667 5.25" stroke="currentColor" stroke-width="1.05" stroke-linecap="round"/>
                        </svg>
                        {{ $district->email }}
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-4 pt-3 border-t border-gray-50">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Laporan</p>
                    <p class="text-lg font-bold text-gray-900 tabular-nums">{{ number_format($reportCount) }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-1">
                <button type="button" @@click="openEdit({{ $cardData }})"
                        class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl
                               border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-600
                               text-sm font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M9.917 2.333a1.65 1.65 0 112.333 2.334L4.667 12.25H2.333V9.917L9.917 2.333z"
                              stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit
                </button>
                <button type="button" @@click="openDelete({{ $district->id }}, '{{ addslashes($district->name) }}', {{ $reportCount }})"
                        class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl
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
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
        </svg>
        <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada kecamatan</p>
        <p class="text-xs text-gray-400">Klik "Tambah Kecamatan" untuk menambahkan data baru</p>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════
         MODAL CREATE
    ══════════════════════════════════════════════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'create'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Tambah Kecamatan</h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.districts.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label class="label-sm">Nama Kecamatan <span class="text-error">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Kuta, Mengwi, Abiansemal..."
                               class="input-field">
                        @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-sm">Email Kantor <span class="text-error">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="kec.nama@badungkab.go.id"
                               class="input-field">
                        @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-sm">Nomor Telepon <span class="text-error">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               placeholder="0361-XXXXXX"
                               class="input-field">
                        @error('phone') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                    <button type="button" @@click="close()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                        Simpan Kecamatan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>


    {{-- ══════════════════════════════════════════════
         MODAL EDIT
    ══════════════════════════════════════════════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'edit'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">
                    Edit Kecamatan <span x-text="editData?.name" class="text-primary-500"></span>
                </h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <template x-if="editData">
                <form :action="`{{ url('admin/kecamatan') }}/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="px-6 py-5 space-y-4">

                        <div>
                            <label class="label-sm">Nama Kecamatan <span class="text-error">*</span></label>
                            <input type="text" name="name" :value="editData.name" required
                                   placeholder="Nama kecamatan"
                                   class="input-field">
                            @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label-sm">Email Kantor <span class="text-error">*</span></label>
                            <input type="email" name="email" :value="editData.email" required
                                   class="input-field">
                            @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label-sm">Nomor Telepon <span class="text-error">*</span></label>
                            <input type="text" name="phone" :value="editData.phone" required
                                   class="input-field">
                            @error('phone') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button" @@click="close()"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    </template>


    {{-- ══════════════════════════════════════════════
         MODAL KONFIRMASI HAPUS
    ══════════════════════════════════════════════ --}}
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
                {{-- Icon warning --}}
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-error" fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Kecamatan?</h3>
                <p class="text-sm text-gray-500 mb-1">
                    Anda akan menghapus kecamatan
                    <span class="font-semibold text-gray-900" x-text="deleteData?.name"></span>.
                </p>

                {{-- Warning jika ada laporan --}}
                <div x-show="deleteData?.reportCount > 0"
                     class="mt-3 px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-200 text-xs text-yellow-800 text-left">
                    <p class="font-semibold mb-0.5">⚠️ Perhatian</p>
                    <p>Kecamatan ini memiliki <strong x-text="deleteData?.reportCount"></strong> laporan.
                       Laporan aktif tidak dapat dihapus.</p>
                </div>

                <p class="text-xs text-gray-400 mt-3">Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <div class="px-6 pb-6 flex gap-3">
                <button type="button" @@click="close()"
                        class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors">
                    Batal
                </button>

                <template x-if="deleteData">
                    <form :action="`{{ url('admin/kecamatan') }}/${deleteData.id}`" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-error hover:bg-red-700 text-white text-sm font-semibold transition-colors">
                            Ya, Hapus
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>
    </template>

</div>

{{-- ── Styles ── --}}
<style>
.input-field {
    width:100%; padding:.75rem 1rem; border-radius:.75rem;
    border:1px solid #E5E7EB; background:#F9FAFB; font-size:.875rem; color:#111827;
    outline:none; transition:all .15s;
}
.input-field:focus { background:white; border-color:#C01818; box-shadow:0 0 0 1px #C01818; }
.label-sm { display:block; font-size:.75rem; font-weight:600; color:#374151; margin-bottom:.375rem; }
[x-cloak] { display:none !important; }
</style>

{{-- ── Alpine ── --}}
<script>
function districtModal() {
    return {
        openMode:   null,   // null | 'create' | 'edit' | 'delete'
        editData:   null,
        deleteData: null,

        openCreate() {
            this.openMode   = 'create';
            this.editData   = null;
            this.deleteData = null;
            document.body.style.overflow = 'hidden';
        },

        openEdit(data) {
            this.openMode   = 'edit';
            this.editData   = data;
            this.deleteData = null;
            document.body.style.overflow = 'hidden';
        },

        openDelete(id, name, reportCount) {
            this.openMode   = 'delete';
            this.deleteData = { id, name, reportCount };
            this.editData   = null;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.openMode   = null;
            this.editData   = null;
            this.deleteData = null;
            document.body.style.overflow = '';
        },
    };
}
</script>

@endsection