@extends('layouts.app')

@section('content')
    <div class="bg-[#F8FAFC] min-h-screen py-20 font-plus-jakarta-sans relative" x-data="{ showAddModal: false, showDeleteModal: false }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Pemetaan Kategori OPD</h1>
                <p class="text-sm text-gray-500">Petakan kategori laporan ke OPD yang bertanggung jawab</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">10</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Pemetaan</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] flex items-center justify-center text-[#27AE60] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">10</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kategori Terpetakan</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">0</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kategori Belum Dipetakan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Cari kategori atau OPD...">
                </div>

                <button type="button" @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm border border-[#B91C1C]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pemetaan
                </button>
            </div>

            <div class="flex flex-col gap-6">

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Dinas Pekerjaan Umum dan Penataan Ruang</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Dinas PUPR</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600 block">4</span>
                            <span class="text-[11px] text-gray-400">Kategori</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Infrastruktur Jalan</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-001</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Penerangan Jalan</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-003</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Fasilitas Umum</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-005</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.924 12.279c0 4.417-3.791 8.243-8.625 8.243-4.832 0-8.625-3.826-8.625-8.243 0-3.328 1.905-6.237 4.757-7.511a12.871 12.871 0 013.868-2.673C14.77 3.303 16.5 5.518 16.5 7.64c0 1.258-.87 2.45-2.002 3.037"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Air Bersih</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-006</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Dinas Lingkungan Hidup</h3>
                                <p class="text-xs text-gray-500 mt-0.5">DLH</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600 block">3</span>
                            <span class="text-[11px] text-gray-400">Kategori</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Kebersihan Lingkungan</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-002</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Drainase Tersumbat</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-008</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Pohon Tumbang</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-009</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Satuan Polisi Pamong Praja</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Satpol PP</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600 block">2</span>
                            <span class="text-[11px] text-gray-400">Kategori</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Parkir Liar</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-004</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Ketertiban Umum</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-007</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Dinas Perhubungan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Dishub</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600 block">1</span>
                            <span class="text-[11px] text-gray-400">Kategori</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-4 flex items-center justify-between group hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Rambu Lalu Lintas</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">ID: KAT-010</p>
                                    </div>
                                </div>
                                <button type="button" @click="showDeleteModal = true" class="bg-red-50 text-gray-400 hover:text-[#B91C1C] transition-colors p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true" @click="showAddModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showAddModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">

                    <form @submit.prevent="showAddModal = false">
                        <div class="px-6 py-5 bg-white">
                            <h3 class="text-lg font-bold text-gray-900 mb-5" id="modal-title">Tambah Pemetaan Baru</h3>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Kategori Laporan</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors">
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    <option value="1">Infrastruktur Jalan</option>
                                    <option value="2">Penerangan Jalan</option>
                                    <option value="3">Kebersihan Lingkungan</option>
                                    <option value="4">Rambu Lalu Lintas</option>
                                </select>
                            </div>

                            <div class="mb-2 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih OPD Penanggung Jawab</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors">
                                    <option value="" disabled selected>Pilih Instansi/OPD...</option>
                                    <option value="1">Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)</option>
                                    <option value="2">Dinas Lingkungan Hidup (DLH)</option>
                                    <option value="3">Satuan Polisi Pamong Praja (Satpol PP)</option>
                                    <option value="4">Dinas Perhubungan (Dishub)</option>
                                </select>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                Simpan Pemetaan
                            </button>
                            <button type="button" @click="showAddModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true" @click="showDeleteModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100">

                    <div class="px-6 pt-6 pb-5 bg-white flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-[#B91C1C] mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Pemetaan?</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus relasi kategori ini dari OPD bersangkutan? Laporan yang masuk ke kategori ini mungkin tidak akan diteruskan secara otomatis.
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                            Ya, Hapus
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
