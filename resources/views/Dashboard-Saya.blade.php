<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=PlusJakartaSans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'PlusJakartaSans', sans-serif; }
    </style>
</head>
<body class="bg-[#f9fafb] min-h-screen">

    <div class="max-w-[1440px] mx-auto p-8">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, I Wayan Sukarta</p>
        </div>

        {{-- Main Grid Layout --}}
        <div class="grid grid-cols-12 gap-6">
            
            {{-- Kolom Kiri: Utama (Lebar 8/12) --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">
                
                {{-- Row 1: 4 Ringkasan Kartu Statistik --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    {{-- Total Laporan --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 leading-none">8</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Total Laporan</div>
                        </div>
                    </div>

                    {{-- Sedang Diproses --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 leading-none">2</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Sedang Diproses</div>
                        </div>
                    </div>

                    {{-- Selesai --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 leading-none">6</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Selesai</div>
                        </div>
                    </div>

                    {{-- Poin Reward --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v5l3-3.001L18 20v-5M6 11a6 6 0 1112 0 6 6 0 01-12 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 leading-none">150</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Poin Reward</div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Aksi Cepat --}}
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-xl p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition">
                            <div class="text-rose-500 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">Buat Laporan</span>
                            <span class="text-[11px] text-gray-400 mt-0.5">Laporkan masalah baru</span>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition">
                            <div class="text-rose-500 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">Laporan Saya</span>
                            <span class="text-[11px] text-gray-400 mt-0.5">Lihat semua laporan</span>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition">
                            <div class="text-rose-500 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v5l3-3.001L18 20v-5M6 11a6 6 0 1112 0 6 6 0 01-12 0z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">Klaim Reward</span>
                            <span class="text-[11px] text-gray-400 mt-0.5">150 poin tersedia</span>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Laporan Terbaru --}}
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-lg font-bold text-gray-900">Laporan Terbaru</h2>
                        <a href="#" class="text-xs font-bold text-rose-600 flex items-center gap-1 hover:underline">
                            Lihat Semua
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Item Laporan 1 --}}
                        <div class="border border-gray-100 rounded-xl p-4 flex gap-4 items-start">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1635068741358-ab1b9813623f?q=80&w=2060&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover" alt="Jalan Berlubang">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 font-medium">TKT-2024-005</span>
                                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Diproses</span>
                                    </div>
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        2 Jun 2026
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1.5">Jalan Berlubang di Jalan Raya Kuta</h3>
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded text-[11px] font-medium">Infrastruktur</span>
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[11px] font-medium">Jalan Rusak</span>
                                    <span class="bg-sky-50 text-sky-600 px-2 py-0.5 rounded text-[11px] font-medium">Lubang Aspal</span>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    Petugas sedang menuju lokasi
                                </div>
                            </div>
                        </div>

                        {{-- Item Laporan 2 --}}
                        <div class="border border-gray-100 rounded-xl p-4 flex gap-4 items-start">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=150" class="w-full h-full object-cover" alt="Tumpukan Sampah">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 font-medium">TKT-2024-003</span>
                                        <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Selesai</span>
                                    </div>
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        28 Mei 2026
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1.5">Tumpukan Sampah di Pantai Sanur</h3>
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="bg-teal-50 text-teal-600 px-2 py-0.5 rounded text-[11px] font-medium">Kebersihan</span>
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[11px] font-medium">Sampah</span>
                                    <span class="bg-cyan-50 text-cyan-600 px-2 py-0.5 rounded text-[11px] font-medium">Pantai</span>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Laporan telah diselesaikan
                                </div>
                            </div>
                        </div>

                        {{-- Item Laporan 3 --}}
                        <div class="border border-gray-100 rounded-xl p-4 flex gap-4 items-start">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?w=150" class="w-full h-full object-cover" alt="Lampu Jalan">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 font-medium">TKT-2024-001</span>
                                        <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Menunggu Verifikasi</span>
                                    </div>
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        25 Mei 2026
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1.5">Lampu Jalan Mati</h3>
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded text-[11px] font-medium">Infrastruktur</span>
                                    <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-[11px] font-medium">Penerangan Jalan</span>
                                    <span class="bg-slate-50 text-slate-600 px-2 py-0.5 rounded text-[11px] font-medium">Lampu Mati</span>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Menunggu konfirmasi penyelesaian dari Anda
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar (Lebar 4/12) --}}
            <div class="col-span-12 lg:col-span-4 space-y-6">
                
                {{-- Card Profil User --}}
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 font-bold text-xl mb-3 shadow-inner">
                        IWS
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">I Wayan Sukarta</h3>
                    <p class="text-xs text-gray-400 mb-5">wayan.sukarta@email.com</p>
                    <button class="w-full py-2 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                        Edit Profile
                    </button>
                </div>

                {{-- Card Panel Notifikasi --}}
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <h2 class="text-base font-bold text-gray-800">Notifikasi</h2>
                        </div>
                        <div class="w-2 h-2 bg-rose-600 rounded-full"></div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <p class="text-xs text-gray-700 leading-normal">Laporan TKT-2024-005 telah diproses oleh petugas</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">2 jam yang lalu</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <p class="text-xs text-gray-700 leading-normal">Anda mendapatkan 25 poin reward dari laporan TKT-2024-003</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">1 hari yang lalu</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <p class="text-xs text-gray-700 leading-normal">Laporan TKT-2024-001 menunggu verifikasi penyelesaian</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">2 hari yang lalu</span>
                        </div>
                    </div>
                    
                    <a href="#" class="text-xs font-bold text-rose-600 block text-center mt-4 hover:underline">
                        Lihat Semua Notifikasi
                    </a>
                </div>

                {{-- Card Poin Reward (Merah Maroon) --}}
                <div class="bg-[#b91c1c] text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v5l3-3.001L18 20v-5M6 11a6 6 0 1112 0 6 6 0 01-12 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm leading-tight">Poin Reward</h4>
                            <p class="text-[11px] text-rose-200">Kumpulkan lebih banyak poin</p>
                        </div>
                    </div>
                    
                    <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-4xl font-black">150</span>
                        <span class="text-xs text-rose-200">/ 200 poin</span>
                    </div>
                    
                    <div class="w-full bg-rose-950/50 rounded-full h-1.5 mb-2">
                        <div class="bg-white h-1.5 rounded-full" style="width: 75%"></div>
                    </div>
                    
                    <p class="text-[11px] text-rose-100 mb-4">50 poin lagi untuk mendapatkan reward berikutnya!</p>
                    
                    <button class="w-full bg-white text-[#b91c1c] py-2.5 rounded-lg font-bold text-xs shadow hover:bg-rose-50 transition tracking-wide uppercase">
                        Klaim Reward
                    </button>
                </div>

            </div>
        </div>
    </div>

</body>
</html>