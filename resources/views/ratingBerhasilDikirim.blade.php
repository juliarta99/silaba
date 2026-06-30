@extends('layouts.app')

@section('content')
    <div class="bg-white min-h-screen py-12 px-4 sm:px-6 lg:px-8 font-plus-jakarta-sans flex flex-col items-center">

        <div class="text-center mb-8 mt-4">
            <div class="w-20 h-20 mx-auto bg-[#00C853] rounded-full flex items-center justify-center mb-6 shadow-sm border-4 border-[#E8F5E9]">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Rating Berhasil Dikirim!</h1>
            <p class="text-sm md:text-base text-gray-500">Terima kasih atas penilaian Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-gray-100 p-6 md:p-10 w-full max-w-2xl text-center mb-6">

            <p class="text-xs text-gray-500 mb-1">Rating Anda untuk</p>
            <p class="text-lg font-bold text-primary-500 mb-6">TKT-2024-XXX</p>

            <div class="flex justify-center items-center gap-2 mb-4">
                @for ($i = 0; $i < 5; $i++)
                <svg class="w-10 h-10 text-secondary-500 fill-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                @endfor
            </div>
            <p class="text-base font-bold text-gray-900 mb-8">5 dari 5 Bintang</p>

            <div class="space-y-3">
                <div class="flex items-start gap-3 bg-[#E8F5E9] border border-[#C8E6C9] p-4 rounded-xl text-left">
                    <svg class="w-5 h-5 text-[#27AE60] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-sm font-bold text-[#27AE60]">Rating Tersimpan</p>
                        <p class="text-xs text-[#27AE60] mt-1 opacity-90">Penilaian Anda akan membantu kami meningkatkan kualitas layanan SILABA</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 bg-secondary-50 border border-secondary-100 p-4 rounded-xl text-left">
                    <svg class="w-5 h-5 text-secondary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    <div>
                        <p class="text-sm font-bold text-secondary-500">Poin Reward Ditambahkan: +25 Poin</p>
                        <p class="text-xs text-secondary-500 mt-1 opacity-90">Poin reward telah ditambahkan ke akun Anda dan dapat ditukar dengan berbagai hadiah menarik</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 p-4 rounded-xl text-left">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-500">Laporan Ditutup</p>
                        <p class="text-xs text-blue-500 mt-1 opacity-90">Laporan ini telah ditutup dan statusnya akan diperbarui di dashboard Anda</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-primary-50 rounded-xl  border border-primary-100 p-5 w-full max-w-2xl flex items-start sm:items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm border-2 border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Kumpulkan Lebih Banyak Poin!</h3>
                <p class="text-xs text-gray-700 leading-relaxed">
                    Buat laporan baru dan dapatkan lebih banyak poin reward setiap kali laporan Anda terverifikasi selesai.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-3 w-full max-w-2xl mb-8">
            <a href="#" class="w-full sm:w-auto px-6 py-3.5 bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors text-center shadow-sm">
                Ke Dashboard
            </a>

            <a href="#" class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3.5 bg-secondary-500 hover:bg-secondary-700 text-gray-900 text-sm font-bold rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                Klaim Reward
            </a>

            <a href="#" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-gray-200 text-gray-900 hover:bg-primary-500 hover:text-white text-sm font-bold rounded-lg transition-colors text-center shadow-sm">
                Buat Laporan Baru
            </a>
        </div>

        <p class="text-xs text-gray-500 text-center max-w-lg leading-relaxed pb-8">
            Terima kasih telah menggunakan SILABA untuk melaporkan masalah di Kabupaten Badung. Partisipasi Anda sangat berarti bagi kami!
        </p>

    </div>
@endsection
