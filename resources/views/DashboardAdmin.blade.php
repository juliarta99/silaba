@extends('layouts.app') 
@section('content')
<div class="flex min-h-screen bg-[#f5f5f5] font-['Plus_Jakarta_Sans',_sans-serif] py-16">
    


    <main class="flex-1 p-6 md:p-8 lg:p-10 overflow-y-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#111111]">Dashboard Admin Sistem</h1>
            <p class="text-[#828282] mt-2">Kelola seluruh konfigurasi sistem, pengguna, dan data master</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-lg bg-[#F0F5FF] text-[#2F80ED] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-green-500">+12</span>
                </div>
                <h3 class="text-3xl font-bold text-[#111111]">1,247</h3>
                <p class="text-[#828282] text-sm mt-1">Total Pengguna</p>
            </div>

            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-lg bg-green-50 text-green-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[#111111]">15</h3>
                <p class="text-[#828282] text-sm mt-1">Kecamatan</p>
            </div>

            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    </div>
                    <span class="text-sm font-bold text-green-500">+2</span>
                </div>
                <h3 class="text-3xl font-bold text-[#111111]">24</h3>
                <p class="text-[#828282] text-sm mt-1">Kategori Laporan</p>
            </div>

            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[#111111]">24</h3>
                <p class="text-[#828282] text-sm mt-1">OPD Aktif</p>
            </div>
        </div>

        <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0] mb-8">
            <h3 class="text-lg font-bold text-[#111111] mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-[#F0F5FF] text-[#2F80ED] rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span class="text-xs font-bold">Tambah<br>Pengguna</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-green-50 text-green-600 rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="text-xs font-bold">Kelola<br>Kecamatan</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-purple-50 text-purple-600 rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    <span class="text-xs font-bold">Kelola Kategori</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-orange-50 text-orange-600 rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span class="text-xs font-bold">Kelola OPD</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-teal-50 text-teal-600 rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37-2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="text-xs font-bold">Pemetaan<br>Kategori</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-indigo-50 text-indigo-600 rounded-xl hover:opacity-80 transition text-center">
                    <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span class="text-xs font-bold">Lihat Statistik</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <h3 class="text-lg font-bold text-[#111111] mb-6">Distribusi Pengguna per Role</h3>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Warga</span>
                            <span class="text-[#828282]">1089 (87.3%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-[#2F80ED] h-2 rounded-full" style="width: 87.3%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Petugas</span>
                            <span class="text-[#828282]">98 (7.9%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-green-400 h-2 rounded-full" style="width: 7.9%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Supervisor</span>
                            <span class="text-[#828282]">32 (2.6%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-purple-400 h-2 rounded-full" style="width: 2.6%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Kepala Dinas</span>
                            <span class="text-[#828282]">24 (1.9%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-orange-400 h-2 rounded-full" style="width: 1.9%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Camat</span>
                            <span class="text-[#828282]">15 (1.2%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-teal-400 h-2 rounded-full" style="width: 1.2%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-[#333333]">Bupati/Sekda</span>
                            <span class="text-[#828282]">2 (0.2%)</span>
                        </div>
                        <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                            <div class="bg-[#EB5757] h-2 rounded-full" style="width: 0.2%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
                <h3 class="text-lg font-bold text-[#111111] mb-6">Aktivitas Terbaru</h3>
                <div class="relative pl-4 border-l-2 border-[#E0E0E0] space-y-6">
                    
                    <div class="relative">
                        <div class="absolute -left-[21px] mt-1.5 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-[#111111]">Kategori Baru Ditambahkan</p>
                        <p class="text-xs text-[#4F4F4F] mt-0.5">Kategori 'Ketertiban Umum' telah ditambahkan</p>
                        <p class="text-[11px] text-[#BDBDBD] mt-1">Admin Super • 5 menit yang lalu</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[21px] mt-1.5 w-2.5 h-2.5 rounded-full bg-[#2F80ED] border-2 border-white"></div>
                        <p class="text-sm font-bold text-[#111111]">OPD Diperbarui</p>
                        <p class="text-xs text-[#4F4F4F] mt-0.5">Kepala Dinas PUPR diganti menjadi Ir. Made Sudarma</p>
                        <p class="text-[11px] text-[#BDBDBD] mt-1">Admin Super • 1 jam yang lalu</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[21px] mt-1.5 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-[#111111]">Pengguna Baru</p>
                        <p class="text-xs text-[#4F4F4F] mt-0.5">Akun Camat Kecamatan Sukawati telah dibuat</p>
                        <p class="text-[11px] text-[#BDBDBD] mt-1">Admin Super • 3 jam yang lalu</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[21px] mt-1.5 w-2.5 h-2.5 rounded-full bg-[#2F80ED] border-2 border-white"></div>
                        <p class="text-sm font-bold text-[#111111]">Pemetaan Kategori</p>
                        <p class="text-xs text-[#4F4F4F] mt-0.5">Kategori 'Infrastruktur Jalan' dipetakan ke Dinas PUPR</p>
                        <p class="text-[11px] text-[#BDBDBD] mt-1">Admin Super • 5 jam yang lalu</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[21px] mt-1.5 w-2.5 h-2.5 rounded-full bg-[#2F80ED] border-2 border-white"></div>
                        <p class="text-sm font-bold text-[#111111]">Kecamatan Diperbarui</p>
                        <p class="text-xs text-[#4F4F4F] mt-0.5">Data kontak Kecamatan Ubud diperbarui</p>
                        <p class="text-[11px] text-[#BDBDBD] mt-1">Admin Super • 1 hari yang lalu</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="bg-[#FDFDFD] p-6 rounded-xl shadow-sm border border-[#E0E0E0]">
            <h3 class="text-lg font-bold text-[#111111] mb-4">Status Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <div>
                        <p class="text-xs text-[#828282]">Database</p>
                        <p class="text-sm font-bold text-[#111111]">Aktif</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <div>
                        <p class="text-xs text-[#828282]">API Server</p>
                        <p class="text-sm font-bold text-[#111111]">Aktif</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <div>
                        <p class="text-xs text-[#828282]">Storage</p>
                        <p class="text-sm font-bold text-[#111111]">85% Available</p>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection