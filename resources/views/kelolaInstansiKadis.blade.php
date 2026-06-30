@extends('layouts.app')

@section('content')
    <div class="bg-white min-h-screen py-10 font-plus-jakarta-sans">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Kelola Instansi</h1>
                <p class="text-sm text-gray-500">Manajemen data instansi dan petugas</p>
            </div>

            <hr class="border-gray-200 mb-8">

            <div class="bg-white border border-gray-200 rounded-xl mb-10">
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Informasi Instansi
                        </h2>
                        <button class="text-sm font-bold text-primary-500 hover:text-primary-700 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Info
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nama Instansi</p>
                            <p class="text-sm font-bold text-gray-900">Dinas Pekerjaan Umum dan Penataan Ruang</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kepala Dinas</p>
                            <p class="text-sm font-bold text-gray-900">Dr. I Gusti Bagus Wirawan, S.T., M.T.</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">NIP Kepala Dinas</p>
                            <p class="text-sm font-bold text-gray-900">196505101990031001</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Supervisor</p>
                            <p class="text-sm font-bold text-gray-900">I Gusti Ngurah Wirawan, S.T., M.T.</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Telepon</p>
                            <p class="text-sm font-bold text-gray-900">(0361) 123456</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="text-sm font-bold text-gray-900">dpupr@badungkab.go.id</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Alamat</p>
                            <p class="text-sm font-bold text-gray-900">Jl. Raya Sempidi, Mengwi, Badung</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl mb-10 overflow-hidden">
                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Daftar Supervisor (2)
                    </h2>
                    <button type="button" class="px-5 py-2.5 bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Supervisor
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 border-y border-gray-200">
                            <tr class="text-gray-900 text-xs font-bold tracking-wide">
                                <th class="py-4 px-6">Nama</th>
                                <th class="py-4 px-6">NIP</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6">Bergabung</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Gusti Ngurah Wirawan, S.T., M.T.</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Supervisor</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">197505101998031001</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>ngurah.wirawan@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567880</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6 text-gray-700">10 Mar 1998</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Ketut Sudarsana, S.T.</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Supervisor</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">198012151999031002</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>ketut.sudarsana@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567881</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6 text-gray-700">15 Jan 1999</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Daftar Petugas (6)
                    </h2>
                    <button type="button" class="px-5 py-2.5 bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Petugas
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 border-y border-gray-200">
                            <tr class="text-gray-900 text-xs font-bold tracking-wide">
                                <th class="py-4 px-6">Nama</th>
                                <th class="py-4 px-6">NIP</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6">Spesialisasi</th>
                                <th class="py-4 px-6 text-center">Tugas</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Made Wirawan</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">198505152010011001</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>made.wirawan@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567890</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Infrastruktur</span>
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Listrik</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">2/24</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">Ni Luh Suartini</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">198803102011012002</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>luh.suartini@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567891</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Infrastruktur</span>
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Drainase</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">3/21</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Ketut Sudiana</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">199001152012011003</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>ketut.sudiana@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567892</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Kebersihan</span>
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Drainase</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">4/19</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Wayan Sudiarta</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">198707202013011004</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>wayan.sudiarta@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567893</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Infrastruktur</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">5/18</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">Putri Ariani</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">199105252014012005</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>putri.ariani@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567894</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Taman</span>
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Penerangan</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">1/16</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-[#E8F5E9] text-[#27AE60] text-[11px] font-bold tracking-wide">Aktif</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">I Komang Suartana</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Petugas Lapangan</p>
                                </td>
                                <td class="py-4 px-6 text-gray-700">199208102015011006</td>
                                <td class="py-4 px-6 text-gray-700">
                                    <p>komang.suartana@badungkab.go.id</p>
                                    <p class="text-xs text-gray-500 mt-0.5">081234567895</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-primary-50 text-primary-500 text-[10px] font-bold">Infrastruktur</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-900">0/15</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-primary-50 text-primary-500 text-[11px] font-bold tracking-wide">Cuti</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="text-primary-500 hover:text-primary-700 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
