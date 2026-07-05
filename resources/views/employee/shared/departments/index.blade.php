@extends('layouts.app')
@section('title', 'Kelola Instansi — SILABU')

@section('content')

{{-- Data dikirim lewat <script type="application/json"> supaya aman dari konflik tanda kutip
     (nama pegawai, dsb) dan XSS. JSON_HEX_TAG mencegah "</script>" merusak tag ini. --}}
<script type="application/json" id="instansi-supervisors-data">{!! json_encode($supervisorsData, JSON_HEX_TAG) !!}</script>
<script type="application/json" id="instansi-officers-data">{!! json_encode($officersData, JSON_HEX_TAG) !!}</script>

<div
    class="bg-gray-10 min-h-[calc(100vh-68px)] py-16"
    x-data='{
        isKadis: @json($isKadis),

        // ── Data pegawai, diisi saat init() ──
        supervisors: [],
        officers: [],

        // ── Pencarian ──
        supervisorSearch: "",
        officerSearch: "",

        // ── Modal: Edit Informasi Instansi ──
        infoModalOpen: false,
        infoForm: {
            name: @json($department->name),
            phone: @json($department->phone),
            email: @json($department->email),
            address: @json($department->address),
            kadis_name: @json($kepalaDinas?->user?->name),
            kadis_nip: @json($kepalaDinas?->nip),
        },

        // ── Modal: Tambah / Edit Pegawai (dipakai untuk supervisor & petugas) ──
        employeeModalOpen: false,
        employeeModalMode: "add", // "add" | "edit"
        employeeForm: {
            id: null,
            position: "field_officer", // "supervisor" | "field_officer"
            name: "",
            nip: "",
            phone: "",
            email: "",
            status: "active",
        },

        // ── Modal: Konfirmasi Hapus ──
        deleteModalOpen: false,
        deleteTarget: null,

        init() {
            try {
                this.supervisors = JSON.parse(document.getElementById("instansi-supervisors-data").textContent);
            } catch (e) {
                console.error("Gagal parse data supervisor:", e);
                this.supervisors = [];
            }
            try {
                this.officers = JSON.parse(document.getElementById("instansi-officers-data").textContent);
            } catch (e) {
                console.error("Gagal parse data petugas:", e);
                this.officers = [];
            }
        },

        get filteredSupervisors() {
            const kw = this.supervisorSearch.trim().toLowerCase();
            if (!kw) return this.supervisors;
            return this.supervisors.filter(s =>
                (s.name || "").toLowerCase().includes(kw) ||
                (s.nip || "").toLowerCase().includes(kw)
            );
        },

        get filteredOfficers() {
            const kw = this.officerSearch.trim().toLowerCase();
            if (!kw) return this.officers;
            return this.officers.filter(o =>
                (o.name || "").toLowerCase().includes(kw) ||
                (o.nip || "").toLowerCase().includes(kw) ||
                (o.specializations || []).some(sp => (sp || "").toLowerCase().includes(kw))
            );
        },

        openAddSupervisor() {
            this.employeeModalMode = "add";
            this.employeeForm = { id: null, position: "supervisor", name: "", nip: "", phone: "", email: "", status: "active" };
            this.employeeModalOpen = true;
        },

        openAddOfficer() {
            this.employeeModalMode = "add";
            this.employeeForm = { id: null, position: "field_officer", name: "", nip: "", phone: "", email: "", status: "active" };
            this.employeeModalOpen = true;
        },

        openEdit(person, position) {
            this.employeeModalMode = "edit";
            this.employeeForm = {
                id: person.id,
                position: position,
                name: person.name,
                nip: person.nip,
                phone: person.phone,
                email: person.email,
                status: person.status,
            };
            this.employeeModalOpen = true;
        },

        confirmDelete(person, position) {
            this.deleteTarget = { id: person.id, name: person.name, position };
            this.deleteModalOpen = true;
        },

        get employeeModalTitle() {
            if (this.employeeModalMode === "edit") {
                return this.employeeForm.position === "supervisor" ? "Edit Supervisor" : "Edit Petugas";
            }
            return this.employeeForm.position === "supervisor" ? "Tambah Supervisor" : "Tambah Petugas";
        },
    }'
>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6">

        @php
            $dashboardRoute = auth()->user()->employee->position === 'head_of_department' 
                ? 'employee.head.dashboard' 
                : 'employee.supervisor.dashboard';
        @endphp

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route($dashboardRoute) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Instansi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manajemen data instansi dan petugas</p>
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

        @if ($errors->any())
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-error mb-5">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                <path d="M12 9v3.5m0 3.01l.01-.011M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78a1.5 1.5 0 001.29-2.25L13.71 3.86a1.5 1.5 0 00-2.42 0z"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <ul class="space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="space-y-5">

            {{-- ── Informasi Instansi ── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7M9 9h.01M9 13h.01M9 17h.01"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Informasi Instansi
                    </h2>
                    @if ($isKadis)
                    <button type="button" @click="infoModalOpen = true"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M11.3 2.3a1.5 1.5 0 012.1 2.1L5.5 12.3l-2.8.7.7-2.8 7.9-7.9z"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Edit Info
                    </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nama Instansi</p>
                        <p class="text-sm font-bold text-gray-900">{{ $department->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-gray-900">{{ $kepalaDinas?->user?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">NIP Kepala Dinas</p>
                        <p class="text-sm text-gray-700">{{ $kepalaDinas?->nip ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Jumlah Supervisor</p>
                        <p class="text-sm font-bold text-gray-900">{{ count($supervisorsData) }} orang</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Telepon</p>
                        <p class="text-sm text-gray-700">{{ $department->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Email</p>
                        <p class="text-sm text-gray-700">{{ $department->email }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">{{ $department->address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- ── Daftar Supervisor (khusus Kepala Dinas) ── --}}
            @if ($isKadis)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Daftar Supervisor
                        <span x-text="'(' + supervisors.length + ')'"></span>
                    </h2>
                    <button type="button" @click="openAddSupervisor()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M8 3.5v9M3.5 8h9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                        Tambah Supervisor
                    </button>
                </div>

                <div class="relative mb-3">
                    <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 16 16">
                        <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M14 14l-3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="text" x-model="supervisorSearch"
                           placeholder="Cari supervisor (nama atau NIP)..."
                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                  text-gray-700 placeholder-gray-400 focus:bg-white focus:border-primary-500
                                  focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                                <th class="pb-2.5 font-semibold">Nama</th>
                                <th class="pb-2.5 font-semibold">NIP</th>
                                <th class="pb-2.5 font-semibold">Kontak</th>
                                <th class="pb-2.5 font-semibold">Status</th>
                                <th class="pb-2.5 font-semibold">Bergabung</th>
                                <th class="pb-2.5 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="supervisor in filteredSupervisors" :key="supervisor.id">
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3">
                                        <p class="font-bold text-gray-900" x-text="supervisor.name"></p>
                                        <p class="text-xs text-gray-400">Supervisor</p>
                                    </td>
                                    <td class="py-3 text-gray-600" x-text="supervisor.nip"></td>
                                    <td class="py-3">
                                        <p class="text-gray-600" x-text="supervisor.email"></p>
                                        <p class="text-xs text-gray-400" x-text="supervisor.phone"></p>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                                              :class="supervisor.status === 'active' ? 'bg-green-100 text-success' : (supervisor.status === 'on_leave' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-500')"
                                              x-text="supervisor.statusLabel"></span>
                                    </td>
                                    <td class="py-3 text-gray-500" x-text="supervisor.joined"></td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="openEdit(supervisor, 'supervisor')"
                                                    class="text-primary-500 hover:text-primary-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                                    <path d="M11.3 2.3a1.5 1.5 0 012.1 2.1L5.5 12.3l-2.8.7.7-2.8 7.9-7.9z"
                                                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <button type="button" @click="confirmDelete(supervisor, 'supervisor')"
                                                    class="text-error hover:text-red-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                                    <path d="M2.5 4.5h11M6 4.5V3a1 1 0 011-1h2a1 1 0 011 1v1.5M12.5 4.5l-.6 8.4a1.5 1.5 0 01-1.5 1.4H5.6a1.5 1.5 0 01-1.5-1.4l-.6-8.4"
                                                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div x-show="filteredSupervisors.length === 0" class="text-center py-10">
                        <p class="text-sm text-gray-400">Belum ada supervisor yang cocok / terdaftar</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Daftar Petugas (Kepala Dinas & Supervisor) ── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Daftar Petugas
                        <span x-text="'(' + officers.length + ')'"></span>
                    </h2>
                    <button type="button" @click="openAddOfficer()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M8 3.5v9M3.5 8h9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                        Tambah Petugas
                    </button>
                </div>

                <div class="relative mb-3">
                    <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 16 16">
                        <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M14 14l-3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="text" x-model="officerSearch"
                           placeholder="Cari petugas (nama, NIP, atau spesialisasi)..."
                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                  text-gray-700 placeholder-gray-400 focus:bg-white focus:border-primary-500
                                  focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                                <th class="pb-2.5 font-semibold">Nama</th>
                                <th class="pb-2.5 font-semibold">NIP</th>
                                <th class="pb-2.5 font-semibold">Kontak</th>
                                <th class="pb-2.5 font-semibold">Spesialisasi</th>
                                <th class="pb-2.5 font-semibold">Tugas</th>
                                <th class="pb-2.5 font-semibold">Status</th>
                                <th class="pb-2.5 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="officer in filteredOfficers" :key="officer.id">
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3">
                                        <p class="font-bold text-gray-900" x-text="officer.name"></p>
                                        <p class="text-xs text-gray-400">Petugas Lapangan</p>
                                    </td>
                                    <td class="py-3 text-gray-600" x-text="officer.nip"></td>
                                    <td class="py-3">
                                        <p class="text-gray-600" x-text="officer.email"></p>
                                        <p class="text-xs text-gray-400" x-text="officer.phone"></p>
                                    </td>
                                    <td class="py-3">
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="tag in officer.specializations" :key="tag">
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-error" x-text="tag"></span>
                                            </template>
                                            <span x-show="officer.specializations.length === 0" class="text-xs text-gray-300">—</span>
                                        </div>
                                    </td>
                                    <td class="py-3 font-semibold text-gray-700"
                                        x-text="officer.activeTasks + '/' + officer.totalTasks"></td>
                                    <td class="py-3">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                                              :class="officer.status === 'active' ? 'bg-green-100 text-success' : (officer.status === 'on_leave' ? 'bg-red-50 text-error' : 'bg-gray-100 text-gray-500')"
                                              x-text="officer.statusLabel"></span>
                                    </td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="openEdit(officer, 'field_officer')"
                                                    class="text-primary-500 hover:text-primary-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                                    <path d="M11.3 2.3a1.5 1.5 0 012.1 2.1L5.5 12.3l-2.8.7.7-2.8 7.9-7.9z"
                                                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <button type="button" @click="confirmDelete(officer, 'field_officer')"
                                                    class="text-error hover:text-red-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                                    <path d="M2.5 4.5h11M6 4.5V3a1 1 0 011-1h2a1 1 0 011 1v1.5M12.5 4.5l-.6 8.4a1.5 1.5 0 01-1.5 1.4H5.6a1.5 1.5 0 01-1.5-1.4l-.6-8.4"
                                                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div x-show="filteredOfficers.length === 0" class="text-center py-10">
                        <p class="text-sm text-gray-400">Belum ada petugas yang cocok / terdaftar</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════ MODAL: Edit Informasi Instansi (khusus Kepala Dinas) ══════════════ --}}
    @if ($isKadis)
    <div x-show="infoModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @keydown.escape.window="infoModalOpen = false">
        <div @click.outside="infoModalOpen = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <form action="{{ route('employee.shared.departments.update', $department->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-900">Edit Informasi Instansi</h3>
                    <button type="button" @click="infoModalOpen = false" class="text-gray-300 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 16 16">
                            <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nama Instansi</label>
                        <input type="text" name="name" x-model="infoForm.name" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                      focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nama Kepala Dinas</label>
                            <input type="text" name="kadis_name" x-model="infoForm.kadis_name" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">NIP Kepala Dinas</label>
                            <input type="text" name="kadis_nip" x-model="infoForm.kadis_nip" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Telepon</label>
                            <input type="text" name="phone" x-model="infoForm.phone" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Email</label>
                            <input type="email" name="email" x-model="infoForm.email" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">Alamat</label>
                        <textarea name="address" x-model="infoForm.address" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                         resize-none focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="infoModalOpen = false"
                            class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ══════════════ MODAL: Tambah / Edit Pegawai (Supervisor / Petugas) ══════════════ --}}
    <div x-show="employeeModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @keydown.escape.window="employeeModalOpen = false">
        <div @click.outside="employeeModalOpen = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <form :action="employeeModalMode === 'edit'
                            ? '{{ route('employee.shared.employees.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', employeeForm.id)
                            : '{{ route('employee.shared.employees.store') }}'"
                  method="POST" class="p-6">
                @csrf
                <template x-if="employeeModalMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Input tersembunyi untuk menyimpan posisi (supervisor/field_officer) --}}
                <input type="hidden" name="position" :value="employeeForm.position">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-900" x-text="employeeModalTitle"></h3>
                    <button type="button" @click="employeeModalOpen = false" class="text-gray-300 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 16 16">
                            <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" x-model="employeeForm.name" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                      focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">NIP</label>
                        <input type="text" name="nip" x-model="employeeForm.nip" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                      focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Telepon</label>
                            <input type="text" name="phone" x-model="employeeForm.phone" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Email</label>
                            <input type="email" name="email" x-model="employeeForm.email" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                          focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                    </div>
                    <div x-show="employeeModalMode === 'edit'">
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">Status</label>
                        <select name="status" x-model="employeeForm.status"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm text-gray-900
                                       focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                            <option value="active">Aktif</option>
                            <option value="on_leave">Cuti / Nonaktif Sementara</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="employeeModalOpen = false"
                            class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════ MODAL: Konfirmasi Hapus ══════════════ --}}
    <div x-show="deleteModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @keydown.escape.window="deleteModalOpen = false">
        <div @click.outside="deleteModalOpen = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 text-error flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Data?</h3>
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-700" x-text="deleteTarget?.name"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex border-t border-gray-100">
                <button type="button" @click="deleteModalOpen = false"
                        class="flex-1 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                
                {{-- Form untuk Hapus --}}
                <form :action="'{{ route('employee.shared.employees.destroy', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', deleteTarget?.id)"
                      method="POST" class="flex-1 border-l border-gray-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-3 text-sm font-bold text-error hover:bg-red-50 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection