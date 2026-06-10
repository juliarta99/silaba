<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Progress Laporan SILABA</title>
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

<div class="font-plus-jakarta bg-[#F8F9FA] min-h-screen py-8 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="max-w-[800px] mx-auto space-y-4">
        
        {{-- Header Section --}}
        <div class="mb-6">
            <a href="#" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Detail Tugas
            </a>
            <h1 class="text-[28px] font-bold text-gray-900 leading-tight">Update Progress</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Tambahkan update progress untuk tugas TKT-2024-014</p>
        </div>

        {{-- Container 1: Informasi Tugas --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-[16px] font-bold text-gray-900 mb-5">Informasi Tugas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-[13px] text-gray-500 mb-1 font-medium">Nomor Tiket</p>
                        <p class="font-bold text-sm text-gray-900">TKT-2024-014</p>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-500 mb-1 font-medium">Judul Laporan</p>
                        <p class="font-bold text-sm text-gray-900">Lampu Jalan Mati di Sunset Road</p>
                    </div>
                </div>
                <div>
                    <p class="text-[13px] text-gray-500 mb-2 font-medium">Status Saat Ini</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#EBF5FF] text-[#1E40AF]">
                        Diproses
                    </span>
                </div>
            </div>
        </div>

        {{-- Container 2: Form Update Progress --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-[16px] font-bold text-gray-900 mb-6">Form Update Progress</h2>
            
            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Update Status --}}
               {{-- Update Status --}}
<div>
    <label class="block text-sm font-bold text-gray-900 mb-1.5">Update Status <span class="text-[#C01818]">*</span></label>
    <div class="relative">
        <select class="w-full border border-gray-200 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[#C01818] focus:border-[#C01818] text-sm text-gray-500 appearance-none bg-white font-medium">
            <option value="" disabled selected>Pilih Status...</option>
            
            {{-- Tambahan pilihan Diproses --}}
            <option value="diproses">Diproses</option>
            
            <option value="selesai">Selesai</option>
        </select>
        
        {{-- Ikon panah dropdown (Opsional, agar lebih mirip select bawaan) --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div>
    <p class="mt-1.5 text-[12px] text-gray-400 font-medium">Pilih status yang sesuai dengan kondisi pekerjaan saat ini</p>
</div>

                {{-- Judul Progress --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-1.5">Judul Progress <span class="text-[#C01818]">*</span></label>
                    <input type="text" placeholder="Contoh: Survey lokasi telah dilakukan" class="w-full border border-gray-200 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[#C01818] focus:border-[#C01818] text-sm placeholder-gray-400 font-medium">
                </div>

                {{-- Deskripsi Progress --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-1.5">Deskripsi Progress <span class="text-[#C01818]">*</span></label>
                    <textarea rows="4" placeholder="Jelaskan secara detail progress yang telah dilakukan, temuan di lapangan, dan rencana selanjutnya..." class="w-full border border-gray-200 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[#C01818] focus:border-[#C01818] text-sm placeholder-gray-400 font-medium"></textarea>
                </div>

                {{-- Foto Dokumentasi --}}
                <div>
                    <label class="flex items-center text-sm font-bold text-gray-900 mb-1.5">
                        <svg class="w-[18px] h-[18px] mr-1.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Foto Dokumentasi <span class="text-gray-400 font-medium ml-1">(Opsional)</span>
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-7 pb-8 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors shadow-sm">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <div class="flex text-sm justify-center">
                                <span class="font-bold text-gray-900">Klik untuk upload foto</span>
                            </div>
                            <p class="text-[12px] text-gray-400 font-medium">PNG, JPG atau JPEG (Maks. 5MB per file)</p>
                        </div>
                    </div>
                </div>

               {{-- Estimasi Penyelesaian --}}
<div>
    <label class="flex items-center text-sm font-bold text-gray-900 mb-1.5">
        {{-- Ikon diganti menjadi Kalender agar lebih sesuai dengan input date --}}
        <svg class="w-[18px] h-[18px] mr-1.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Estimasi Penyelesaian <span class="text-gray-400 font-medium ml-1">(Opsional)</span>
    </label>
    
    {{-- Menggunakan type="date" dengan warna focus tetap #C01818 --}}
    <input type="date" class="w-full border border-gray-200 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[#C01818] focus:border-[#C01818] text-sm bg-white font-medium text-gray-700 uppercase">
    
    <p class="mt-1.5 text-[12px] text-gray-400 font-medium">Perkiraan kapan pekerjaan akan selesai</p>
</div>

                {{-- Catatan Internal --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-1.5">Catatan Internal <span class="text-gray-400 font-medium ml-1">(Opsional)</span></label>
                    <textarea rows="3" placeholder="Catatan khusus untuk internal OPD (tidak akan ditampilkan ke pelapor)..." class="w-full border border-gray-200 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[#C01818] focus:border-[#C01818] text-sm placeholder-gray-400 font-medium bg-[#F9FAFB]"></textarea>
                    <p class="mt-1.5 text-[12px] text-gray-400 font-medium">Catatan ini hanya untuk internal dan tidak akan dilihat oleh pelapor</p>
                </div>

            </form>
        </div>

        {{-- Container 3: Alert Box --}}
        <div class="bg-[#F0F7FF] border border-[#E0F2FE] rounded-lg p-4 flex items-start mt-2 shadow-sm">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-[20px] w-[20px] text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-[#1E40AF]">Informasi Penting</h3>
                <div class="mt-1 text-[13px] font-medium text-[#1E3A8A]">
                    <p>Update progress ini akan dikirimkan ke pelapor melalui WhatsApp. Pastikan informasi yang Anda berikan akurat dan mudah dipahami.</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-4 pt-2 pb-8">
            <button type="button" class="px-8 py-3 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none shadow-sm transition-colors">
                Batal
            </button>
            <button type="submit" class="flex-1 flex justify-center items-center px-6 py-3 bg-[#C01818] border border-transparent rounded-lg text-sm font-bold text-white hover:bg-[#A31414] focus:outline-none shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Update Progress
            </button>
        </div>

    </div>
</div>