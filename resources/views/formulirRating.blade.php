@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8 md:py-12 font-plus-jakarta-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Beri Rating & Ulasan</h1>
                <p class="text-sm text-gray-500">Bantu kami meningkatkan kualitas layanan</p>
            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-10 h-10 rounded-full bg-[#E8F5E9] text-[#27AE60] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Laporan Telah Diselesaikan</h2>
                                <p class="text-sm text-gray-500 mt-1">Terima kasih telah menggunakan layanan SILABA</p>
                            </div>
                        </div>

                        <hr class="border-gray-50 mb-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nomor Tiket</p>
                                <p class="text-sm font-bold text-gray-900">TKT-2024-003</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Kategori</p>
                                <p class="text-sm font-bold text-gray-900">Infrastruktur</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Ditangani Oleh</p>
                                <p class="text-sm font-bold text-gray-900">Dinas Pekerjaan Umum dan Penataan Ruang</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Petugas</p>
                                <p class="text-sm font-bold text-gray-900">I Made Wirawan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-2">Bagaimana pengalaman Anda?</h2>
                        <p class="text-sm text-gray-500 mb-6">Pilih bintang untuk memberikan penilaian</p>

                        <div class="flex flex-row-reverse justify-center items-center gap-2 mb-3 cursor-pointer">
                            <svg class="peer peer-hover:text-secondary-500 peer-hover:fill-secondary-500 w-10 h-10 text-gray-100 hover:text-secondary-500 hover:fill-secondary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <svg class="peer peer-hover:text-secondary-500 peer-hover:fill-secondary-500 w-10 h-10 text-gray-100 hover:text-secondary-500 hover:fill-secondary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <svg class="peer peer-hover:text-secondary-500 peer-hover:fill-secondary-500 w-10 h-10 text-gray-100 hover:text-secondary-500 hover:fill-secondary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <svg class="peer peer-hover:text-secondary-500 peer-hover:fill-secondary-500 w-10 h-10 text-gray-100 hover:text-secondary-500 hover:fill-secondary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <svg class="peer peer-hover:text-secondary-500 peer-hover:fill-secondary-500 w-10 h-10 text-gray-100 hover:text-secondary-500 hover:fill-secondary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">Pilih Rating</p>
                    </div>

                    <div class="mb-2">
                        <label for="komentar" class="block text-sm font-semibold text-gray-900 mb-2">
                            Komentar atau Saran <span class="font-normal text-gray-500">(Opsional)</span>
                        </label>
                        <textarea id="komentar" rows="4"
                            class="w-full bg-gray-50 border border-gray-100 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-3.5 outline-none transition-colors resize-none"
                            placeholder="Ceritakan pengalaman Anda menggunakan layanan SILABA..."></textarea>
                    </div>
                    <p class="text-xs text-gray-500">Masukan Anda sangat berharga untuk meningkatkan kualitas layanan kami</p>
                </div>

                <div class="bg-primary-50 border border-primary-100 rounded-xl p-5 flex items-start sm:items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Dapatkan 25 Poin Reward!</h3>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                            Setelah memberikan rating, Anda akan mendapatkan 25 poin reward yang dapat ditukar dengan berbagai hadiah menarik.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="button" class="px-6 md:px-8 py-3.5 bg-white border border-gray-200 text-gray-900 text-sm font-bold rounded-lg hover:bg-gray-50 transition-colors focus:ring-4 focus:ring-gray-100">
                        Batal
                    </button>
                    <button type="button" class="flex-1 flex justify-center items-center gap-2 px-6 py-3.5 bg-primary-500/50 text-white text-sm font-bold rounded-lg hover:bg-primary-500 transition-colors focus:ring-4 focus:ring-primary-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Kirim Rating
                    </button>
                </div>

            </div>

        </div>
    </div>
@endsection
