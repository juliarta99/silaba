<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - SILABA</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#C01818',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans p-8">
<div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-[22px] font-extrabold text-gray-900 tracking-tight">Laporan Saya</h1>
                <p class="text-[13px] text-gray-500 mt-0.5">Kelola dan lacak semua laporan Anda</p>
            </div>
            <button class="bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white px-4 py-2.5 rounded-md text-[13px] font-semibold flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Baru
            </button>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-5 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Filter Status</label>
                    <div class="relative">
                       <select id="statusFilter" class="w-full border border-gray-200 rounded-md p-2.5 text-[13px] text-gray-600 bg-gray-50 focus:ring-1 focus:ring-primary-red focus:border-primary-red outline-none appearance-none transition-all">
    <option value="" disabled selected>Pilih Status...</option>
    <option value="semua">Semua Status</option>
</select>

<script>
    // Data status (nantinya ini bisa berasal dari fetch API ke database)
    const statusOptions = [
        { value: 'baru', label: 'Baru' },
        { value: 'diproses', label: 'Diproses' },
        { value: 'menunggu_verifikasi', label: 'Menunggu Verifikasi' },
        { value: 'ditutup', label: 'Ditutup' }
    ];

    const selectElement = document.getElementById('statusFilter');

    // Memasukkan opsi ke dalam dropdown secara otomatis
    statusOptions.forEach(status => {
        const option = document.createElement('option');
        option.value = status.value;
        option.textContent = status.label;
        selectElement.appendChild(option);
    });
</script>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Periode</label>
<div class="flex items-center gap-2">
    <input type="date" title="Dari Tanggal" class="w-full border border-gray-200 rounded-md p-2 text-[12px] text-gray-600 bg-gray-50 focus:ring-1 focus:ring-primary-red focus:border-primary-red outline-none transition-all" />
    <span class="text-gray-400 font-bold">-</span>
    <input type="date" title="Sampai Tanggal" class="w-full border border-gray-200 rounded-md p-2 text-[12px] text-gray-600 bg-gray-50 focus:ring-1 focus:ring-primary-red focus:border-primary-red outline-none transition-all" />
</div>
                </div>
                <div class="sm:col-span-2 md:col-span-1 flex items-end">
                    <button class="w-full border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 active:bg-gray-100 transition-colors rounded-md p-2.5 text-[13px] font-semibold flex justify-center items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-3">
                <p class="text-[12px] text-gray-400">Menampilkan <span class="font-bold text-gray-700">5</span> dari <span class="font-bold text-gray-700">5</span> laporan</p>
            </div>
        </div>

        <div class="space-y-4">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-5">
                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-[220px] h-[160px] sm:h-[180px] flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=500" alt="Jalan Berlubang" class="w-full h-full object-cover rounded-md" />
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between space-y-3 md:space-y-0 py-0.5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[13px] font-extrabold text-gray-800 tracking-wide">TKT-2024-005</span>
                                <span class="px-2 py-0.5 bg-[#F59E0B] text-white text-[10px] font-bold rounded">Diproses</span>
                                <span class="px-2 py-0.5 bg-red-50 text-primary-red text-[10px] font-bold rounded">Infrastruktur</span>
                            </div>
                            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2.5">Jalan Berlubang di Jalan Raya Kuta</h3>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Jalan Rusak</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Lubang/Aspal</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Berbahaya</span>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-[12px] text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. Raya Kuta, Kec. Kuta</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Dilaporkan: 2 Jun 2026</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-gray-600">Update terakhir: Petugas sedang menuju lokasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-[200px] flex flex-col justify-start flex-shrink-0 md:pt-0.5">
                        <button class="w-full bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#FED7AA] rounded-lg shadow-sm p-4 sm:p-5">
                <div class="bg-[#FFF7ED] border border-[#FFEDD5] rounded-md p-3 mb-4 flex items-center gap-2.5">
                    <div class="bg-[#EA580C] text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-extrabold flex-shrink-0">!</div>
                    <p class="text-[12px] text-[#C2410C] font-semibold">Perlu Verifikasi: Konfirmasi apakah masalah sudah selesai</p>
                </div>

                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-[220px] h-[160px] sm:h-[180px] flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1526951521990-620dc14c214b?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Sampah di Pantai" class="w-full h-full object-cover rounded-md" />
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between space-y-3 md:space-y-0 py-0.5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[13px] font-extrabold text-gray-800 tracking-wide">TKT-2024-004</span>
                                <span class="px-2 py-0.5 bg-[#EA580C] text-white text-[10px] font-bold rounded">Menunggu Verifikasi</span>
                                <span class="px-2 py-0.5 bg-red-50 text-primary-red text-[10px] font-bold rounded">Kebersihan</span>
                            </div>
                            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2.5">Tumpukan Sampah di Pantai Jimbaran</h3>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Sampah</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Pantai</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Limbah Plastik</span>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-[12px] text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Pantai Jimbaran, Kec. Kuta Selatan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Dilaporkan: 28 Mei 2026</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-gray-600 leading-tight">Update terakhir: Petugas menyatakan selesai, menunggu konfirmasi Anda</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-[200px] flex flex-col gap-2 flex-shrink-0 md:pt-0.5">
                        <button class="w-full bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Lihat Detail
                        </button>
                        <button class="w-full bg-[#00C853] hover:bg-[#00A844] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            Konfirmasi Selesai
                        </button>
                        <button class="w-full bg-white hover:bg-gray-50 border border-gray-300 transition-colors duration-200 text-gray-700 text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Laporkan Belum Selesai
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#FED7AA] rounded-lg shadow-sm p-4 sm:p-5">
                <div class="bg-[#FFF7ED] border border-[#FFEDD5] rounded-md p-3 mb-4 flex items-center gap-2.5">
                    <div class="bg-[#EA580C] text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-extrabold flex-shrink-0">!</div>
                    <p class="text-[12px] text-[#C2410C] font-semibold">Beri Rating: Bantu kami meningkatkan layanan dengan memberikan penilaian</p>
                </div>

                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-[220px] h-[160px] sm:h-[180px] flex-shrink-0">
                        <img src=" https://images.unsplash.com/photo-1647678803694-a3bd223e4414?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Lampu Jalan Mati" class="w-full h-full object-cover rounded-md" />
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between space-y-3 md:space-y-0 py-0.5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[13px] font-extrabold text-gray-800 tracking-wide">TKT-2024-003</span>
                                <span class="px-2 py-0.5 bg-[#6B7280] text-white text-[10px] font-bold rounded">Ditutup</span>
                                <span class="px-2 py-0.5 bg-red-50 text-primary-red text-[10px] font-bold rounded">Infrastruktur</span>
                            </div>
                            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2.5">Lampu Jalan Mati di Sunset Road</h3>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Penerangan Jalan</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Lampu Mati</span>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-[12px] text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. Sunset Road, Kec. Kuta</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Dilaporkan: 25 Mei 2026</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-gray-600">Update terakhir: Laporan telah diselesaikan dan ditutup</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-[200px] flex flex-col gap-2 flex-shrink-0 md:pt-0.5">
                        <button class="w-full bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Lihat Detail
                        </button>
                        <button class="w-full bg-[#FFB300] hover:bg-[#E6A100] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Beri Rating
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-5">
                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-[220px] h-[160px] sm:h-[180px] flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1775620783106-4af2e979710d?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="PKL Parkir" class="w-full h-full object-cover rounded-md" />
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between space-y-3 md:space-y-0 py-0.5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[13px] font-extrabold text-gray-800 tracking-wide">TKT-2024-002</span>
                                <span class="px-2 py-0.5 bg-[#3B82F6] text-white text-[10px] font-bold rounded">Baru</span>
                                <span class="px-2 py-0.5 bg-red-50 text-primary-red text-[10px] font-bold rounded">Ketertiban</span>
                            </div>
                            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2.5">PKL Parkir di Trotoar</h3>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Drainase</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Banjir</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Saluran</span>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-[12px] text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. Pantai Kuta, Kec. Kuta</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Dilaporkan: 15 Mei 2026</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-gray-600">Update terakhir: Laporan diterima, menunggu penugasan petugas</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-[200px] flex flex-col justify-start flex-shrink-0 md:pt-0.5">
                        <button class="w-full bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-5">
                <div class="flex flex-col md:flex-row gap-5">
                    <div class="w-full md:w-[220px] h-[160px] sm:h-[180px] flex-shrink-0">
                        <img src=" https://images.unsplash.com/photo-1662883914604-f44ea7cc5b74?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fHBsYXN0aWMlMjBkaXJ0eSUyMGRyYWluYWdlfGVufDB8fDB8fHww" alt="Saluran Air Tersumbat" class="w-full h-full object-cover rounded-md" />
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between space-y-3 md:space-y-0 py-0.5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[13px] font-extrabold text-gray-800 tracking-wide">TKT-2024-001</span>
                                <span class="px-2 py-0.5 bg-[#3B82F6] text-white text-[10px] font-bold rounded">Baru</span>
                                <span class="px-2 py-0.5 bg-red-50 text-primary-red text-[10px] font-bold rounded">Infrastruktur</span>
                            </div>
                            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2.5">Saluran Air Tersumbat</h3>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Drainase</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Banjir</span>
                                <span class="px-2.5 py-1 bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E40AF] text-[11px] font-medium rounded">Saluran</span>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-[12px] text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. Gatot Subroto, Kec. Mengwi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Dilaporkan: 15 Mei 2026</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-gray-600">Update terakhir: Laporan diterima, menunggu penugasan petugas</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-[200px] flex flex-col justify-start flex-shrink-0 md:pt-0.5">
                        <button class="w-full bg-primary-red hover:bg-[#A01414] transition-colors duration-200 text-white text-[13px] font-semibold py-2.5 rounded-md text-center shadow-sm">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
  