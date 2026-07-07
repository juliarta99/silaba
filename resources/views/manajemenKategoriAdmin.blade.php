@extends('layouts.app')

@section('content')
    <div class="bg-[#F8FAFC] min-h-screen py-20 font-plus-jakarta-sans relative" x-data="{ showEditModal: false, showDeleteModal: false }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Manajemen Kategori</h1>
                <p class="text-sm text-gray-500">Kelola kategori laporan untuk sistem pelaporan</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">7</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Kategori</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] flex items-center justify-center text-[#27AE60]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">7</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kategori Aktif</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#F3E8FF] flex items-center justify-center text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">3,851</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Total Laporan</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-secondary-50 flex items-center justify-center text-secondary-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">550</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Rata-rata per Kategori</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model.live="search" type="text" class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors" placeholder="Cari nama atau deskripsi kategori...">
                </div>

                <button type="button" @click="showEditModal = true" class="w-full sm:w-auto px-5 py-2.5 bg-primary-700 hover:bg-primary-900 text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm border border-primary-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kategori
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-[#B91C1C] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Infrastruktur Jalan</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Jalan rusak, berlubang, atau perlu perbaikan</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-primary-500">1,234</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-primary-500"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] flex items-center justify-center text-[#27AE60] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Kebersihan Lingkungan</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Sampah menumpuk, drainase tersumbat</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-[#27AE60]">867</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-[#27AE60]"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#FFF8D1] flex items-center justify-center text-[#F59E0B] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Penerangan Jalan</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Lampu jalan mati atau rusak</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-[#F59E0B]">543</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#F3E8FF] flex items-center justify-center text-[#9333EA] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h8M8 11h8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Parkir Liar</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Kendaraan parkir sembarangan</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-[#9333EA]">421</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-[#9333EA]"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#FCE7F3] flex items-center justify-center text-[#EC4899] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Fasilitas Umum</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Taman, lapangan, halte rusak atau tidak terawat</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-[#EC4899]">312</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-[#EC4899]"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#E0F2FE] flex items-center justify-center text-[#06B6D4] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.924 12.279c0 4.417-3.791 8.243-8.625 8.243-4.832 0-8.625-3.826-8.625-8.243 0-3.328 1.905-6.237 4.757-7.511a12.871 12.871 0 013.868-2.673C14.77 3.303 16.5 5.518 16.5 7.64c0 1.258-.87 2.45-2.002 3.037"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Air Bersih</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Masalah pasokan air bersih</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-[#06B6D4]">276</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-[#06B6D4]"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-[#B91C1C] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-[15px]">Ketertiban Umum</h3>
                            <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[10px] font-bold tracking-wide">Aktif</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">Gangguan ketertiban dan keamanan</p>

                    <div class="mb-5">
                        <p class="text-[11px] text-gray-400 mb-1">Total Laporan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-primary-500">198</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-primary-500"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button type="button" @click="showEditModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-blue-200 rounded-lg text-blue-500 bg-white hover:bg-blue-50 hover:border-blue-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                        </button>
                        <button type="button" @click="showDeleteModal = true" class="flex items-center justify-center gap-1.5 py-2 border border-primary-200 rounded-lg text-primary-500 bg-white hover:bg-primary-50 hover:border-primary-300 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                        </button>
                    </div>

                    <div class="bg-[#FFFDF0] border border-secondary-100 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-secondary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-secondary-700 leading-tight">Kategori ini tidak dapat dihapus karena masih memiliki laporan aktif</p>
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
                            <h3 class="text-lg font-bold text-gray-900 mb-5" id="modal-title">Formulir Kategori</h3>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kategori</label>
                                <input wire:model="nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors" placeholder="Contoh: Infrastruktur Jalan">
                            </div>

                            <div class="mb-4 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                                <textarea wire:model="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors resize-none" placeholder="Masukkan deskripsi singkat mengenai kategori ini..."></textarea>
                            </div>

                            <div class="mb-2 text-left">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Kategori</label>
                                <select wire:model="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak_aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary-700 hover:bg-primary-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
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
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Kategori?</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan dan semua data terkait mungkin akan terpengaruh.
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-gray-100">
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-6 py-2.5 bg-primary-700 hover:bg-primary-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
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
