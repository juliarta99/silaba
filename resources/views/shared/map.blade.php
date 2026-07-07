@extends('layouts.app')
@section('title', 'Peta Sebaran Laporan — SILABU')

@section('content')

@php
$statusConfig = [
    'pending'               => ['label' => 'Menunggu Penugasan', 'dot' => 'bg-red-500',    'text' => 'text-error'],
    'in_progress'           => ['label' => 'Diproses',           'dot' => 'bg-blue-500',   'text' => 'text-blue-600'],
    'waiting_for_materials' => ['label' => 'Menunggu Material',  'dot' => 'bg-orange-400', 'text' => 'text-orange-600'],
    'under_review'          => ['label' => 'Sedang Ditinjau',    'dot' => 'bg-yellow-400', 'text' => 'text-yellow-600'],
    'completed'             => ['label' => 'Selesai',            'dot' => 'bg-green-500',  'text' => 'text-success'],
    'rejected'              => ['label' => 'Ditolak',            'dot' => 'bg-gray-400',   'text' => 'text-gray-500'],
];
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$isFieldOfficer = ($role === 'employee' && $position === 'field_officer');
@endphp

{{-- Leaflet & Cluster CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>

<style>
#map { height: 500px; z-index: 1; }
@media (max-width:640px) { #map { height: 360px; } }
.silabu-popup .leaflet-popup-content-wrapper {
    border-radius:14px; padding:10px; box-shadow:0 4px 24px rgba(0,0,0,.15);
}
.silabu-popup .leaflet-popup-tip { background:white; }
.fullscreen-map {
    position:fixed !important; top:68px !important; left:0 !important;
    right:0 !important; bottom:0 !important; z-index:999 !important;
    height:calc(100vh - 68px) !important; border-radius:0 !important;
}
</style>

<div class="bg-gray-50 min-h-[calc(100vh-68px)] pb-10 pb-24 sm:pb-10"
     x-data="{ view: 'map' }">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ── Header ── --}}
        <div class="flex items-start justify-between gap-3 mb-5">
            <div>
                <a href="{{ $dashboardRoute }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Peta Sebaran Laporan</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $scopeNote }}
                    <span class="inline-flex items-center gap-1 ml-1.5 text-xs font-medium text-gray-400
                                 bg-gray-100 px-2 py-0.5 rounded-full">
                        {{ $roleLabel }}
                    </span>
                </p>
            </div>

            {{-- Toggle Peta/List --}}
            <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 shrink-0">
                <button type="button" @@click="view = 'map'"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all"
                        :class="view === 'map'
                            ? 'bg-primary-500 text-white shadow-sm'
                            : 'text-gray-500 hover:text-gray-700'">
                    Peta
                </button>
                <button type="button" @@click="view = 'list'"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all"
                        :class="view === 'list'
                            ? 'bg-primary-500 text-white shadow-sm'
                            : 'text-gray-500 hover:text-gray-700'">
                    List
                </button>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-4 gap-2.5 mb-5">
            @foreach ([
                ['label' => 'Total Laporan', 'val' => $stats['total'],      'clr' => 'text-gray-900'],
                ['label' => 'Menunggu',      'val' => $stats['pending'],    'clr' => 'text-error'],
                ['label' => 'Diproses',      'val' => $stats['inProgress'], 'clr' => 'text-blue-600'],
                ['label' => 'Selesai',       'val' => $stats['completed'],  'clr' => 'text-success'],
            ] as $s)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 sm:p-4 text-center">
                <p class="text-xl sm:text-2xl font-bold {{ $s['clr'] }} tabular-nums">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Filter ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
            <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Filter Peta
            </h2>
            <form method="GET" action="{{ request()->url() }}" x-data="{ expanded: {{ request()->hasAny(['date_from','date_to']) ? 'true' : 'false' }} }">

                {{-- Baris 1: Status + Kecamatan + Kategori --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach ([
                                'pending'               => 'Menunggu Penugasan',
                                'in_progress'           => 'Diproses',
                                'waiting_for_materials' => 'Menunggu Material',
                                'under_review'          => 'Sedang Ditinjau',
                                'completed'             => 'Selesai',
                                'rejected'              => 'Ditolak',
                            ] as $val => $lbl)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kecamatan (tidak untuk camat) --}}
                    @if ($showDistrictFilter)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Kecamatan</label>
                        <select name="district"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                onchange="this.form.submit()">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($districts as $d)
                            <option value="{{ $d->id }}" {{ request('district') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Kategori --}}
                    <div class="{{ $showDistrictFilter ? '' : 'col-span-2 sm:col-span-1' }}">
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Kategori</label>
                        <select name="category"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Baris 2: Date Range (collapsible) --}}
                <div class="border-t border-gray-50 pt-3 mb-3">
                    <button type="button" @@click="expanded = !expanded"
                            class="flex items-center gap-2 text-xs font-semibold text-gray-500
                                   hover:text-gray-800 transition-colors mb-3">
                        <svg class="w-3.5 h-3.5 transition-transform duration-200"
                             :class="expanded ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 14 14">
                            <path d="M3 5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Filter Rentang Tanggal
                        @if (request()->hasAny(['date_from','date_to']))
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                     bg-primary-100 text-primary-600 text-[10px] font-bold">
                            Aktif
                        </span>
                        @endif
                    </button>

                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="display:none;">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                            {{-- Shortcut periode --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Periode Cepat</label>
                                <select x-data
                                        @@change="
                                            const v = $event.target.value;
                                            const today = new Date();
                                            const fmt = d => d.toISOString().split('T')[0];
                                            const from = document.getElementById('date_from');
                                            const to   = document.getElementById('date_to');
                                            if (v === '7')       { const d = new Date(); d.setDate(d.getDate()-7);  from.value = fmt(d); to.value = fmt(today); }
                                            else if (v === '30') { const d = new Date(); d.setDate(d.getDate()-30); from.value = fmt(d); to.value = fmt(today); }
                                            else if (v === 'month') {
                                                const d = new Date(today.getFullYear(), today.getMonth(), 1);
                                                from.value = fmt(d); to.value = fmt(today);
                                            }
                                            else if (v === 'lastmonth') {
                                                const s = new Date(today.getFullYear(), today.getMonth()-1, 1);
                                                const e = new Date(today.getFullYear(), today.getMonth(), 0);
                                                from.value = fmt(s); to.value = fmt(e);
                                            }
                                            else if (v === 'year') {
                                                const d = new Date(today.getFullYear(), 0, 1);
                                                from.value = fmt(d); to.value = fmt(today);
                                            }
                                            else { from.value = ''; to.value = ''; }
                                        "
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                               text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                               focus:ring-primary-500 outline-none transition-all">
                                    <option value="">Pilih periode...</option>
                                    <option value="7">7 hari terakhir</option>
                                    <option value="30">30 hari terakhir</option>
                                    <option value="month">Bulan ini</option>
                                    <option value="lastmonth">Bulan lalu</option>
                                    <option value="year">Tahun ini</option>
                                </select>
                            </div>

                            {{-- Dari tanggal --}}
                            <div>
                                <label for="date_from" class="block text-xs font-medium text-gray-500 mb-1.5">
                                    Dari Tanggal
                                </label>
                                <input type="date" id="date_from" name="date_from"
                                       value="{{ request('date_from') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                              text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                              focus:ring-primary-500 outline-none transition-all">
                            </div>

                            {{-- Sampai tanggal --}}
                            <div>
                                <label for="date_to" class="block text-xs font-medium text-gray-500 mb-1.5">
                                    Sampai Tanggal
                                </label>
                                <input type="date" id="date_to" name="date_to"
                                       value="{{ request('date_to') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm
                                              text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                              focus:ring-primary-500 outline-none transition-all">
                            </div>

                        </div>

                        {{-- Tombol apply date filter --}}
                        <div class="flex items-center gap-2 mt-3">
                            <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-primary-500 hover:bg-primary-700
                                           text-white text-xs font-semibold transition-colors">
                                Terapkan Filter Tanggal
                            </button>
                            @if (request()->hasAny(['date_from','date_to']))
                            <a href="{{ request()->url() . '?' . http_build_query(request()->except(['date_from','date_to'])) }}"
                               class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600
                                      text-xs font-semibold hover:bg-gray-50 transition-colors">
                                Hapus Filter Tanggal
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info & Reset --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <p class="text-xs text-gray-500">
                            Menampilkan
                            <span class="font-semibold text-gray-800">{{ $markers->count() }}</span>
                            dari
                            <span class="font-semibold">{{ $stats['total'] }}</span>
                            laporan
                        </p>
                        @if (request()->hasAny(['date_from','date_to']))
                        <span class="text-xs text-gray-400">
                            |
                            @if (request('date_from') && request('date_to'))
                                {{ \Carbon\Carbon::parse(request('date_from'))->translatedFormat('j M Y') }}
                                — {{ \Carbon\Carbon::parse(request('date_to'))->translatedFormat('j M Y') }}
                            @elseif (request('date_from'))
                                sejak {{ \Carbon\Carbon::parse(request('date_from'))->translatedFormat('j M Y') }}
                            @else
                                s/d {{ \Carbon\Carbon::parse(request('date_to'))->translatedFormat('j M Y') }}
                            @endif
                        </span>
                        @endif
                    </div>
                    @if (request()->hasAny(['status','district','category','date_from','date_to']))
                    <a href="{{ request()->url() }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Reset Semua Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ══════ VIEW: PETA ══════ --}}
        <div x-show="view === 'map'" class="space-y-4">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div id="map" class="w-full"></div>
            </div>

            {{-- Legenda --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs font-bold text-gray-700 mb-3">Legenda Status</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-2 gap-x-4">
                    @foreach ([
                        ['dot' => 'bg-red-500',    'label' => 'Menunggu Penugasan'],
                        ['dot' => 'bg-blue-500',   'label' => 'Diproses'],
                        ['dot' => 'bg-orange-400', 'label' => 'Menunggu Material'],
                        ['dot' => 'bg-yellow-400', 'label' => 'Sedang Ditinjau'],
                        ['dot' => 'bg-green-500',  'label' => 'Selesai'],
                        ['dot' => 'bg-gray-400',   'label' => 'Ditolak'],
                    ] as $leg)
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full {{ $leg['dot'] }} shrink-0"></div>
                        <span class="text-xs text-gray-600">{{ $leg['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══════ VIEW: LIST ══════ --}}
        <div x-show="view === 'list'" style="display:none;">
            <h2 class="text-base font-bold text-gray-900 mb-3">Daftar Laporan pada Peta</h2>

            @if ($markers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($markers as $m)
                @php
                $st = $statusConfig[$m['status']] ?? $statusConfig['pending'];
                $pr = $priorityConfig[$m['priority']] ?? $priorityConfig['medium'];
                @endphp
                <a href="{{ $m['url'] }}"
                   class="flex flex-col gap-2 bg-white rounded-xl border border-gray-100 shadow-sm p-4
                          hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 font-mono">{{ $m['code'] }}</p>
                            <p class="text-sm font-bold text-gray-900 line-clamp-1 mt-0.5">{{ $m['title'] }}</p>
                        </div>
                        <div class="w-3 h-3 rounded-full {{ $st['dot'] }} shrink-0 mt-1"></div>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @if ($m['category'])
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $m['category'] }}</span>
                        @endif
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $pr['bg'] }} {{ $pr['text'] }}">
                            {{ $pr['label'] }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                        @if ($m['district'])
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                <path d="M6 1C4.07 1 2.5 2.57 2.5 4.5c0 2.625 3.5 6.5 3.5 6.5s3.5-3.875 3.5-6.5C9.5 2.57 7.93 1 6 1z"
                                      stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                            </svg>
                            {{ $m['district'] }}
                        </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                <circle cx="6" cy="4.5" r="2" stroke="currentColor" stroke-width="1.1"/>
                                <path d="M2 11c0-2.21 1.79-4 4-4s4 1.79 4 4"
                                      stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                            </svg>
                            Petugas: {{ $m['officer'] }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-14 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
                </svg>
                <p class="text-sm font-semibold text-gray-700">Tidak ada laporan ditemukan</p>
                <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau hapus filter yang aktif</p>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Petugas lapangan: bottom nav --}}
@if ($isFieldOfficer)
<x-employee.bottom-nav active="assignments" :badge="0" />
@endif

{{-- ══════ LEAFLET + CLUSTER SCRIPTS ══════ --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
(function () {
    const markers = @json($markers);

    const COLOR = {
        red:    '#EF4444',
        blue:   '#3B82F6',
        orange: '#F97316',
        yellow: '#EAB308',
        green:  '#22C55E',
        gray:   '#9CA3AF',
    };

    const STATUS_LABEL = {
        pending:               'Menunggu Penugasan',
        in_progress:           'Diproses',
        waiting_for_materials: 'Menunggu Material',
        under_review:          'Sedang Ditinjau',
        completed:             'Selesai',
        rejected:              'Ditolak',
    };

    const PRIORITY_LABEL = { critical:'Mendesak', high:'Tinggi', medium:'Sedang', low:'Rendah' };
    const PRIORITY_COLOR = { critical:'#FEE2E2,#B91C1C', high:'#FFEDD5,#C2410C', medium:'#FEF9C3,#92400E', low:'#DCFCE7,#15803D' };

    // ── Init peta ─────────────────────────────────────────────────────────
    const map = L.map('map', { zoomControl: false }).setView([-8.6478, 115.2118], 11);

    L.control.zoom({ position: 'topright' }).addTo(map);

    const osmTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19,
    });
    const satTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Esri', maxZoom: 19,
    });
    osmTile.addTo(map);
    L.control.layers({ 'Street': osmTile, 'Satelit': satTile }, {}, { position: 'topright' }).addTo(map);

    // ── Fullscreen button ─────────────────────────────────────────────────
    const fsCtrl = L.control({ position: 'topright' });
    fsCtrl.onAdd = function () {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
        div.innerHTML = `<a href="#" title="Layar penuh"
            style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;">
            <svg width="14" height="14" fill="none" viewBox="0 0 14 14">
                <path d="M1 5V1h4M13 5V1H9M1 9v4h4M13 9v4H9"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></a>`;
        L.DomEvent.on(div, 'click', function (e) {
            L.DomEvent.preventDefault(e);
            const el = document.getElementById('map');
            el.classList.toggle('fullscreen-map');
            el.style.height = el.classList.contains('fullscreen-map') ? 'calc(100vh - 68px)' : '';
            setTimeout(() => map.invalidateSize(), 100);
        });
        return div;
    };
    fsCtrl.addTo(map);

    // ── Cluster group ─────────────────────────────────────────────────────
    const cluster = L.markerClusterGroup({
        iconCreateFunction(c) {
            const n = c.getChildCount();
            return L.divIcon({
                html: `<div style="background:#C01818;color:white;border-radius:50%;
                    width:36px;height:36px;display:flex;align-items:center;justify-content:center;
                    font-weight:700;font-size:13px;border:3px solid white;
                    box-shadow:0 2px 8px rgba(0,0,0,.3)">${n}</div>`,
                className: '', iconSize: [36, 36], iconAnchor: [18, 18],
            });
        },
        maxClusterRadius: 60,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
    });

    if (markers.length === 0) {
        L.divIcon({ html: '<div style="font-size:13px;color:#6B7280;padding:8px">Tidak ada laporan</div>', className: '' });
    } else {
        markers.forEach(function (m) {
            const color = COLOR[m.color] || '#C01818';
            const code  = m.code.replace(/^TKT-\d{4}-/, ''); // "015" dari "TKT-2026-015"
            const isPending = m.status === 'pending';

            const icon = L.divIcon({
                html: `<div style="position:relative;width:36px;height:44px;">
                    <svg viewBox="0 0 36 44" width="36" height="44" fill="none">
                        <path d="M18 0C8.059 0 0 8.059 0 18c0 9.941 18 26 18 26S36 27.941 36 18C36 8.059 27.941 0 18 0z" fill="${color}"/>
                        <circle cx="18" cy="18" r="9" fill="rgba(255,255,255,0.25)"/>
                    </svg>
                    <span style="position:absolute;top:8px;left:0;width:36px;text-align:center;
                        color:white;font-weight:700;font-size:9px;font-family:monospace;
                        letter-spacing:-0.5px;line-height:1">${code}</span>
                    ${isPending ? `<span style="position:absolute;top:-3px;right:-3px;
                        width:10px;height:10px;background:#EF4444;border-radius:50%;
                        border:2px solid white;"></span>` : ''}
                </div>`,
                className: '',
                iconSize:   [36, 44],
                iconAnchor: [18, 44],
                popupAnchor:[0, -46],
            });

            // Officers HTML
            const officersHtml = m.all_officers.length
                ? m.all_officers.map(n =>
                    `<span style="display:inline-block;background:#FEF2F2;color:#C01818;
                        padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;
                        margin:2px 2px 0 0">${n}</span>`
                  ).join('')
                : `<span style="font-size:12px;color:#9CA3AF;font-style:italic">Belum ada petugas</span>`;

            // Priority colors
            const [pbg, ptxt] = (PRIORITY_COLOR[m.priority] || '#F3F4F6,#374151').split(',');

            const popup = `
                <div style="font-family:-apple-system,BlinkMacSystemFont,sans-serif;min-width:230px">
                    <div style="background:#C01818;color:white;padding:10px 12px;
                        margin:-10px -10px 10px;border-radius:10px 10px 0 0">
                        <p style="font-size:11px;opacity:.75;margin:0;font-family:monospace">${m.code}</p>
                        <p style="font-size:13px;font-weight:700;margin:2px 0 0;line-height:1.35">${m.title}</p>
                    </div>
                    <div style="padding:0 2px">
                        <div style="display:flex;gap:5px;flex-wrap:wrap;margin-bottom:8px">
                            <span style="background:#FEE2E2;color:#B91C1C;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600">${STATUS_LABEL[m.status]||m.status}</span>
                            <span style="background:${pbg};color:${ptxt};padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600">${PRIORITY_LABEL[m.priority]||m.priority}</span>
                        </div>
                        <p style="font-size:12px;color:#6B7280;margin:0 0 3px">
                            <strong style="color:#374151">📍</strong>
                            ${m.district||'-'} ${m.location ? '• ' + m.location.substring(0,40) + (m.location.length>40?'...':'') : ''}
                        </p>
                        <p style="font-size:12px;color:#6B7280;margin:0 0 8px">
                            <strong style="color:#374151">📋</strong> ${m.category||'-'} &nbsp;•&nbsp; ${m.created_at}
                        </p>
                        <div style="margin-bottom:10px">
                            <p style="font-size:11px;color:#9CA3AF;margin:0 0 4px">Petugas:</p>
                            ${officersHtml}
                        </div>
                        <a href="${m.url}" style="display:block;text-align:center;background:#C01818;
                            color:white;padding:8px 12px;border-radius:10px;font-size:12px;
                            font-weight:700;text-decoration:none">
                            Lihat Detail →
                        </a>
                    </div>
                </div>`;

            const mk = L.marker([m.lat, m.lng], { icon });
            mk.bindPopup(popup, { maxWidth: 290, className: 'silabu-popup' });
            cluster.addLayer(mk);
        });

        map.addLayer(cluster);

        // Fit bounds
        const bounds = L.latLngBounds(markers.map(m => [m.lat, m.lng]));
        map.fitBounds(bounds.pad(0.15));
    }
})();
</script>

@endsection