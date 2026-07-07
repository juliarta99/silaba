@extends('layouts.app')

@section('content')
    <div class="bg-[#F8FAFC] min-h-screen py-20 font-plus-jakarta-sans relative" x-data="{ showEditModal: false, showDeleteModal: false }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Manajemen Kecamatan</h1>
                <p class="text-sm text-gray-500">Kelola data kecamatan di Kabupaten Gianyar</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">5</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Kecamatan</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] flex items-center justify-center text-[#27AE60]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">51</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Desa/Kelurahan</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#F3E8FF] flex items-center justify-center text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">204.37</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Luas (km²)</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">40.87</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Rata-rata Luas (km²)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model.live="search" type="text" class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-red-700 focus:border-red-700 outline-none transition-colors" placeholder="Cari nama kecamatan atau camat...">
                </div>

                <button type="button" @click="showEditModal = true" class="w-full sm:w-auto px-5 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm border border-[#B91C1C]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kecamatan
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Ubud</h3>
                            <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[#27AE60] bg-[#E8F5E9] text-[10px] font-bold">Aktif</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-[11px] text-gray-400 mb-0.5">Camat</p>
                        <p class="text-sm font-semibold text-gray-900">I Ketut Ardana, SH</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-600">Jl. Raya Ubud No. 1, Gianyar</p>
                    </div>

                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0361-975123
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            kec.ubud@gianyar.go.id
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Luas Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">42.38 km²</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Jumlah Desa</p>
                            <p class="text-sm font-bold text-gray-900">8 Desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-red-200 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Gianyar</h3>
                            <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[#27AE60] bg-[#E8F5E9] text-[10px] font-bold">Aktif</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-[11px] text-gray-400 mb-0.5">Camat</p>
                        <p class="text-sm font-semibold text-gray-900">I Wayan Suartana, S.Sos</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-600">Jl. Ngurah Rai No. 12, Gianyar</p>
                    </div>

                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0361-943210
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            kec.gianyar@gianyar.go.id
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Luas Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">26.68 km²</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Jumlah Desa</p>
                            <p class="text-sm font-bold text-gray-900">10 Desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-red-200 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Sukawati</h3>
                            <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[#27AE60] bg-[#E8F5E9] text-[10px] font-bold">Aktif</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-[11px] text-gray-400 mb-0.5">Camat</p>
                        <p class="text-sm font-semibold text-gray-900">I Made Sudiarta, M.Si</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-600">Jl. Raya Sukawati No. 45, Gianyar</p>
                    </div>

                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0361-299876
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            kec.sukawati@gianyar.go.id
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Luas Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">30.15 km²</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Jumlah Desa</p>
                            <p class="text-sm font-bold text-gray-900">12 Desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-red-200 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Blahbatuh</h3>
                            <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[#27AE60] bg-[#E8F5E9] text-[10px] font-bold">Aktif</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-[11px] text-gray-400 mb-0.5">Camat</p>
                        <p class="text-sm font-semibold text-gray-900">Ni Nyoman Sukerti, S.Sos</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-600">Jl. Raya Blahbatuh No. 78, Gianyar</p>
                    </div>

                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0361-942567
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            kec.blahbatuh@gianyar.go.id
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Luas Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">36.22 km²</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Jumlah Desa</p>
                            <p class="text-sm font-bold text-gray-900">11 Desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-red-200 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Tampaksiring</h3>
                            <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[#27AE60] bg-[#E8F5E9] text-[10px] font-bold">Aktif</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-[11px] text-gray-400 mb-0.5">Camat</p>
                        <p class="text-sm font-semibold text-gray-900">I Nyoman Wirawan, SH</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-[11px] text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-sm text-gray-600">Jl. Raya Tampaksiring No. 56, Gianyar</p>
                    </div>

                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0361-901234
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            kec.tampaksiring@gianyar.go.id
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-5">
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Luas Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">68.94 km²</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 mb-0.5">Jumlah Desa</p>
                            <p class="text-sm font-bold text-gray-900">10 Desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-red-200 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 text-xs font-bold transition-all">
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
                            <h3 class="text-lg font-bold text-gray-900 mb-5" id="modal-title">Formulir Kecamatan</h3>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kecamatan</label>
                                <input wire:model="nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Contoh: Ubud">
                            </div>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Camat</label>
                                <input wire:model="camat" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Masukkan nama camat beserta gelar">
                            </div>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Kantor</label>
                                <textarea wire:model="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors resize-none" placeholder="Masukkan alamat lengkap..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telepon</label>
                                    <input wire:model="telepon" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="Contoh: 0361-123456">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                    <input wire:model="email" type="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="email@gianyar.go.id">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-2">
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Luas Wilayah (km²)</label>
                                    <input wire:model="luas" type="number" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="0.00">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Desa</label>
                                    <input wire:model="jumlah_desa" type="number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-[#B91C1C] focus:border-[#B91C1C] outline-none transition-colors" placeholder="0">
                                </div>
                            </div>

                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#B91C1C] hover:bg-red-800 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-lg transition-colors">
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
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Kecamatan?</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus data kecamatan ini? Tindakan ini tidak dapat dibatalkan dan semua data terkait mungkin akan terpengaruh.
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
