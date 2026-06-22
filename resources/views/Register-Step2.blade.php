@extends('layouts.app')

@section('content')  
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <div class="flex justify-center items-center mb-4">
                <a href="#" class="text-4xl font-bold tracking-tight text-[#C01818] flex items-center">
                    <span>S</span>
                    <svg class="w-5 h-10 mx-0.5 text-[#C01818]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L10 8h4l-2 -6zM11 9v10h2V9h-2zM9 19h6v2H9v-2z"/>
                    </svg>
                    <span>LABA</span>
                </a>
            </div>
            <h2 class="text-3xl font-bold text-[#111] mb-2">Daftar Akun SILABA</h2>
            <p class="text-[#6B7280]">Buat akun untuk mendapatkan akses penuh ke layanan SILABA</p>
        </div>

        <div class="flex items-center justify-center space-x-4 mb-8">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-[#10B981] text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-medium text-[#6B7280] text-sm">Verifikasi NIK</span>
            </div>
            
            <div class="w-12 h-0.5 bg-gray-200"></div>
            
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-[#C01818] text-white flex items-center justify-center font-bold text-sm">
                    2
                </div>
                <span class="font-bold text-[#111] text-sm">Data Lengkap</span>
            </div>
        </div>

        <div class="bg-[#F9FAFB] p-8 sm:p-10 rounded-xl shadow-sm border border-gray-100 w-full max-w-2xl">
            
            <div class="mb-6">
                <h3 class="text-xl font-bold text-[#111] mb-2">Lengkapi Data Anda</h3>
                <p class="text-[#6B7280] text-sm">NIK berhasil diverifikasi. Lengkapi data di bawah ini untuk menyelesaikan pendaftaran.</p>
            </div>

            <form action="#" method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-sm font-semibold text-[#111] mb-2">
                        Nama Lengkap <span class="text-[#C01818]">*</span>
                    </label>
                    <input type="text" value="I Wayan Sukarta" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed outline-none">
                    <p class="mt-1.5 text-xs text-[#6B7280]">Nama otomatis dari data Disdukcapil (tidak dapat diubah)</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#111] mb-2">
                            Email <span class="text-[#C01818]">*</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition text-[#111] bg-white">
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-[#111] mb-2">
                            Jenis Kelamin <span class="text-[#C01818]">*</span>
                        </label>
                        <select id="gender" name="gender" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition text-[#111] bg-white appearance-none">
                            <option value="" disabled selected hidden></option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-[#111] mb-2">
                        Nomor WhatsApp Aktif <span class="text-[#C01818]">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" placeholder="08123456789" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 text-[#111] bg-white">
                    <p class="mt-1.5 text-xs text-[#6B7280]">Nomor ini akan digunakan untuk verifikasi OTP dan notifikasi laporan</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#111] mb-2">
                            Password <span class="text-[#C01818]">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 text-[#111] bg-white pr-10">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B7280] hover:text-[#111]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#111] mb-2">
                            Konfirmasi Password <span class="text-[#C01818]">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 text-[#111] bg-white pr-10">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B7280] hover:text-[#111]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <p class="text-sm text-[#6B7280] leading-relaxed px-4">
                        Saya menyetujui <a href="#" class="font-bold text-[#C01818] hover:underline">Syarat dan Ketentuan</a> serta <a href="#" class="font-bold text-[#C01818] hover:underline">Kebijakan Privasi</a> sesuai UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi
                    </p>
                </div>

                <div class="flex items-center gap-4 mt-6 pt-2">
                    <a href="#" class="px-6 py-3.5 border border-gray-300 rounded-lg text-[#111] font-semibold hover:bg-gray-50 transition duration-150 text-center">
                        Kembali
                    </a>
                    <button type="submit" class="flex-1 flex justify-center items-center gap-2 bg-[#C01818] hover:bg-[#a11414] text-white p-3.5 rounded-lg font-semibold transition duration-150">
                        Daftar Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-sm text-[#6B7280]">
            Sudah punya akun? <a href="#" class="font-semibold text-[#C01818] hover:underline">Masuk di sini</a>
        </p>

    </div>
@endsection