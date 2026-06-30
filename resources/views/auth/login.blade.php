@extends('layouts.app')

@section('title', 'Masuk ke SILABU')

@section('content')
<div class="min-h-[calc(100vh-68px)] flex flex-col items-center justify-center py-36 px-4 sm:px-6 lg:px-8 bg-gray-10">

    {{-- ── Logo + Heading ── --}}
    <div class="text-center mb-8">
        <a href="{{ route('home') }}" aria-label="SILABU" class="inline-block mb-5">
            <svg class="h-9 w-auto mx-auto" viewBox="0 0 159 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M105.952 4.83771C109.752 4.82264 113.845 4.63145 117.59 5.12775C124.732 6.07393 127.108 14.7249 120.885 18.7967C124.914 20.7549 126.358 23.8259 125.41 28.3719C124.997 30.3462 123.552 31.6972 121.853 32.6883C118.203 34.4076 113.101 34.0512 109.077 34.0477L100.4 34.035C100.273 24.5441 100.309 14.3502 100.415 4.85626L105.952 4.83771ZM117.006 21.2889C113.363 20.8703 109.056 21.0788 105.362 21.0936L105.347 29.9969C108.784 29.9883 117.056 30.571 119.551 28.6854C122.007 25.9129 120.579 21.6995 117.006 21.2889ZM118.515 10.3592C115.96 8.13877 108.619 8.87954 105.37 8.91095C105.338 11.6594 105.33 14.4085 105.346 17.157C108.839 17.171 115.422 17.7958 118.204 15.9549C119.701 14.4449 120.062 11.7029 118.515 10.3592Z" fill="#C01818"/>
                <path d="M83.6111 4.84888C85.1111 7.85391 86.6779 11.55 88.0652 14.6418C90.9412 21.1175 93.859 27.5753 96.8181 34.0139C95.1224 34.0769 93.2139 34.0351 91.5027 34.0354C90.469 31.6958 89.4619 29.3449 88.4802 26.9827L83.1453 26.9729C79.9593 26.9526 76.7716 26.9639 73.5857 27.0061C72.9927 28.8382 71.4897 32.1678 70.7087 34.0266C69.0031 34.0417 67.2971 34.0391 65.5916 34.0168C68.4208 27.2694 71.6288 20.3565 74.6208 13.6633L77.2605 7.76685C77.6351 6.93087 78.0504 5.94599 78.4841 5.14185C78.6461 4.84283 78.7173 4.8962 79.0525 4.83423L83.6111 4.84888ZM80.9822 9.93091C79.2949 14.0658 77.2616 19.0353 75.3982 23.0745L81.6169 23.0813L86.6941 23.0686C86.1656 21.7167 81.3961 10.2687 80.9822 9.93091Z" fill="#C01818"/>
                <path d="M145.269 4.82129C149.778 14.1745 154.007 24.4728 158.369 34.0156C156.657 34.0667 154.861 34.0324 153.14 34.0244C152.076 31.7015 151.161 29.2972 150.085 26.9668L145.032 26.9785L135.397 26.9717C134.666 29.0239 133.351 31.9267 132.496 34.0244C130.745 34.0478 128.993 34.044 127.242 34.0127L136.652 12.9482C137.749 10.4861 139.014 7.5651 140.214 5.11914C140.345 4.85097 140.52 4.87258 140.776 4.83398C142.273 4.84919 143.772 4.84494 145.269 4.82129ZM137.121 23.0723L142.92 23.0801L148.386 23.0703C147.204 20.065 145.842 17.053 144.59 14.0674C144.325 13.4385 143.061 10.4026 142.726 9.9248L137.121 23.0723Z" fill="#C01818"/>
                <path d="M10.4931 4.98316C14.4252 4.41542 18.769 5.61007 22.1328 7.68049C21.548 8.99514 21.1189 10.2856 20.4784 11.635C16.8956 9.36204 10.552 7.51274 6.83873 10.7925C6.31099 11.2585 5.71859 12.3385 5.81352 13.0554C6.59916 18.9916 17.9844 16.9509 21.3252 21.3933C22.6208 22.772 23.3034 24.4372 23.232 26.4201C22.9765 33.5095 16.2742 35.0588 10.6418 34.9199C6.29409 34.3975 3.47296 33.7957 0 30.9281C0.652254 29.6534 1.2762 28.3644 1.87155 27.0622C5.47873 29.7726 8.86394 31.1395 13.4563 30.624C15.871 30.353 18.6354 29.1171 18.2349 26.2599C17.717 22.5637 11.6594 22.3181 8.84099 21.3539C6.96648 20.7125 5.22845 20.3257 3.61409 19.0832C0.343099 16.7415 0.0987314 11.7099 2.55873 8.67415C4.61577 6.1357 7.36761 5.37753 10.4931 4.98316Z" fill="#C01818"/>
                <path d="M29.4756 27.8715C30.9877 26.1693 31.0959 25.2135 31.5011 23.0436C31.598 22.5246 32.0315 21.8173 32.1315 21.2687C32.4314 19.9117 31.6562 18.6162 31.6781 17.2489C31.6987 15.9642 32.4578 15.2103 32.6608 14.0142C32.7721 13.0007 32.0313 11.9394 32.0469 10.8584C32.0077 9.76756 32.8285 8.93413 32.9805 7.90219C33.1114 7.01321 32.9719 5.96495 32.9379 5.05421C32.8912 3.79764 33.2074 2.69804 33.5255 1.5005C33.6498 1.03258 33.7593 0.447336 33.9611 0.0243701L34.0474 0C34.4389 0.651947 34.2232 2.6564 34.2615 3.45371C34.3643 5.59128 35.8169 7.35221 35.1199 9.55737C35.0648 9.73137 34.8783 10.5153 34.8799 10.6792C34.8915 11.888 35.7288 12.5281 35.8241 13.8415C35.9173 15.1251 35.1237 16.1613 35.0862 17.2122C35.0503 18.2163 35.8842 19.2858 35.9955 20.5575C36.123 22.0142 35.1101 23.6258 35.6938 24.7419C36.1292 25.5743 37.6357 25.9915 37.3197 27.2551C37.3141 27.2772 37.3084 27.2992 37.3025 27.3212C36.9213 27.3387 36.8553 26.8268 36.2922 27.051C36.0131 27.5355 36.4604 27.6918 36.1852 27.8604C35.5594 27.9041 34.9014 27.8832 34.2724 27.8757C33.8769 26.5353 33.6052 25.2964 33.8743 23.8851C34.0864 22.7728 34.4415 21.6577 34.3702 20.5144C34.2936 19.2852 33.5968 18.2206 33.7074 16.9605C33.7942 15.9713 34.4479 15.1863 34.4761 14.1981C34.1491 11.0792 32.9286 12.1631 34.1777 8.68986C34.1428 8.51209 34.0831 8.39561 34.0093 8.23053C33.845 9.16405 33.3506 9.89898 33.2863 10.7662C33.2075 11.8293 33.9862 12.8299 33.9886 13.942C33.9912 15.1801 33.2139 15.955 33.0929 17.1313C32.9893 18.139 33.5045 19.1404 33.6682 20.0981C33.8684 21.2695 33.3867 22.5648 33.1491 23.6916C32.8854 24.9427 33.0374 26.6615 33.4716 27.8708L29.4756 27.8715Z" fill="#C01818"/>
                <path d="M28.7252 28.4621L37.2986 28.4507C37.4586 28.8276 37.7358 29.319 37.9317 29.6912C36.8566 29.6449 35.7443 29.6119 34.675 29.5188C34.708 29.7607 34.7212 29.9187 34.8108 30.1512C35.9415 30.7064 36.4373 31.4522 35.4906 32.5113C35.4889 32.693 35.4946 32.8747 35.5076 33.0561C35.5564 33.6659 35.8198 35.0516 36.309 35.4804C36.5377 35.6809 36.999 35.5977 37.3069 35.5709C37.6235 35.8092 38.0693 36.1138 38.3141 36.4116C39.2233 37.5179 38.6629 38.8071 37.6279 39.5992C37.1964 39.869 36.6939 40.0082 36.1827 39.9996C35.3973 39.9805 34.8507 39.6045 34.3545 39.0907C32.3345 37.169 32.2793 35.6427 32.1912 33.0582C32.1582 32.0913 31.0639 31.3044 32.626 30.1904C32.7752 30.084 32.869 29.9527 32.8752 29.7664L32.7154 29.6551C31.4715 29.4801 27.9037 30.5762 26.6587 31.0062C27.0586 30.2666 28.015 28.9103 28.7252 28.4621Z" fill="#C01818"/>
                <path d="M43.868 4.84961L48.7608 4.85017L48.7631 29.8323L63.6779 29.8302L63.6859 34.0296L43.8418 34.0319L43.868 4.84961Z" fill="#C01818"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Masuk ke SILABU</h2>
        <p class="text-sm text-gray-500">Masuk untuk melacak dan mengelola laporan Anda</p>
    </div>

    {{-- ── Form Card ── --}}
    <div class="bg-white p-7 sm:p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">

        {{-- Error message --}}
        @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-sm text-error">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5" x-data="{ showPassword: false }">
            @csrf

            {{-- Identifier --}}
            <div>
                <label for="identifier" class="block text-sm font-medium text-gray-900 mb-2">
                    NIK
                </label>
                <input
                    type="text" id="identifier" name="identifier"
                    value="{{ old('identifier') }}"
                    placeholder="Masukkan NIK"
                    required autofocus
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                           text-gray-900 placeholder-gray-400
                           focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                           outline-none transition-all duration-150"
                >
                @error('identifier')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                    Password
                </label>
                <div class="relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        id="password" name="password"
                        placeholder="Masukkan password"
                        required
                        class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400
                               hover:text-gray-600 transition-colors"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                    >
                        {{-- Eye open --}}
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- Eye closed --}}
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center gap-2 cursor-pointer">
                    <input
                        id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-primary-500
                               focus:ring-primary-500 accent-primary-500"
                    >
                    <span class="text-sm text-gray-500">Ingat saya</span>
                </label>
                <a href="#" class="text-sm font-medium text-primary-500 hover:text-primary-700 hover:underline transition-colors">
                    Lupa password?
                </a>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-700
                       text-white py-3 rounded-lg font-semibold text-sm
                       transition-all duration-150 active:scale-[.98] shadow-sm"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk
            </button>

            {{-- Divider --}}
            <div class="relative flex items-center py-1">
                <div class="grow border-t border-gray-100"></div>
                <span class="shrink-0 mx-4 text-xs text-gray-400">atau</span>
                <div class="grow border-t border-gray-100"></div>
            </div>

            {{-- Guest --}}
            <a
                href="{{ route('reports.create') }}"
                class="w-full flex items-center justify-center gap-2 bg-gray-10 border border-gray-200
                       hover:bg-gray-100 hover:border-gray-300 text-gray-700 py-3 rounded-lg
                       font-medium text-sm transition-all duration-150 active:scale-[.98]"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Masuk sebagai Tamu
            </a>
        </form>
    </div>

    {{-- Daftar --}}
    <p class="mt-8 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-primary-500 hover:text-primary-700 hover:underline transition-colors">
            Daftar sekarang
        </a>
    </p>

</div>
@endsection