@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 pt-6 px-4 sm:px-6 lg:px-8 font-['Plus_Jakarta_Sans',_sans-serif]">
    <div class="max-w-7xl mx-auto">
        
        <a href="#" class="inline-flex items-center text-sm font-medium text-[#6B7280] hover:text-[#111] mb-4 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#111]">Daftar Laporan</h1>
                <p class="text-sm text-[#6B7280] mt-1">Monitoring semua laporan yang masuk</p>
            </div>
            <button class="bg-[#C01818] hover:bg-[#a11414] text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Data
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Total Laporan</p>
                    <h3 class="text-2xl font-bold text-[#111]">6</h3>
                </div>
                <div class="text-[#6B7280]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Belum Ditugaskan</p>
                    <h3 class="text-2xl font-bold text-[#111]">2</h3>
                </div>
                <div class="text-[#6B7280]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Diproses</p>
                    <h3 class="text-2xl font-bold text-[#111]">2</h3>
                </div>
                <div class="text-yellow-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Selesai</p>
                    <h3 class="text-2xl font-bold text-[#111]">1</h3>
                </div>
                <div class="text-green-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Terlambat</p>
                    <h3 class="text-2xl font-bold text-[#111]">1</h3>
                </div>
                <div class="text-[#C01818]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-bold text-[#111] flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter & Pencarian
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                <input type="text" placeholder="Cari laporan..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:border-[#C01818] focus:ring-1 focus:ring-[#C01818]">
                <div class="w-full h-[38px] bg-gray-50 border border-gray-200 rounded-lg"></div>
                <div class="w-full h-[38px] bg-gray-50 border border-gray-200 rounded-lg"></div>
                <div class="w-full h-[38px] bg-gray-50 border border-gray-200 rounded-lg"></div>
                <div class="w-full h-[38px] bg-gray-50 border border-gray-200 rounded-lg"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-[#111] mb-6">Hasil: 6 laporan</h3>
            
            <div class="flex flex-col space-y-6">
                
                <div class="flex flex-col md:flex-row gap-5 border-b border-gray-100 pb-6">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1647678803694-a3bd223e4414?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Lampu" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Lampu Jalan Mati di Sunset Road</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-015</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-md">Diproses</span>
                                <span class="px-2 py-1 bg-red-100 text-[#C01818] text-[10px] font-bold rounded-md">Prioritas Tinggi</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md">Infrastruktur</span>
                                <span class="px-2 py-1 text-yellow-600 text-[10px] font-bold">SLA 65%</span>
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold rounded-md flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    2 Laporan Serupa
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Kuta</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Wayan Sukarta +1 lainnya</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 1 Jun 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> 2 Petugas</div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Made Wirawan
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Wayan Sudiarta
                            </span>
                            <button class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] hover:bg-red-50 text-[11px] font-semibold rounded-md transition bg-white">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Petugas
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 border-b border-gray-100 pb-6">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?w=300&h=300&fit=crop" alt="Jalan" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Jalan Berlubang di Jl. Raya Sempidi</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-016</p>
                                </div>
                                <button class="bg-[#C01818] hover:bg-[#a11414] text-white px-3 py-1.5 rounded-md text-[11px] font-bold whitespace-nowrap flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg> Tugaskan Petugas
                                </button>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md">Belum Ditugaskan</span>
                                <span class="px-2 py-1 bg-red-100 text-[#C01818] text-[10px] font-bold rounded-md">Prioritas Tinggi</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md">Infrastruktur</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Mengwi</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Ni Nyoman Suartini</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 2 Jun 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> Belum ada petugas</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 border-b border-gray-100 pb-6">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1526951521990-620dc14c214b?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Pantai" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Sampah Menumpuk di Pantai Jimbaran</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-017</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-[10px] font-bold rounded-md">Ditinjau</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-md">Prioritas Sedang</span>
                                <span class="px-2 py-1 bg-teal-100 text-teal-700 text-[10px] font-bold rounded-md">Kebersihan</span>
                                <span class="px-2 py-1 text-green-600 text-[10px] font-bold">SLA 40%</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Kuta Utara</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Komang Adi</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 1 Jun 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> 1 Petugas</div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Ketut Sudiana
                            </span>
                            <button class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] hover:bg-red-50 text-[11px] font-semibold rounded-md transition bg-white">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Petugas
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 border-b border-gray-100 pb-6">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1662883914604-f44ea7cc5b74?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fHBsYXN0aWMlMjBkaXJ0eSUyMGRyYWluYWdlfGVufDB8fDB8fHww" alt="Drainase" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Drainase Tersumbat di Perumahan Dalung</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-018</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-md">Selesai</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-md">Prioritas Sedang</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md">Drainase</span>
                                <span class="px-2 py-1 text-[#C01818] text-[10px] font-bold">SLA 100%</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Kuta Utara</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Wayan Sujana</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 28 Mei 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> 1 Petugas</div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Ni Luh Suartini
                            </span>
                            <button class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] hover:bg-red-50 text-[11px] font-semibold rounded-md transition bg-white">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Petugas
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 border-b border-gray-100 pb-6">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1739783863808-1be542e9b33b?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Taman" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Taman Kota Butuh Perawatan</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-019</p>
                                </div>
                                <button class="bg-[#C01818] hover:bg-[#a11414] text-white px-3 py-1.5 rounded-md text-[11px] font-bold whitespace-nowrap flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg> Tugaskan Petugas
                                </button>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md">Belum Ditugaskan</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-md">Prioritas Rendah</span>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md">Taman</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Mengwi</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Ni Made Ayu</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 2 Jun 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> Belum ada petugas</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-32 h-32 rounded-lg bg-gray-200 shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1759670691169-59b8adfe5bc9?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Lampu Rusak" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <h4 class="text-base font-bold text-[#111]">Penerangan Jalan Rusak di Jl. Uluwatu</h4>
                                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">ID: TKT-2024-020</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="px-2 py-1 bg-red-100 text-[#C01818] text-[10px] font-bold rounded-md">Terlambat</span>
                                <span class="px-2 py-1 bg-[#C01818] text-white text-[10px] font-bold rounded-md">Prioritas Darurat</span>
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-md">Penerangan</span>
                                <span class="px-2 py-1 text-[#C01818] text-[10px] font-bold">SLA 95%</span>
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold rounded-md flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    4 Laporan Serupa
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-xs font-medium text-[#6B7280]">
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Kuta Selatan</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Ketut Rana +3 lainnya</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 25 Mei 2026</div>
                            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> 3 Petugas</div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> I Made Wirawan
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Ni Luh Suartini
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] bg-red-50 text-[11px] font-semibold rounded-md">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Ni Made Ariani
                            </span>
                            <button class="inline-flex items-center gap-1 px-2.5 py-1 border border-red-200 text-[#C01818] hover:bg-red-50 text-[11px] font-semibold rounded-md transition bg-white">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Petugas
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection