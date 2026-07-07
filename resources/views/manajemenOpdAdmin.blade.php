@extends('layouts.app')

@section('content')
    <div class="bg-[#F8FAFC] min-h-screen py-20 font-plus-jakarta-sans relative" x-data="{ showEditModal: false, showDeleteModal: false, showKadisModal: false }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Manajemen OPD</h1>
                <p class="text-sm text-gray-500">Kelola Organisasi Perangkat Daerah dan Kepala Dinas</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">5</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total OPD</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] flex items-center justify-center text-[#27AE60]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">80</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Petugas</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">5</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">OPD Aktif</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">1</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Belum Ada<br>Kepala Dinas</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model.live="search" type="text" class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Cari nama OPD atau kepala dinas...">
                </div>

                <button type="button" @click="showEditModal = true" class="w-full sm:w-auto px-5 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm border border-[#B91C1C]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah OPD
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-4">
                            <h3 class="font-bold text-gray-900 text-[15px] leading-tight">Dinas Pekerjaan Umum dan Penataan Ruang</h3>
                            <p class="text-xs text-gray-500 mt-1">Dinas PUPR</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="inline-flex px-2 py-0.5 rounded bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-gray-900">Ir. I Made Sudarma, MT</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">NIP: 197508101998031003</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">Jl. Ngurah Rai No. 45, Gianyar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Telepon</p>
                            <p class="text-sm text-gray-700">0361-943123</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700">pupr@gianyar.go.id</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">24 Petugas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">3 Kategori</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button type="button" @click="showKadisModal = true" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-fuchsia-600 bg-fuchsia-50 hover:bg-fuchsia-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Ubah Kadis
                        </button>
                        <button type="button" @click="showEditModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-blue-600 bg-[#EFF6FF] hover:bg-blue-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-red-600 bg-[#FEF2F2] hover:bg-red-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-4">
                            <h3 class="font-bold text-gray-900 text-[15px] leading-tight">Dinas Lingkungan Hidup</h3>
                            <p class="text-xs text-gray-500 mt-1">DLH</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="inline-flex px-2 py-0.5 rounded bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-gray-900">Dr. I Wayan Suteja, M.Si</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">NIP: 196805121992031001</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">Jl. Ciung Wanara No. 12, Gianyar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Telepon</p>
                            <p class="text-sm text-gray-700">0361-943456</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700">dlh@gianyar.go.id</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">18 Petugas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">4 Kategori</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button type="button" @click="showKadisModal = true" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-fuchsia-600 bg-fuchsia-50 hover:bg-fuchsia-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Ubah Kadis
                        </button>
                        <button type="button" @click="showEditModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-blue-600 bg-[#EFF6FF] hover:bg-blue-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-red-600 bg-[#FEF2F2] hover:bg-red-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-4">
                            <h3 class="font-bold text-gray-900 text-[15px] leading-tight">Dinas Perhubungan</h3>
                            <p class="text-xs text-gray-500 mt-1">Dishub</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="inline-flex px-2 py-0.5 rounded bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-gray-900">I Ketut Suardana, SH, M.Si</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">NIP: 197112151994031002</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">Jl. Raya Ubud No. 89, Gianyar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Telepon</p>
                            <p class="text-sm text-gray-700">0361-943789</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700">dishub@gianyar.go.id</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">16 Petugas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">2 Kategori</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button type="button" @click="showKadisModal = true" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-fuchsia-600 bg-fuchsia-50 hover:bg-fuchsia-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Ubah Kadis
                        </button>
                        <button type="button" @click="showEditModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-blue-600 bg-[#EFF6FF] hover:bg-blue-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-red-600 bg-[#FEF2F2] hover:bg-red-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-4">
                            <h3 class="font-bold text-gray-900 text-[15px] leading-tight">Dinas Sosial</h3>
                            <p class="text-xs text-gray-500 mt-1">Dinsos</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="inline-flex px-2 py-0.5 rounded bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-gray-900">Drs. I Nyoman Wiratha</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">NIP: 196503101990031001</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">Jl. Astina Utara No. 23, Gianyar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Telepon</p>
                            <p class="text-sm text-gray-700">0361-943234</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700">dinsos@gianyar.go.id</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">10 Petugas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">2 Kategori</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button type="button" @click="showKadisModal = true" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-fuchsia-600 bg-fuchsia-50 hover:bg-fuchsia-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Ubah Kadis
                        </button>
                        <button type="button" @click="showEditModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-blue-600 bg-[#EFF6FF] hover:bg-blue-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-red-600 bg-[#FEF2F2] hover:bg-red-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-4">
                            <h3 class="font-bold text-gray-900 text-[15px] leading-tight">Satuan Polisi Pamong Praja</h3>
                            <p class="text-xs text-gray-500 mt-1">Satpol PP</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="inline-flex px-2 py-0.5 rounded bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Kepala Dinas</p>
                        <p class="text-sm font-bold text-red-500">Belum ditugaskan</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-700">Jl. Kepundung No. 34, Gianyar</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Telepon</p>
                            <p class="text-sm text-gray-700">0361-943567</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700">satpolpp@gianyar.go.id</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">12 Petugas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <div>
                                <p class="text-[10px] text-gray-400">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">3 Kategori</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button type="button" @click="showKadisModal = true" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-orange-500 bg-orange-50 hover:bg-orange-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg> Tugaskan Kadis
                        </button>
                        <button type="button" @click="showEditModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-blue-600 bg-[#EFF6FF] hover:bg-blue-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="px-4 flex items-center justify-center gap-1.5 py-2 rounded-lg text-red-600 bg-[#FEF2F2] hover:bg-red-100 text-xs font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true" @click="showEditModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">

                    <form @submit.prevent="showEditModal = false">
                        <div class="px-6 py-5 bg-white">
                            <h3 class="text-lg font-bold text-gray-900 mb-5" id="modal-title">Formulir Data OPD</h3>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama OPD</label>
                                <input wire:model="nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Contoh: Dinas Lingkungan Hidup">
                            </div>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Singkatan</label>
                                <input wire:model="singkatan" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Contoh: DLH">
                            </div>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Kantor</label>
                                <textarea wire:model="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors resize-none" placeholder="Masukkan alamat lengkap..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-2">
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telepon</label>
                                    <input wire:model="telepon" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="0361-XXXXXX">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                    <input wire:model="email" type="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="opd@gianyar.go.id">
                                </div>
                            </div>

                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                Simpan Data
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-lg transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showKadisModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showKadisModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true" @click="showKadisModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showKadisModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">

                    <form @submit.prevent="showKadisModal = false">
                        <div class="px-6 py-5 bg-white">
                            <h3 class="text-lg font-bold text-gray-900 mb-5" id="modal-title">Tugaskan Kepala Dinas</h3>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap & Gelar</label>
                                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-fuchsia-500 focus:border-fuchsia-500 outline-none transition-colors" placeholder="Masukkan nama kepala dinas">
                            </div>

                            <div class="mb-2 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIP</label>
                                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-fuchsia-500 focus:border-fuchsia-500 outline-none transition-colors" placeholder="Masukkan Nomor Induk Pegawai">
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-fuchsia-600 hover:bg-fuchsia-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                Simpan Penugasan
                            </button>
                            <button type="button" @click="showKadisModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-lg transition-colors">
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
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Data OPD?</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus data OPD ini? Tindakan ini tidak dapat dibatalkan dan semua data petugas serta laporan terkait mungkin akan terpengaruh.
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                            Ya, Hapus
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-lg transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
