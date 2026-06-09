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
                    <div class="w-8 h-8 rounded-full bg-[#C01818] text-white flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <span class="font-semibold text-[#111] text-sm">Verifikasi NIK</span>
                </div>
                
                <div class="w-12 h-0.5 bg-gray-200"></div>
                
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-transparent border border-gray-300 text-[#6B7280] flex items-center justify-center font-medium text-sm">
                        2
                    </div>
                    <span class="font-medium text-[#6B7280] text-sm">Data Lengkap</span>
                </div>
            </div>

            <div class="bg-[#F9FAFB] p-8 sm:p-10 rounded-xl shadow-sm border border-gray-100 w-full max-w-2xl">
                
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-[#111] mb-2">Verifikasi Identitas</h3>
                    <p class="text-[#6B7280] text-sm">Masukkan NIK dan tanggal lahir Anda untuk verifikasi data ke Disdukcapil</p>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-[#111] mb-2">
                            Nomor Induk Kependudukan (NIK) <span class="text-[#C01818]">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 text-[#111] bg-white">
                        <p class="mt-2 text-xs text-[#6B7280]">Satu NIK hanya dapat didaftarkan untuk satu akun aktif</p>
                    </div>

                    <div>
                        <label for="dob" class="block text-sm font-semibold text-[#111] mb-2">
                            Tanggal Lahir <span class="text-[#C01818]">*</span>
                        </label>
                        <input type="date" id="dob" name="dob" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition text-[#111] bg-white">
                    </div>

                    <button type="button" class="w-full flex justify-center items-center gap-2 bg-[#C01818] hover:bg-[#a11414] text-white p-3.5 rounded-lg font-semibold transition duration-150 mt-4">
                        Verifikasi NIK
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>

            <p class="mt-8 text-center text-sm text-[#6B7280]">
                Sudah punya akun? <a href="#" class="font-semibold text-[#C01818] hover:underline">Masuk di sini</a>
            </p>

        </div>
@endsection