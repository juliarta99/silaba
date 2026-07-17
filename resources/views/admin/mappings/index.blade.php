@extends('layouts.admin')
@section('title', 'Pemetaan Kategori OPD')

@section('content')

<div x-data="mappingModal()" class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pemetaan Kategori OPD</h1>
            <p class="text-sm text-gray-500 mt-0.5">Petakan kategori laporan ke OPD yang bertanggung jawab</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Export CSV — bawa filter aktif --}}
            <a href="{{ route('admin.mappings.export') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200
                    bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 16 16">
                    <path d="M3 12.5h10M8 2v8m0 0-3-3m3 3 3-3"
                        stroke="currentColor" stroke-width="1.3"
                        stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export CSV
            </a>
            
            <button type="button" @@click="openAdd()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                        text-white text-sm font-semibold transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Tambah Pemetaan
            </button>
        </div>
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
    @if ($errors->any())
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-error">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 16 16">
            <path d="M8 5v4M8 10.5v.5M1.5 8a6.5 6.5 0 1013 0 6.5 6.5 0 00-13 0z"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach ([
            ['label' => 'Total Pemetaan',          'val' => $stats['total'],    'clr' => 'text-blue-600',   'ibg' => 'bg-blue-50',   'icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v10m0 0H5a2 2 0 01-2-2V9m6 4h10a2 2 0 002-2V9m-6 4v6m0 0H9m6 0a2 2 0 002-2v-4'],
            ['label' => 'Kategori Terpetakan',     'val' => $stats['mapped'],   'clr' => 'text-success',    'ibg' => 'bg-green-50',  'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
            ['label' => 'Kategori Belum Dipetakan','val' => $stats['unmapped'],
             'clr' => $stats['unmapped'] > 0 ? 'text-orange-600' : 'text-gray-300',
             'ibg' => $stats['unmapped'] > 0 ? 'bg-orange-50'   : 'bg-gray-10',
             'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ] as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
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
    <form method="GET" action="{{ route('admin.mappings.index') }}">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari kategori atau OPD..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm
                              placeholder-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
            </div>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold">Cari</button>
            @if ($search)
            <a href="{{ route('admin.mappings.index') }}"
               class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-10">Reset</a>
            @endif
        </div>
    </form>

    {{-- ── Kategori Belum Dipetakan ── --}}
    @if ($unmapped->count() > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-orange-200">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <p class="text-sm font-bold text-orange-800">Kategori Belum Dipetakan</p>
                    <p class="text-xs text-orange-600">{{ $unmapped->count() }} kategori belum memiliki OPD penanggung jawab</p>
                </div>
            </div>
        </div>
        <div class="p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach ($unmapped as $cat)
            <div class="bg-white rounded-xl border border-orange-200 p-3 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        @if ($cat->icon)
                        <img src="{{ $cat->icon }}" class="w-5 h-5 object-contain" alt=""
                             onerror="this.style.display='none'">
                        @else
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                            <path d="M2 4h12M2 8h8M2 12h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $cat->name }}</p>
                        <p class="text-[10px] text-gray-400">ID: KAT-{{ str_pad($cat->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <button type="button"
                        @@click="openAddFor({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                        class="w-7 h-7 rounded-lg bg-primary-50 hover:bg-primary-100 flex items-center justify-center
                               text-primary-500 shrink-0 transition-colors"
                        title="Petakan ke OPD">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M7 2v10M2 7h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Grup per OPD ── --}}
    <div class="space-y-4">
        @foreach ($deptsSorted as $dept)
        @php
        $cats = $grouped->get($dept->id, collect());
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- OPD Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-10/60">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $dept->name }}</p>
                        <p class="text-xs text-gray-400">{{ $dept->code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-2xl font-bold text-blue-600 tabular-nums">{{ $cats->count() }}</p>
                        <p class="text-xs text-gray-400">Kategori</p>
                    </div>
                    <button type="button"
                            @@click="openAddToDept({{ $dept->id }}, '{{ addslashes($dept->name) }}')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100
                                   text-primary-600 text-xs font-semibold transition-colors border border-primary-200">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                            <path d="M6 2v8M2 6h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            {{-- Kategori Cards --}}
            @if ($cats->count() > 0)
            <div class="p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($cats as $cat)
                @php
                $hasActive = $cat->reports()->whereNotIn('status',['completed','rejected'])->count() > 0;
                @endphp
                <div class="border border-gray-100 rounded-xl p-3 flex items-center justify-between gap-2
                            hover:border-gray-200 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            @if ($cat->icon)
                            <img src="{{ $cat->icon }}" class="w-5 h-5 object-contain" alt=""
                                 onerror="this.style.display='none'">
                            @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                                <path d="M2 4h12M2 8h8M2 12h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                            </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-900 truncate">{{ $cat->name }}</p>
                            <p class="text-[10px] text-gray-400">ID: KAT-{{ str_pad($cat->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    {{-- Hapus pemetaan --}}
                    <button type="button"
                            @@click="openRemove({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $hasActive ? 'true' : 'false' }})"
                            class="w-7 h-7 rounded-lg {{ $hasActive ? 'bg-gray-100 text-gray-300 cursor-not-allowed' : 'bg-red-50 hover:bg-red-100 text-error' }}
                                   flex items-center justify-center shrink-0 transition-colors"
                            :title="'{{ $hasActive ? 'Ada laporan aktif' : 'Hapus pemetaan' }}'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                            <path d="M1.75 3.5h10.5M4.667 3.5V2.333h4.666V3.5M5.833 6.417v3.5M8.167 6.417v3.5M2.917 3.5l.583 8.167h7l.583-8.167"
                                  stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-6 text-sm text-gray-400 text-center">Belum ada kategori dipetakan ke OPD ini</div>
            @endif
        </div>
        @endforeach
    </div>

    @if ($grouped->count() === 0 && $unmapped->count() === 0)
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-sm font-semibold text-gray-700 mb-1">Tidak ada data pemetaan</p>
        <p class="text-xs text-gray-400">Tambahkan kategori atau OPD terlebih dahulu</p>
    </div>
    @endif


    {{-- ════ MODAL TAMBAH PEMETAAN ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'add'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Tambah Pemetaan Kategori</h2>
                <button type="button" @@click="close()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.mappings.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    {{-- Info preset jika dari kategori atau dari dept --}}
                    <div x-show="presetCatName" class="flex items-center gap-2 px-4 py-3 rounded-xl bg-blue-50 border border-blue-100">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 16 16">
                            <path d="M2 4h12M2 8h8M2 12h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <p class="text-xs text-blue-700">
                            Memetakan kategori: <strong x-text="presetCatName"></strong>
                        </p>
                    </div>
                    <div x-show="presetDeptName" class="flex items-center gap-2 px-4 py-3 rounded-xl bg-blue-50 border border-blue-100">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 16 16">
                            <path d="M1 13V3a2 2 0 012-2h10a2 2 0 012 2v10M5 7h2m-2 3h2M9 7h2m-2 3h2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <p class="text-xs text-blue-700">
                            Menambahkan ke OPD: <strong x-text="presetDeptName"></strong>
                        </p>
                    </div>

                    {{-- Pilih Kategori --}}
                    <div>
                        <label class="label-sm">Kategori <span class="text-error">*</span></label>
                        <select name="category_id" id="map-category-id" required class="input-field">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($allCategories as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                                @if ($c->department) (sudah di {{ $c->department->name }}) @else (belum dipetakan) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih OPD --}}
                    <div>
                        <label class="label-sm">OPD Tujuan <span class="text-error">*</span></label>
                        <select name="department_id" id="map-dept-id" required class="input-field">
                            <option value="">-- Pilih OPD --</option>
                            @foreach ($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-200 text-xs text-yellow-800">
                        Jika kategori sudah dipetakan ke OPD lain, pemetaan lama akan diganti dengan pemetaan baru ini.
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                    <button type="button" @@click="close()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold">
                        Simpan Pemetaan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>


    {{-- ════ MODAL KONFIRMASI HAPUS PEMETAAN ════ --}}
    <template x-teleport="body">
    <div x-show="openMode === 'remove'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45);"
         @@keydown.escape.window="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" @@click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     :class="removeData?.hasActive ? 'bg-orange-100' : 'bg-red-100'">
                    <svg class="w-8 h-8" :class="removeData?.hasActive ? 'text-orange-500' : 'text-error'"
                         fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <template x-if="removeData?.hasActive">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Tidak Dapat Dihapus</h3>
                        <p class="text-sm text-gray-500">
                            Pemetaan kategori <span class="font-semibold" x-text="removeData?.name"></span>
                            tidak dapat dihapus karena masih ada laporan aktif.
                        </p>
                        <p class="text-xs text-gray-400 mt-3">Selesaikan semua laporan aktif terlebih dahulu.</p>
                    </div>
                </template>

                <template x-if="!removeData?.hasActive">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Pemetaan?</h3>
                        <p class="text-sm text-gray-500">
                            Kategori <span class="font-semibold text-gray-900" x-text="removeData?.name"></span>
                            akan dilepas dari OPD dan berstatus belum dipetakan.
                        </p>
                        <p class="text-xs text-gray-400 mt-3">Data laporan tidak akan terhapus.</p>
                    </div>
                </template>
            </div>

            <div class="px-6 pb-6 flex gap-3">
                <button type="button" @@click="close()"
                        class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-10">
                    <span x-text="removeData?.hasActive ? 'Tutup' : 'Batal'"></span>
                </button>
                <template x-if="removeData && !removeData.hasActive">
                    <form :action="`{{ url('admin/pemetaan') }}/${removeData.id}`" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-error hover:bg-red-700 text-white text-sm font-semibold">
                            Ya, Hapus Pemetaan
                        </button>
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
function mappingModal() {
    return {
        openMode:     null,
        removeData:   null,
        presetCatId:  null,
        presetCatName: null,
        presetDeptId:  null,
        presetDeptName: null,

        openAdd() {
            this.openMode      = 'add';
            this.presetCatId   = null;
            this.presetCatName = null;
            this.presetDeptId  = null;
            this.presetDeptName= null;
            this.removeData    = null;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                this.$nextTick(() => {
                    const cat  = document.getElementById('map-category-id');
                    const dept = document.getElementById('map-dept-id');
                    if (cat)  cat.value  = '';
                    if (dept) dept.value = '';
                });
            });
        },

        // Dari tombol "+" di kartu kategori belum dipetakan
        openAddFor(catId, catName) {
            this.openMode      = 'add';
            this.presetCatId   = catId;
            this.presetCatName = catName;
            this.presetDeptId  = null;
            this.presetDeptName= null;
            this.removeData    = null;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                this.$nextTick(() => {
                    const cat = document.getElementById('map-category-id');
                    if (cat) cat.value = catId;
                    const dept = document.getElementById('map-dept-id');
                    if (dept) dept.value = '';
                });
            });
        },

        // Dari tombol "Tambah" di header OPD
        openAddToDept(deptId, deptName) {
            this.openMode      = 'add';
            this.presetDeptId  = deptId;
            this.presetDeptName= deptName;
            this.presetCatId   = null;
            this.presetCatName = null;
            this.removeData    = null;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                this.$nextTick(() => {
                    const dept = document.getElementById('map-dept-id');
                    if (dept) dept.value = deptId;
                    const cat = document.getElementById('map-category-id');
                    if (cat) cat.value = '';
                });
            });
        },

        openRemove(catId, catName, hasActive) {
            this.openMode   = 'remove';
            this.removeData = { id: catId, name: catName, hasActive };
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.openMode      = null;
            this.removeData    = null;
            this.presetCatId   = null;
            this.presetCatName = null;
            this.presetDeptId  = null;
            this.presetDeptName= null;
            document.body.style.overflow = '';
        },
    };
}
</script>

@endsection