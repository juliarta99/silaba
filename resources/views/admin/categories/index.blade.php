@extends('layouts.admin')
@section('title', 'Manajemen Kategori')

@section('content')

@php
// Warna berbeda per OPD agar kartu lebih bervariasi
$colorPalette = [
    'bg-red-50 text-error border-red-100',
    'bg-blue-50 text-blue-600 border-blue-100',
    'bg-green-50 text-success border-green-100',
    'bg-purple-50 text-purple-600 border-purple-100',
    'bg-orange-50 text-orange-600 border-orange-100',
    'bg-teal-50 text-teal-600 border-teal-100',
    'bg-yellow-50 text-yellow-700 border-yellow-100',
    'bg-pink-50 text-pink-600 border-pink-100',
];
$dotColors = ['bg-red-400','bg-blue-400','bg-green-400','bg-purple-400','bg-orange-400','bg-teal-400','bg-yellow-400','bg-pink-400'];
@endphp

<div x-data="categoryModal()" class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola kategori laporan untuk sistem pelaporan</p>
        </div>
        <button type="button" @@click="openCreate()"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                       text-white text-sm font-semibold transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Tambah Kategori
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
    @if ($errors->any() && !$errors->has('delete'))
    <div x-init="{{ session('modal_mode') === 'edit' ? 'openEditById('.session('modal_cat_id', 0).')' : 'openCreate()' }}"
         class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach ([
            ['label' => 'Total Kategori',       'val' => $stats['total'],         'clr' => 'text-blue-600',   'ibg' => 'bg-blue-50'],
            ['label' => 'Total Laporan',         'val' => number_format($stats['total_reports']), 'clr' => 'text-primary-500', 'ibg' => 'bg-red-50'],
            ['label' => 'Rata-rata per Kategori','val' => $stats['avg_reports'],   'clr' => 'text-orange-600', 'ibg' => 'bg-orange-50'],
        ] as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl {{ $s['ibg'] }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 {{ $s['clr'] }}" fill="none" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Filter ── --}}
    <form method="GET" action="{{ route('admin.categories.index') }}">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi kategori..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm
                              placeholder-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
            </div>
            <select name="department" onchange="this.form.submit()"
                    class="px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-700
                           focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
                <option value="">Semua OPD</option>
                @foreach ($departments as $d)
                <option value="{{ $d->id }}" {{ request('department') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold">Cari</button>
            @if (request()->hasAny(['search','department']))
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-10">Reset</a>
            @endif
        </div>
    </form>

    {{-- ── Cards Grid ── --}}
    @if ($categories->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($categories as $cat)
        @php
        $colorIdx = $cat->department_id ? ($cat->department_id % count($colorPalette)) : 0;
        $colorClass = $colorPalette[$colorIdx];
        $dotClass   = $dotColors[$colorIdx];
        $hasActive  = $cat->reports()->whereNotIn('status',['completed','rejected'])->count() > 0;

        $cardData = json_encode([
            'id'            => $cat->id,
            'name'          => $cat->name,
            'description'   => $cat->description ?? '',
            'icon'          => $cat->icon ?? '',
            'department_id' => $cat->department_id,
        ]);
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">

            {{-- Header --}}
            <div class="flex items-start gap-3">
                {{-- Icon box --}}
                <div class="w-12 h-12 rounded-xl border {{ $colorClass }} flex items-center justify-center shrink-0 text-xl">
                    @if ($cat->icon)
                    <img src="{{ $cat->icon }}" class="w-8 h-8 object-contain" alt=""
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <span style="display:none" class="w-8 h-8 items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                    </span>
                    @else
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $cat->name }}</h3>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5
                                 rounded-full bg-green-100 text-success mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Aktif
                    </span>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if ($cat->description)
            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $cat->description }}</p>
            @endif

            {{-- OPD Badge --}}
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 14 14">
                    <path d="M2 12V4.5L7 2l5 2.5V12M5 12V9h4v3" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-xs text-gray-500 truncate">{{ $cat->department?->name ?? '—' }}</span>
            </div>

            {{-- Total Laporan --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide font-semibold">Total Laporan</p>
                    <p class="text-xl font-bold tabular-nums" style="color: {{ ['#EF4444','#3B82F6','#22C55E','#A855F7','#F97316','#14B8A6','#EAB308','#EC4899'][$colorIdx] }}">
                        {{ number_format($cat->reports_count) }}
                    </p>
                </div>
                <div class="w-3 h-3 rounded-full {{ $dotClass }}"></div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-1 border-t border-gray-50">
                <button type="button" @@click="openEdit({{ $cardData }})"
                        class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                               border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-600
                               text-sm font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M9.917 2.333a1.65 1.65 0 112.333 2.334L4.667 12.25H2.333V9.917L9.917 2.333z"
                              stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit
                </button>
                <button type="button"
                        @@click="openDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->reports_count }}, {{ $hasActive ? 'true' : 'false' }})"
                        class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                               border border-red-200 bg-red-50 hover:bg-red-100 text-error
                               text-sm font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M1.75 3.5h10.5M4.667 3.5V2.333h4.666V3.5M5.833 6.417v3.5M8.167 6.417v3.5M2.917 3.5l.583 8.167h7l.583-8.167"
                              stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Hapus
                </button>
            </div>

            {{-- Warning laporan aktif --}}
            @if ($hasActive)
            <div class="flex items-start gap-2 px-3 py-2.5 rounded-xl bg-yellow-50 border border-yellow-200">
                <svg class="w-3.5 h-3.5 text-yellow-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 14 14">
                    <path d="M7 5.25v3M7 9.5v.25M1.167 12.25L7 2.333l5.833 9.917H1.167z"
                          stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-[10px] text-yellow-700 leading-relaxed">
                    Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif
                </p>
            </div>
            @endif

        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
            <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
        </svg>
        <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada kategori</p>
        <p class="text-xs text-gray-400">Klik "Tambah Kategori" untuk menambahkan data baru</p>
    </div>
    @endif


    {{-- ════════════════════════════
         MODAL CREATE
    ════════════════════════════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'create'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Tambah Kategori Baru</h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label class="label-sm">Nama Kategori <span class="text-error">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Infrastruktur Jalan, Kebersihan..."
                               class="input-field">
                        @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-sm">OPD / Dinas Penanggung Jawab <span class="text-error">*</span></label>
                        <select name="department_id" required class="input-field">
                            <option value="">-- Pilih OPD --</option>
                            @foreach ($departments as $d)
                            <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-sm">Deskripsi</label>
                        <textarea name="description" rows="3"
                                  placeholder="Jelaskan jenis laporan yang masuk dalam kategori ini..."
                                  class="input-field resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="label-sm">URL Icon <span class="text-xs font-normal text-gray-400">(opsional, URL gambar)</span></label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                               placeholder="https://..." class="input-field">
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                    <button type="button" @@click="close()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>


    {{-- ════════════════════════════
         MODAL EDIT
    ════════════════════════════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'edit'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">
                    Edit Kategori: <span x-text="editData?.name" class="text-primary-500"></span>
                </h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <template x-if="editData">
                <form :action="`{{ url('admin/kategori') }}/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="px-6 py-5 space-y-4">

                        <div>
                            <label class="label-sm">Nama Kategori <span class="text-error">*</span></label>
                            <input type="text" name="name" :value="editData.name" required
                                   class="input-field">
                            @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label-sm">OPD / Dinas Penanggung Jawab <span class="text-error">*</span></label>
                            <select name="department_id" id="edit-cat-dept" required class="input-field">
                                <option value="">-- Pilih OPD --</option>
                                @foreach ($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label-sm">Deskripsi</label>
                            <textarea name="description" id="edit-cat-desc" rows="3"
                                      class="input-field resize-none"
                                      placeholder="Jelaskan jenis laporan yang masuk dalam kategori ini..."></textarea>
                        </div>

                        <div>
                            <label class="label-sm">URL Icon <span class="text-xs font-normal text-gray-400">(opsional)</span></label>
                            <input type="text" name="icon" id="edit-cat-icon"
                                   placeholder="https://..." class="input-field">
                        </div>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button" @@click="close()"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    </template>


    {{-- ════════════════════════════
         MODAL HAPUS
    ════════════════════════════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'delete'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm"
             @@click.stop
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
                <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Kategori?</h3>
                <p class="text-sm text-gray-500 mb-1">
                    Anda akan menghapus kategori
                    <span class="font-semibold text-gray-900" x-text="deleteData?.name"></span>.
                </p>
                <p class="text-sm text-gray-500">
                    Total laporan terkait:
                    <span class="font-semibold" x-text="deleteData?.totalReports"></span>
                </p>

                <div x-show="deleteData?.hasActive"
                     class="mt-3 px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-200 text-xs text-yellow-800 text-left">
                    <p class="font-semibold mb-0.5">Tidak dapat dihapus</p>
                    <p>Masih terdapat laporan aktif pada kategori ini. Selesaikan semua laporan aktif sebelum menghapus kategori.</p>
                </div>

                <p class="text-xs text-gray-400 mt-3" x-show="!deleteData?.hasActive">Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <div class="px-6 pb-6 flex gap-3">
                <button type="button" @@click="close()"
                        class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">
                    Batal
                </button>
                <template x-if="deleteData && !deleteData.hasActive">
                    <form :action="`{{ url('admin/kategori') }}/${deleteData.id}`" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-error hover:bg-red-700 text-white text-sm font-semibold">
                            Ya, Hapus
                        </button>
                    </form>
                </template>
                <template x-if="deleteData && deleteData.hasActive">
                    <button type="button" @@click="close()"
                            class="flex-1 py-2.5 rounded-xl bg-gray-200 text-gray-500 text-sm font-semibold cursor-not-allowed">
                        Tidak Dapat Dihapus
                    </button>
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
function categoryModal() {
    return {
        openMode:   null,
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

            // Set select & textarea setelah x-if selesai render (double nextTick)
            this.$nextTick(() => {
                this.$nextTick(() => {
                    const dept = document.getElementById('edit-cat-dept');
                    const desc = document.getElementById('edit-cat-desc');
                    const icon = document.getElementById('edit-cat-icon');

                    if (dept) dept.value = data.department_id || '';
                    if (desc) desc.value = data.description  || '';
                    if (icon) icon.value = data.icon         || '';
                });
            });
        },

        openDelete(id, name, totalReports, hasActive) {
            this.openMode   = 'delete';
            this.deleteData = { id, name, totalReports, hasActive };
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