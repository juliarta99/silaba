@extends('layouts.app')

@section('content')
<div class="bg-white border-b border-gray-200 pt-8 pb-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-[#111]">Profil Saya</h1>
        <p class="text-[#6B7280] mt-2">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
</div>

<div class="bg-gray-50 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div class="relative shrink-0">
                <div class="w-24 h-24 rounded-full bg-red-100 text-[#C01818] flex items-center justify-center text-3xl font-bold">
                    IWS
                </div>
                <button class="absolute bottom-0 right-0 w-8 h-8 bg-[#C01818] hover:bg-[#a11414] text-white rounded-full flex items-center justify-center border-2 border-white transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </button>
            </div>
            
            <div class="text-center sm:text-left mt-2 sm:mt-0">
                <h2 class="text-2xl font-bold text-[#111]">I Wayan Sukarta</h2>
                <p class="text-[#6B7280] mt-1">Warga Terverifikasi</p>
                <div class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    NIK Terverifikasi
                </div>
            </div>
        </div>

        <div class="bg-[#FFFF] p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-xl font-bold text-[#111]">Informasi Dasar</h3>
                <a href="#" class="text-[#C01818] font-semibold text-sm hover:underline">Edit Profil</a>
            </div>

            <form class="space-y-6">
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#111] mb-2">
                        <svg class="w-4 h-4 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        NIK
                    </label>
                    <input type="text" value="5171012345678901" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#6B7280] outline-none cursor-not-allowed">
                    <p class="mt-1.5 text-xs text-[#6B7280]">NIK tidak dapat diubah setelah verifikasi</p>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#111] mb-2">
                        <svg class="w-4 h-4 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Nama Lengkap
                    </label>
                    <input type="text" value="I Wayan Sukarta" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#6B7280] outline-none cursor-not-allowed">
                    <p class="mt-1.5 text-xs text-[#6B7280]">Nama sesuai data Disdukcapil (tidak dapat diubah)</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#111] mb-2">
                        Jenis Kelamin
                    </label>
                    <input type="text" value="" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#6B7280] outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#111] mb-2">
                        Tanggal Lahir
                    </label>
                    <input type="text" value="" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#6B7280] outline-none cursor-not-allowed">
                    <p class="mt-1.5 text-xs text-[#6B7280]">Tanggal lahir tidak dapat diubah</p>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#111] mb-2">
                        <svg class="w-4 h-4 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email
                    </label>
                    <input type="email" value="wayan.sukarta@email.com" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#111] outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#111] mb-2">
                        <svg class="w-4 h-4 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Nomor WhatsApp
                    </label>
                    <input type="text" value="081234567890" readonly disabled
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-[#111] outline-none cursor-not-allowed">
                    <p class="mt-1.5 text-xs text-[#6B7280]">Nomor WhatsApp tidak dapat diubah (terverifikasi dengan OTP)</p>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-start gap-4">
                <div class="mt-1">
                    <svg class="w-6 h-6 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#111]">Keamanan Akun</h3>
                    <p class="text-[#6B7280] text-sm mt-1">Ubah password untuk menjaga keamanan akun Anda</p>
                </div>
            </div>
            
            <a href="#" class="text-[#C01818] font-semibold text-sm hover:underline whitespace-nowrap mt-2 sm:mt-0 sm:ml-4">
                Ubah Password
            </a>
        </div>

    </div>
</div>
@endsection