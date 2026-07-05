@extends('layouts.app')
@section('title', 'Penugasan Petugas — SILABU')

@section('content')

@php
$preselectedReportId = request('report_id');
@endphp

{{-- Data dikirim lewat <script type="application/json"> supaya tidak ada risiko
     tanda kutip di dalam data (judul laporan, nama petugas, dsb) merusak atribut HTML.
     JSON_HEX_TAG mencegah "</script>" di dalam data memutus tag script ini. --}}
<script type="application/json" id="assignment-reports-data">{!! json_encode($reportsData, JSON_HEX_TAG) !!}</script>
<script type="application/json" id="assignment-officers-data">{!! json_encode($officersData, JSON_HEX_TAG) !!}</script>

<div
    class="bg-gray-10 min-h-[calc(100vh-68px)] py-16"
    x-data="{
        // ── Data mentah, diisi saat init() dari <script type=application/json> di atas ──
        allReports: [],
        allOfficers: [],

        // ── State pencarian & pagination laporan ──
        reportSearch: '',
        reportPage: 1,
        reportPerPage: 5,

        // ── State pencarian & pagination petugas ──
        officerSearch: '',
        officerPage: 1,
        officerPerPage: 5,

        // ── State form penugasan ──
        selectedReport: null,
        selectedOfficers: [],
        priority: '',
        estDays: 3,
        notes: '',

        init() {
            // Ambil data dari script JSON di atas (aman dari konflik tanda kutip)
            try {
                this.allReports = JSON.parse(document.getElementById('assignment-reports-data').textContent);
            } catch (e) {
                console.error('Gagal parse data laporan:', e);
                this.allReports = [];
            }
            try {
                this.allOfficers = JSON.parse(document.getElementById('assignment-officers-data').textContent);
            } catch (e) {
                console.error('Gagal parse data petugas:', e);
                this.allOfficers = [];
            }

            // Pre-select laporan jika ada query param, lalu lompat ke halaman yang memuat laporan itu
            @if ($preselectedReportId)
            const pre = this.allReports.find(r => r.id === {{ (int) $preselectedReportId }});
            if (pre) {
                this.selectReport(pre);
                this.goToReportPage(pre.id);
            }
            @endif
        },

        // Lompat ke halaman pagination tempat sebuah laporan berada (dipakai untuk preselect via ?report_id=)
        goToReportPage(reportId) {
            const idx = this.filteredReports.findIndex(r => r.id === reportId);
            if (idx !== -1) {
                this.reportPage = Math.floor(idx / this.reportPerPage) + 1;
            }
        },

        selectReport(report) {
            this.selectedReport    = report;
            this.selectedOfficers  = [...(report.assignedIds || [])];
            this.priority          = report.priority || '';
        },

        toggleOfficer(empId) {
            const idx = this.selectedOfficers.indexOf(empId);
            if (idx === -1) this.selectedOfficers.push(empId);
            else this.selectedOfficers.splice(idx, 1);
        },

        isSelected(empId) {
            return this.selectedOfficers.includes(empId);
        },

        removeOfficer(empId) {
            this.selectedOfficers = this.selectedOfficers.filter(id => id !== empId);
        },

        get newOfficers() {
            const assignedIds = this.selectedReport?.assignedIds || [];
            return this.selectedOfficers.filter(id => !assignedIds.includes(id));
        },

        get estimasiLabel() {
            if (this.estDays == 1) return '1 hari kerja';
            if (this.estDays <= 7) return this.estDays + ' hari kerja';
            const w = Math.floor(this.estDays / 7);
            const d = this.estDays % 7;
            return w + ' minggu' + (d ? ' ' + d + ' hari' : '');
        },

        // ── Filter & pagination: LAPORAN ──
        get filteredReports() {
            const kw = this.reportSearch.trim().toLowerCase();
            if (!kw) return this.allReports;
            return this.allReports.filter(r =>
                (r.title || '').toLowerCase().includes(kw) ||
                (r.code || '').toLowerCase().includes(kw) ||
                (r.category || '').toLowerCase().includes(kw) ||
                (r.district || '').toLowerCase().includes(kw)
            );
        },
        get reportTotalPages() {
            return Math.max(1, Math.ceil(this.filteredReports.length / this.reportPerPage));
        },
        get paginatedReports() {
            const start = (this.reportPage - 1) * this.reportPerPage;
            return this.filteredReports.slice(start, start + this.reportPerPage);
        },
        onReportSearch() {
            this.reportPage = 1;
        },
        reportNextPage() {
            if (this.reportPage < this.reportTotalPages) this.reportPage++;
        },
        reportPrevPage() {
            if (this.reportPage > 1) this.reportPage--;
        },

        // ── Filter & pagination: PETUGAS ──
        get filteredOfficers() {
            const kw = this.officerSearch.trim().toLowerCase();
            if (!kw) return this.allOfficers;
            return this.allOfficers.filter(o =>
                (o.name || '').toLowerCase().includes(kw) ||
                (o.categoryTags || []).some(c => (c || '').toLowerCase().includes(kw))
            );
        },
        get officerTotalPages() {
            return Math.max(1, Math.ceil(this.filteredOfficers.length / this.officerPerPage));
        },
        get paginatedOfficers() {
            const start = (this.officerPage - 1) * this.officerPerPage;
            return this.filteredOfficers.slice(start, start + this.officerPerPage);
        },
        onOfficerSearch() {
            this.officerPage = 1;
        },
        officerNextPage() {
            if (this.officerPage < this.officerTotalPages) this.officerPage++;
        },
        officerPrevPage() {
            if (this.officerPage > 1) this.officerPage--;
        },
    }"
>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('employee.supervisor.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Penugasan Petugas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tugaskan laporan kepada satu atau lebih petugas</p>
        </div>

        {{-- Flash --}}
        @if (session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100
                    text-sm text-success mb-5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ══════ KOLOM KIRI ══════ --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- ── 1. Pilih Laporan ── --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Pilih Laporan
                        </h2>
                        <span class="text-xs text-gray-400" x-text="filteredReports.length + ' laporan'"></span>
                    </div>

                    {{-- Search laporan --}}
                    <div class="relative mb-3">
                        <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 16 16">
                            <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M14 14l-3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <input type="text" x-model="reportSearch" @@input="onReportSearch()"
                               placeholder="Cari laporan (judul, ID tiket, kategori, kecamatan)..."
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-700 placeholder-gray-400 focus:bg-white focus:border-primary-500
                                      focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <template x-for="report in paginatedReports" :key="report.id">
                            <div
                                @@click="selectReport(report)"
                                class="flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all"
                                :class="selectedReport?.id === report.id
                                    ? 'border-primary-500 bg-primary-50'
                                    : 'border-gray-100 hover:border-gray-200 bg-white'"
                            >
                                {{-- Foto --}}
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                    <template x-if="report.photo">
                                        <img :src="report.photo" class="w-full h-full object-cover" alt="">
                                    </template>
                                    <template x-if="!report.photo">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p x-show="report.childCount > 0"
                                       class="text-xs text-orange-600 font-medium flex items-center gap-1 mb-0.5">
                                        📋 <span x-text="report.childCount"></span> laporan serupa digabungkan
                                    </p>
                                    <p class="text-sm font-bold text-gray-900 line-clamp-1" x-text="report.title"></p>
                                    <p class="text-xs text-gray-400 font-mono mb-1.5">ID: <span x-text="report.code"></span></p>
                                    <div class="flex flex-wrap gap-1.5 mb-1.5">
                                        <span x-show="report.category" class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700" x-text="report.category"></span>
                                        <span x-show="report.district" class="text-xs text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                                <path d="M6 1C4.07 1 2.5 2.57 2.5 4.5c0 2.625 3.5 6.5 3.5 6.5s3.5-3.875 3.5-6.5C9.5 2.57 7.93 1 6 1z" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                                            </svg>
                                            <span x-text="report.district"></span>
                                        </span>
                                        <span class="text-xs text-gray-400">🗓 <span x-text="report.created"></span></span>
                                    </div>

                                    {{-- Petugas yang sudah ada --}}
                                    <div x-show="report.assignedNames.length > 0" class="flex flex-wrap gap-1.5">
                                        <span class="text-xs text-gray-400">Petugas yang sudah ditugaskan:</span>
                                        <template x-for="name in report.assignedNames" :key="name">
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-success font-medium" x-text="name"></span>
                                        </template>
                                    </div>
                                </div>

                                {{-- Checkmark --}}
                                <div class="shrink-0 mt-0.5">
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                         :class="selectedReport?.id === report.id
                                             ? 'border-primary-500 bg-primary-500'
                                             : 'border-gray-300'">
                                        <svg x-show="selectedReport?.id === report.id"
                                             class="w-3 h-3 text-white" fill="none" viewBox="0 0 12 12">
                                            <path d="M2.5 6l2.5 2.5L9.5 4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredReports.length === 0" class="text-center py-10">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-sm text-gray-400" x-text="reportSearch ? 'Tidak ada laporan yang cocok dengan pencarian' : 'Semua laporan aktif sudah ditugaskan'"></p>
                        </div>
                    </div>

                    {{-- Pagination laporan --}}
                    <div x-show="filteredReports.length > reportPerPage"
                         class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <button type="button" @@click="reportPrevPage()" :disabled="reportPage === 1"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200
                                       text-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50">
                            ‹ Sebelumnya
                        </button>
                        <span class="text-xs text-gray-400">
                            Halaman <span class="font-semibold text-gray-700" x-text="reportPage"></span>
                            dari <span class="font-semibold text-gray-700" x-text="reportTotalPages"></span>
                        </span>
                        <button type="button" @@click="reportNextPage()" :disabled="reportPage === reportTotalPages"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200
                                       text-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50">
                            Selanjutnya ›
                        </button>
                    </div>
                </div>

                {{-- ── 2. Pilih Petugas ── --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Pilih Petugas
                        </h2>
                        <span class="text-xs font-semibold text-primary-500 bg-primary-50 px-2.5 py-1 rounded-full"
                              x-text="selectedOfficers.length + ' dipilih'"></span>
                    </div>

                    <p class="text-xs text-blue-600 bg-blue-50 px-3 py-2 rounded-lg mb-4">
                        💡 Anda dapat memilih beberapa petugas untuk bekerja sama menangani laporan ini
                    </p>

                    {{-- Search petugas --}}
                    <div class="relative mb-3">
                        <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 16 16">
                            <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M14 14l-3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <input type="text" x-model="officerSearch" @@input="onOfficerSearch()"
                               placeholder="Cari petugas (nama atau kategori keahlian)..."
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-700 placeholder-gray-400 focus:bg-white focus:border-primary-500
                                      focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                    </div>

                    <div class="flex flex-col gap-3">
                        <template x-for="officer in paginatedOfficers" :key="officer.id">
                            <div
                                @@click="toggleOfficer(officer.id)"
                                class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all select-none"
                                :class="isSelected(officer.id)
                                    ? 'border-primary-500 bg-primary-50'
                                    : 'border-gray-100 hover:border-gray-200 bg-white'"
                            >
                                {{-- Avatar --}}
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden shrink-0 font-bold text-gray-400 text-sm">
                                    <template x-if="officer.picture">
                                        <img :src="officer.picture" class="w-full h-full object-cover" alt="">
                                    </template>
                                    <template x-if="!officer.picture">
                                        <span x-text="(officer.name || '?').charAt(0).toUpperCase()"></span>
                                    </template>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate" x-text="officer.name || '—'"></p>

                                    {{-- Category tags --}}
                                    <div class="flex flex-wrap gap-1 my-1">
                                        <template x-for="cat in officer.categoryTags" :key="cat">
                                            <span class="text-xs px-1.5 py-0.5 rounded bg-blue-100 text-blue-700" x-text="cat"></span>
                                        </template>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs">
                                        <span class="text-gray-400">Tugas Aktif
                                            <strong class="text-gray-700" x-text="officer.activeTasks + '/' + officer.totalTasks"></strong>
                                        </span>
                                        <span class="text-gray-400">Beban
                                            <strong :class="officer.loadClass" x-text="officer.loadLabel"></strong>
                                        </span>
                                        <span x-show="officer.avgDays" class="text-gray-400">Rata-rata
                                            <strong class="text-gray-700" x-text="officer.avgDays + ' hari'"></strong>
                                        </span>
                                        <span x-show="officer.avgRating" class="text-yellow-500">⭐
                                            <strong x-text="officer.avgRating"></strong>
                                        </span>
                                    </div>
                                </div>

                                {{-- Checkmark --}}
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-all"
                                     :class="isSelected(officer.id)
                                         ? 'border-primary-500 bg-primary-500'
                                         : 'border-gray-200'">
                                    <svg x-show="isSelected(officer.id)"
                                         class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 14 14">
                                        <path d="M2.5 7l3 3L11.5 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredOfficers.length === 0" class="text-center py-8">
                            <p class="text-sm text-gray-400">Tidak ada petugas yang cocok dengan pencarian</p>
                        </div>
                    </div>

                    {{-- Pagination petugas --}}
                    <div x-show="filteredOfficers.length > officerPerPage"
                         class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                        <button type="button" @@click="officerPrevPage()" :disabled="officerPage === 1"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200
                                       text-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50">
                            ‹ Sebelumnya
                        </button>
                        <span class="text-xs text-gray-400">
                            Halaman <span class="font-semibold text-gray-700" x-text="officerPage"></span>
                            dari <span class="font-semibold text-gray-700" x-text="officerTotalPages"></span>
                        </span>
                        <button type="button" @@click="officerNextPage()" :disabled="officerPage === officerTotalPages"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200
                                       text-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50">
                            Selanjutnya ›
                        </button>
                    </div>
                </div>

                {{-- ── 3. Detail Penugasan ── --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4">Detail Penugasan</h2>
                    <form id="assignment-form"
                          action="{{ route('employee.supervisor.assignments.store') }}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="report_id" :value="selectedReport?.id">
                        <template x-for="empId in newOfficers" :key="empId">
                            <input type="hidden" name="employee_ids[]" :value="empId">
                        </template>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Prioritas</label>
                                <select name="priority" x-model="priority"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                               text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                               focus:ring-primary-500 outline-none transition-all">
                                    <option value="">Tidak diubah</option>
                                    <option value="low">Rendah</option>
                                    <option value="medium">Sedang</option>
                                    <option value="high">Tinggi</option>
                                    <option value="critical">Mendesak/Darurat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                                    Estimasi Penyelesaian (Hari)
                                </label>
                                <input type="number" name="est_days" x-model="estDays"
                                       min="1" max="30" value="3"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                              text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                              focus:ring-primary-500 outline-none transition-all">
                                <p class="mt-1.5 text-xs text-gray-400" x-text="estimasiLabel"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                                    Catatan untuk Petugas
                                    <span class="text-xs font-normal text-gray-400 ml-1">(Opsional)</span>
                                </label>
                                <textarea name="notes" x-model="notes" rows="3"
                                          placeholder="Tambahkan catatan khusus untuk petugas..."
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                                 text-gray-900 placeholder-gray-400 resize-none focus:bg-white
                                                 focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                                 outline-none transition-all"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            {{-- ══════ KOLOM KANAN — Ringkasan ══════ --}}
            <div class="lg:sticky lg:top-24 self-start">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Ringkasan Penugasan</h2>

                    {{-- Laporan dipilih --}}
                    <div class="mb-4 pb-4 border-b border-gray-50">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Laporan</p>
                        <div x-show="selectedReport" style="display:none;">
                            <p class="text-sm font-bold text-gray-900 line-clamp-2" x-text="selectedReport?.title"></p>
                            <p class="text-xs font-mono text-gray-400 mt-0.5" x-text="selectedReport?.code"></p>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <path d="M6 1C4.07 1 2.5 2.57 2.5 4.5c0 2.625 3.5 6.5 3.5 6.5s3.5-3.875 3.5-6.5C9.5 2.57 7.93 1 6 1z" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                                </svg>
                                <span x-text="selectedReport?.district"></span>
                            </p>
                        </div>
                        <p x-show="!selectedReport" class="text-sm text-gray-400 italic">Belum dipilih</p>
                    </div>

                    {{-- Petugas dipilih --}}
                    <div class="mb-4 pb-4 border-b border-gray-50">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">
                            Petugas Dipilih
                            <span x-text="'(' + selectedOfficers.length + ')'" class="text-primary-500"></span>
                        </p>
                        <div x-show="selectedOfficers.length === 0" class="text-sm text-gray-400 italic">
                            Belum ada petugas dipilih
                        </div>
                        <div class="space-y-2">
                            <template x-for="officer in allOfficers" :key="officer.id">
                                <div x-show="isSelected(officer.id)"
                                     class="flex items-center gap-2.5 p-2.5 rounded-xl bg-gray-10 border border-gray-100"
                                     style="display:none;">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 text-xs font-bold
                                                flex items-center justify-center shrink-0 overflow-hidden">
                                        <template x-if="officer.picture">
                                            <img :src="officer.picture" class="w-full h-full object-cover" alt="">
                                        </template>
                                        <template x-if="!officer.picture">
                                            <span x-text="(officer.name || '?').charAt(0).toUpperCase()"></span>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-900 truncate" x-text="officer.name || '—'"></p>
                                        <p class="text-xs text-gray-400" x-text="officer.activeTasks + ' tugas aktif'"></p>
                                    </div>
                                    <button type="button" @@click="removeOfficer(officer.id)"
                                            class="text-gray-300 hover:text-error transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                            <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Detail --}}
                    <div class="mb-4 pb-4 border-b border-gray-50 space-y-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Prioritas</p>
                            <p class="text-sm font-semibold text-gray-900"
                               x-text="priority ? ({low:'Rendah',medium:'Sedang',high:'Tinggi',critical:'Mendesak'}[priority]) : 'Tidak diubah'">
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Estimasi Selesai</p>
                            <p class="text-sm font-semibold text-gray-900" x-text="estimasiLabel"></p>
                        </div>
                    </div>

                    {{-- Info WA --}}
                    <div class="flex items-start gap-2 px-3 py-2.5 rounded-xl bg-blue-50 border border-blue-100 mb-4">
                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 16 16">
                            <path d="M8 1.5a6.5 6.5 0 100 13 6.5 6.5 0 000-13zM8 5v3.5l2 1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <p class="text-xs text-blue-700 leading-relaxed"
                           x-text="newOfficers.length + ' petugas akan menerima notifikasi WhatsApp dan email'">
                        </p>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="button"
                        @@click="
                            if (!selectedReport) { alert('Pilih laporan terlebih dahulu'); return; }
                            if (newOfficers.length === 0) { alert('Pilih minimal 1 petugas baru untuk ditugaskan'); return; }
                            document.getElementById('assignment-form').submit();
                        "
                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                               bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold
                               transition-colors active:scale-[.98]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <circle cx="9" cy="6" r="3" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M3 14.5c0-3.038 2.462-5.5 6-5.5M13 11v3M11.5 12.5h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <span x-text="'Tugaskan ' + newOfficers.length + ' Petugas'">Tugaskan Petugas</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection