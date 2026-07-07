@php
    $user     = auth()->user();
    $role     = $user?->role ?? 'guest';
    $position = ($role === 'employee') ? ($user->employee?->position ?? '') : '';
    $initials = $user ? strtoupper(substr($user->name, 0, 2)) : '';
    $picture  = ($user && $user->picture) ? asset('storage/' . $user->picture) : null;
    $userName = $user?->name ?? '';

    $isGuest        = $role === 'guest';
    $isCitizen      = $role === 'citizen';
    $isFieldOfficer = $role === 'employee' && $position === 'field_officer';
    $isSupervisor   = $role === 'employee' && $position === 'supervisor';
    $isHoD          = $role === 'employee' && $position === 'head_of_department';
    $isRegent        = $role === 'regent';
    $isDistrictChief = $role === 'district_chief';
    $isLoggedIn      = ! $isGuest;

    $profileRoute = match(true) {
        $isFieldOfficer  => route('employee.profile'),
        $isSupervisor    => route('employee.profile'),
        $isHoD           => route('employee.profile'),
        $isRegent        => route('regent.profile'),
        $isDistrictChief => route('district-chief.profile'),
        default          => null,
    };

    $districtName = $isDistrictChief
        ? ($user->districtChief?->district?->name ?? '')
        : '';

    $roleLabel = match(true) {
        $isCitizen       => 'Warga',
        $isFieldOfficer  => 'Petugas Lapangan',
        $isSupervisor    => 'Supervisor',
        $isHoD           => 'Kepala Dinas',
        $isRegent        => 'Bupati/Pejabat',
        $isDistrictChief => 'Camat' . ($districtName ? ' ' . $districtName : ''),
        default          => '',
    };
@endphp

{{-- ════ MODAL LOGOUT KONFIRMASI ════ --}}
{{-- Diletakkan di luar nav supaya tidak terpotong z-index --}}
<div
    x-data="{ showLogout: false }"
    x-on:show-logout-modal.window="showLogout = true"
    @keydown.escape.window="showLogout = false"
>
    {{-- Overlay --}}
    <div
        x-show="showLogout"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-[9998] flex items-center justify-center p-4"
        @click.self="showLogout = false"
        style="display:none;"
    >
        {{-- Modal --}}
        <div
            x-show="showLogout"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden"
        >
            {{-- Icon --}}
            <div class="flex flex-col items-center px-6 pt-7 pb-5">
                <div class="w-14 h-14 rounded-full bg-red-50 border border-red-100
                            flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-error" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Keluar dari SILABU?</h3>
                <p class="text-sm text-gray-500 text-center leading-relaxed">
                    Anda akan keluar dari sesi ini. Pastikan semua pekerjaan sudah disimpan sebelum keluar.
                </p>
            </div>

            {{-- Info user --}}
            <div class="mx-5 mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
                @if ($picture)
                    <img src="{{ $picture }}" class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0" alt="">
                @else
                    <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 text-xs font-bold
                                 flex items-center justify-center shrink-0">{{ $initials }}</span>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $userName }}</p>
                    <p class="text-xs text-gray-400">{{ $roleLabel }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 px-5 pb-6">
                <button
                    type="button"
                    @click="showLogout = false"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-700
                           text-sm font-semibold hover:bg-gray-50 transition-colors"
                >
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-error hover:bg-red-700
                                   text-white text-sm font-semibold transition-colors">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════ NAVBAR ════ --}}
<nav
    x-data="{ mobileOpen: false }"
    class="fixed top-0 inset-x-0 z-[9999] bg-white border-b border-gray-100 shadow-sm font-plus-jakarta-sans"
>
    <div class="max-w-7xl mx-auto px-5 sm:px-6 h-[68px] flex items-center justify-between gap-3">

        {{-- Logo --}}
        <a href="{{ route('home') }}" aria-label="SILABU" class="shrink-0">
            <svg class="h-6 w-auto" viewBox="0 0 159 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M105.952 4.83771C109.752 4.82264 113.845 4.63145 117.59 5.12775C124.732 6.07393 127.108 14.7249 120.885 18.7967C124.914 20.7549 126.358 23.8259 125.41 28.3719C124.997 30.3462 123.552 31.6972 121.853 32.6883C118.203 34.4076 113.101 34.0512 109.077 34.0477L100.4 34.035C100.273 24.5441 100.309 14.3502 100.415 4.85626L105.952 4.83771ZM117.006 21.2889C113.363 20.8703 109.056 21.0788 105.362 21.0936L105.347 29.9969C108.784 29.9883 117.056 30.571 119.551 28.6854C122.007 25.9129 120.579 21.6995 117.006 21.2889ZM118.515 10.3592C115.96 8.13877 108.619 8.87954 105.37 8.91095C105.338 11.6594 105.33 14.4085 105.346 17.157C108.839 17.171 115.422 17.7958 118.204 15.9549C119.701 14.4449 120.062 11.7029 118.515 10.3592Z" fill="#C01818"/>
                <path d="M83.6111 4.84888C85.1111 7.85391 86.6779 11.55 88.0652 14.6418C90.9412 21.1175 93.859 27.5753 96.8181 34.0139C95.1224 34.0769 93.2139 34.0351 91.5027 34.0354C90.469 31.6958 89.4619 29.3449 88.4802 26.9827L83.1453 26.9729C79.9593 26.9526 76.7716 26.9639 73.5857 27.0061C72.9927 28.8382 71.4897 32.1678 70.7087 34.0266C69.0031 34.0417 67.2971 34.0391 65.5916 34.0168C68.4208 27.2694 71.6288 20.3565 74.6208 13.6633L77.2605 7.76685C77.6351 6.93087 78.0504 5.94599 78.4841 5.14185C78.6461 4.84283 78.7173 4.8962 79.0525 4.83423L83.6111 4.84888ZM80.9822 9.93091C79.2949 14.0658 77.2616 19.0353 75.3982 23.0745L81.6169 23.0813L86.6941 23.0686C86.1656 21.7167 81.3961 10.2687 80.9822 9.93091Z" fill="#C01818"/>
                <path d="M145.269 4.82129C149.778 14.1745 154.007 24.4728 158.369 34.0156C156.657 34.0667 154.861 34.0324 153.14 34.0244C152.076 31.7015 151.161 29.2972 150.085 26.9668L145.032 26.9785L135.397 26.9717C134.666 29.0239 133.351 31.9267 132.496 34.0244C130.745 34.0478 128.993 34.044 127.242 34.0127L136.652 12.9482C137.749 10.4861 139.014 7.5651 140.214 5.11914C140.345 4.85097 140.52 4.87258 140.776 4.83398C142.273 4.84919 143.772 4.84494 145.269 4.82129ZM137.121 23.0723L142.92 23.0801L148.386 23.0703C147.204 20.065 145.842 17.053 144.59 14.0674C144.325 13.4385 143.061 10.4026 142.726 9.9248L137.121 23.0723Z" fill="#C01818"/>
                <path d="M10.4931 4.98316C14.4252 4.41542 18.769 5.61007 22.1328 7.68049C21.548 8.99514 21.1189 10.2856 20.4784 11.635C16.8956 9.36204 10.552 7.51274 6.83873 10.7925C6.31099 11.2585 5.71859 12.3385 5.81352 13.0554C6.59916 18.9916 17.9844 16.9509 21.3252 21.3933C22.6208 22.772 23.3034 24.4372 23.232 26.4201C22.9765 33.5095 16.2742 35.0588 10.6418 34.9199C6.29409 34.3975 3.47296 33.7957 0 30.9281C0.652254 29.6534 1.2762 28.3644 1.87155 27.0622C5.47873 29.7726 8.86394 31.1395 13.4563 30.624C15.871 30.353 18.6354 29.1171 18.2349 26.2599C17.717 22.5637 11.6594 22.3181 8.84099 21.3539C6.96648 20.7125 5.22845 20.3257 3.61409 19.0832C0.343099 16.7415 0.0987314 11.7099 2.55873 8.67415C4.61577 6.1357 7.36761 5.37753 10.4931 4.98316Z" fill="#C01818"/>
                <path d="M29.4756 27.8715C30.9877 26.1693 31.0959 25.2135 31.5011 23.0436C31.598 22.5246 32.0315 21.8173 32.1315 21.2687C32.4314 19.9117 31.6562 18.6162 31.6781 17.2489C31.6987 15.9642 32.4578 15.2103 32.6608 14.0142C32.7721 13.0007 32.0313 11.9394 32.0469 10.8584C32.0077 9.76756 32.8285 8.93413 32.9805 7.90219C33.1114 7.01321 32.9719 5.96495 32.9379 5.05421C32.8912 3.79764 33.2074 2.69804 33.5255 1.5005C33.6498 1.03258 33.7593 0.447336 33.9611 0.0243701L34.0474 0C34.4389 0.651947 34.2232 2.6564 34.2615 3.45371C34.3643 5.59128 35.8169 7.35221 35.1199 9.55737C35.0648 9.73137 34.8783 10.5153 34.8799 10.6792C34.8915 11.888 35.7288 12.5281 35.8241 13.8415C35.9173 15.1251 35.1237 16.1613 35.0862 17.2122C35.0503 18.2163 35.8842 19.2858 35.9955 20.5575C36.123 22.0142 35.1101 23.6258 35.6938 24.7419C36.1292 25.5743 37.6357 25.9915 37.3197 27.2551C37.3141 27.2772 37.3084 27.2992 37.3025 27.3212C36.9213 27.3387 36.8553 26.8268 36.2922 27.051C36.0131 27.5355 36.4604 27.6918 36.1852 27.8604C35.5594 27.9041 34.9014 27.8832 34.2724 27.8757C33.8769 26.5353 33.6052 25.2964 33.8743 23.8851C34.0864 22.7728 34.4415 21.6577 34.3702 20.5144C34.2936 19.2852 33.5968 18.2206 33.7074 16.9605C33.7942 15.9713 34.4479 15.1863 34.4761 14.1981C34.1491 11.0792 32.9286 12.1631 34.1777 8.68986C34.1428 8.51209 34.0831 8.39561 34.0093 8.23053C33.845 9.16405 33.3506 9.89898 33.2863 10.7662C33.2075 11.8293 33.9862 12.8299 33.9886 13.942C33.9912 15.1801 33.2139 15.955 33.0929 17.1313C32.9893 18.139 33.5045 19.1404 33.6682 20.0981C33.8684 21.2695 33.3867 22.5648 33.1491 23.6916C32.8854 24.9427 33.0374 26.6615 33.4716 27.8708L29.4756 27.8715Z" fill="#C01818"/>
                <path d="M28.7252 28.4621L37.2986 28.4507C37.4586 28.8276 37.7358 29.319 37.9317 29.6912C36.8566 29.6449 35.7443 29.6119 34.675 29.5188C34.708 29.7607 34.7212 29.9187 34.8108 30.1512C35.9415 30.7064 36.4373 31.4522 35.4906 32.5113C35.4889 32.693 35.4946 32.8747 35.5076 33.0561C35.5564 33.6659 35.8198 35.0516 36.309 35.4804C36.5377 35.6809 36.999 35.5977 37.3069 35.5709C37.6235 35.8092 38.0693 36.1138 38.3141 36.4116C39.2233 37.5179 38.6629 38.8071 37.6279 39.5992C37.1964 39.869 36.6939 40.0082 36.1827 39.9996C35.3973 39.9805 34.8507 39.6045 34.3545 39.0907C32.3345 37.169 32.2793 35.6427 32.1912 33.0582C32.1582 32.0913 31.0639 31.3044 32.626 30.1904C32.7752 30.084 32.869 29.9527 32.8752 29.7664L32.7154 29.6551C31.4715 29.4801 27.9037 30.5762 26.6587 31.0062C27.0586 30.2666 28.015 28.9103 28.7252 28.4621Z" fill="#C01818"/>
                <path d="M43.868 4.84961L48.7608 4.85017L48.7631 29.8323L63.6779 29.8302L63.6859 34.0296L43.8418 34.0319L43.868 4.84961Z" fill="#C01818"/>
            </svg>
        </a>

        {{-- ════ DESKTOP NAV ════ --}}
        <div class="hidden lg:flex items-center gap-0.5">

            @if ($isGuest)
                <x-navbar-link :href="route('home')"          :active="request()->routeIs('home')">Beranda</x-navbar-link>
                <x-navbar-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">Laporan Terbaru</x-navbar-link>
                <x-navbar-link :href="route('about')"         :active="request()->routeIs('about')">Tentang</x-navbar-link>
                <x-navbar-link :href="route('login')"         :active="false">Masuk</x-navbar-link>
                <x-navbar-primary-btn :href="route('reports.create')" class="ml-2">Laporkan Sekarang</x-navbar-primary-btn>
            @endif

            @if ($isCitizen)
                <x-navbar-link :href="route('home')"          :active="request()->routeIs('home')">Beranda</x-navbar-link>
                <x-navbar-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">Laporan Terbaru</x-navbar-link>
                <x-navbar-link :href="route('about')"         :active="request()->routeIs('about')">Tentang</x-navbar-link>
                <x-navbar-primary-btn :href="route('reports.create')" class="ml-2">Laporkan Sekarang</x-navbar-primary-btn>
            @endif

            @if ($isFieldOfficer)
                <x-navbar-link :href="route('employee.field-officer.dashboard')"
                               :active="request()->routeIs('employee.field-officer.dashboard')">Dashboard</x-navbar-link>
                <x-navbar-primary-btn :href="route('employee.field-officer.assignments.index')" class="ml-1">Tugas Saya</x-navbar-primary-btn>
            @endif

            @if ($isSupervisor)
                <x-navbar-link :href="route('employee.supervisor.dashboard')"  :active="request()->routeIs('employee.supervisor.dashboard')">Dashboard</x-navbar-link>
                <x-navbar-link :href="route('employee.shared.reports.index')"  :active="request()->routeIs('employee.shared.reports.*') && !request()->routeIs('employee.shared.reports.map')">Daftar Laporan</x-navbar-link>
                <x-navbar-link :href="route('employee.shared.assignments.index')" :active="request()->routeIs('employee.shared.assignments.*')">Penugasan Petugas</x-navbar-link>
                <x-navbar-link :href="route('employee.shared.departments.index')" :active="request()->routeIs('employee.shared.departments.*')">Kelola Instansi</x-navbar-link>
                <x-navbar-primary-btn :href="route('employee.shared.reviews.index')" class="ml-1">Performa</x-navbar-primary-btn>
                <x-navbar-link :href="route('employee.shared.reports.map')"    :active="request()->routeIs('employee.shared.reports.map')">Peta Sebaran</x-navbar-link>
            @endif

            @if ($isHoD)
                <x-navbar-link :href="route('employee.head.dashboard')"        :active="request()->routeIs('employee.head.dashboard')">Dashboard</x-navbar-link>
                <x-navbar-link :href="route('employee.shared.reports.index')"  :active="request()->routeIs('employee.shared.reports.*') && !request()->routeIs('employee.shared.reports.map')">Daftar Laporan</x-navbar-link>
                <x-navbar-link :href="route('employee.shared.departments.index')" :active="request()->routeIs('employee.shared.departments.*')">Kelola Instansi</x-navbar-link>
                <x-navbar-primary-btn :href="route('employee.shared.reviews.index')" class="ml-1">Performa</x-navbar-primary-btn>
                <x-navbar-link :href="route('employee.shared.reports.map')"    :active="request()->routeIs('employee.shared.reports.map')">Peta Sebaran</x-navbar-link>
            @endif

            @if ($isRegent)
                <x-navbar-link :href="route('regent.dashboard')"            :active="request()->routeIs('regent.dashboard')">Dashboard</x-navbar-link>
                <x-navbar-link :href="route('regent.reports.index')"        :active="request()->routeIs('regent.reports.index')">Daftar Laporan</x-navbar-link>
                {{-- <x-navbar-link :href="route('regent.departments.compare')"  :active="request()->routeIs('regent.departments.compare')">Komparasi Instansi</x-navbar-link> --}}
                <x-navbar-link :href="route('regent.reports.map')"          :active="request()->routeIs('regent.reports.map')">Peta Sebaran</x-navbar-link>
                {{-- <x-navbar-primary-btn :href="route('regent.reports.priority')" class="ml-1">Rekomendasi Prioritas</x-navbar-primary-btn> --}}
            @endif

            @if ($isDistrictChief)
                <x-navbar-link :href="route('district-chief.dashboard')"       :active="request()->routeIs('district-chief.dashboard')">Dashboard</x-navbar-link>
                <x-navbar-link :href="route('district-chief.reports.index')"   :active="request()->routeIs('district-chief.reports.*') && !request()->routeIs('district-chief.reports.map')">Daftar Laporan</x-navbar-link>
                <x-navbar-link :href="route('district-chief.reports.map')"     :active="request()->routeIs('district-chief.reports.map')">Peta Sebaran</x-navbar-link>
                <x-navbar-primary-btn :href="route('district-chief.reports.compare')" class="ml-1">Komparasi Instansi</x-navbar-primary-btn>
            @endif

            {{-- Avatar + Dropdown desktop --}}
            @if ($isLoggedIn)
            <div class="relative ml-3 shrink-0" x-data="{ open: false }">
                <button @@click="open = !open" @@click.outside="open = false"
                        class="flex items-center gap-1.5 p-0.5 rounded-full
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
                        :aria-expanded="open" aria-haspopup="true" aria-label="Menu akun">
                    @if ($picture)
                        <img src="{{ $picture }}" alt="{{ $userName }}"
                             class="w-9 h-9 rounded-full object-cover border-2 border-primary-500 shrink-0">
                    @else
                        <span class="w-9 h-9 rounded-full bg-primary-500 text-white text-xs font-bold
                                     flex items-center justify-center select-none shrink-0">{{ $initials }}</span>
                    @endif
                    <svg class="w-3 h-3 text-gray-400 transition-transform duration-200 shrink-0"
                         :class="{ 'rotate-180': open }" viewBox="0 0 12 12" fill="none">
                        <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 top-[calc(100%+10px)] w-56 bg-white border border-gray-100
                            rounded-xl shadow-lg py-1.5 z-[60] origin-top-right"
                     role="menu" style="display:none;">

                    <div class="px-3.5 py-2.5">
                        <div class="flex items-center gap-2.5">
                            @if ($picture)
                                <img src="{{ $picture }}" class="w-8 h-8 rounded-full object-cover border border-primary-100 shrink-0" alt="">
                            @else
                                <span class="w-8 h-8 rounded-full bg-primary-50 text-primary-500 text-[11px] font-bold
                                             flex items-center justify-center shrink-0">{{ $initials }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate leading-tight">{{ $userName }}</p>
                                <p class="text-xs text-gray-400 leading-tight">{{ $roleLabel }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 mx-1.5 mb-1"></div>

                    @if ($isCitizen)
                        <x-navbar-dropdown-item :href="route('citizen.dashboard')">Dashboard Saya</x-navbar-dropdown-item>
                        <x-navbar-dropdown-item :href="route('citizen.reports.index')">Laporan Saya</x-navbar-dropdown-item>
                        <x-navbar-dropdown-item :href="route('citizen.reward-claims.index')">Klaim Reward</x-navbar-dropdown-item>
                    @elseif ($profileRoute)
                        <x-navbar-dropdown-item :href="$profileRoute">Profil Saya</x-navbar-dropdown-item>
                    @endif

                    <div class="border-t border-gray-100 mx-1.5 my-1"></div>

                    {{-- Logout button — trigger modal --}}
                    <button type="button"
                            @@click="open = false; $dispatch('show-logout-modal')"
                            class="w-full text-left px-3.5 py-2 text-sm font-medium text-error
                                   rounded-lg hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
            @endif

        </div>{{-- end desktop --}}

        {{-- ════ MOBILE: avatar + hamburger ════ --}}
        <div class="lg:hidden flex items-center gap-2 shrink-0">

            @if ($isLoggedIn)
            <div class="relative" x-data="{ open: false }">
                <button @@click="open = !open" @@click.outside="open = false"
                        class="flex items-center gap-1 p-0.5 rounded-full
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                        :aria-expanded="open" aria-label="Menu akun">
                    @if ($picture)
                        <img src="{{ $picture }}" alt="{{ $userName }}"
                             class="w-8 h-8 rounded-full object-cover border-2 border-primary-500 shrink-0">
                    @else
                        <span class="w-8 h-8 rounded-full bg-primary-500 text-white text-[11px] font-bold
                                     flex items-center justify-center select-none shrink-0">{{ $initials }}</span>
                    @endif
                    <svg class="w-3 h-3 text-gray-400 transition-transform duration-150 shrink-0"
                         :class="{ 'rotate-180': open }" viewBox="0 0 12 12" fill="none">
                        <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-[calc(100%+8px)] w-56 bg-white border border-gray-100
                            rounded-xl shadow-lg py-1.5 z-[60] origin-top-right"
                     role="menu" style="display:none;">

                    <div class="px-3.5 py-2.5">
                        <div class="flex items-center gap-2.5">
                            @if ($picture)
                                <img src="{{ $picture }}" class="w-8 h-8 rounded-full object-cover border border-primary-100 shrink-0" alt="">
                            @else
                                <span class="w-8 h-8 rounded-full bg-primary-50 text-primary-500 text-[11px] font-bold
                                             flex items-center justify-center shrink-0">{{ $initials }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate leading-tight">{{ $userName }}</p>
                                <p class="text-xs text-gray-400 leading-tight">{{ $roleLabel }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 mx-1.5 mb-1"></div>

                    @if ($isCitizen)
                        <x-navbar-dropdown-item :href="route('citizen.dashboard')">Dashboard Saya</x-navbar-dropdown-item>
                        <x-navbar-dropdown-item :href="route('citizen.reports.index')">Laporan Saya</x-navbar-dropdown-item>
                        <x-navbar-dropdown-item :href="route('citizen.reward-claims.index')">Klaim Reward</x-navbar-dropdown-item>
                    @elseif ($profileRoute)
                        <x-navbar-dropdown-item :href="$profileRoute">Profil Saya</x-navbar-dropdown-item>
                    @endif

                    <div class="border-t border-gray-100 mx-1.5 my-1"></div>
                    <button type="button"
                            @@click="open = false; $dispatch('show-logout-modal')"
                            class="w-full text-left px-3.5 py-2 text-sm font-medium text-error
                                   rounded-lg hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
            @endif

            {{-- Hamburger --}}
            <button @@click="mobileOpen = !mobileOpen"
                    class="flex flex-col justify-center gap-[5px] w-9 h-9 p-2 rounded-lg hover:bg-primary-50 transition-colors"
                    :aria-expanded="mobileOpen" aria-label="Toggle navigasi">
                <span class="block w-full h-0.5 bg-primary-500 rounded transition-all duration-200"
                      :class="{ 'translate-y-[7px] rotate-45': mobileOpen }"></span>
                <span class="block w-full h-0.5 bg-primary-500 rounded transition-all duration-200"
                      :class="{ 'opacity-0': mobileOpen }"></span>
                <span class="block w-full h-0.5 bg-primary-500 rounded transition-all duration-200"
                      :class="{ '-translate-y-[7px] -rotate-45': mobileOpen }"></span>
            </button>
        </div>

    </div>

    {{-- Mobile Drawer --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lg:hidden fixed top-[68px] inset-x-0 z-40 bg-white border-b border-gray-100 shadow-md
                px-4 pb-5 pt-3 flex flex-col gap-0.5 max-h-[calc(100dvh-68px)] overflow-y-auto"
         @@click.outside="mobileOpen = false" style="display:none;">

        @if ($isGuest)
            <x-navbar-mobile-link :href="route('home')">Beranda</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('reports.index')">Laporan Terbaru</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('about')">Tentang</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('login')">Masuk</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('reports.create')">Laporkan Sekarang</x-navbar-mobile-btn>
        @endif
        @if ($isCitizen)
            <x-navbar-mobile-link :href="route('home')">Beranda</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('reports.index')">Laporan Terbaru</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('about')">Tentang</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('reports.create')">Laporkan Sekarang</x-navbar-mobile-btn>
        @endif
        @if ($isFieldOfficer)
            <x-navbar-mobile-link :href="route('employee.field-officer.dashboard')">Dashboard</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('employee.field-officer.assignments.index')">Tugas Saya</x-navbar-mobile-btn>
        @endif
        @if ($isSupervisor)
            <x-navbar-mobile-link :href="route('employee.supervisor.dashboard')">Dashboard</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('employee.shared.reports.index')">Daftar Laporan</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('employee.shared.assignments.index')">Penugasan Petugas</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('employee.shared.departments.index')">Kelola Instansi</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('employee.shared.reviews.index')">Performa</x-navbar-mobile-btn>
            <x-navbar-mobile-link :href="route('employee.shared.reports.map')">Peta Sebaran</x-navbar-mobile-link>
        @endif
        @if ($isHoD)
            <x-navbar-mobile-link :href="route('employee.head.dashboard')">Dashboard</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('employee.shared.reports.index')">Daftar Laporan</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('employee.shared.departments.index')">Kelola Instansi</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('employee.shared.reviews.index')">Performa</x-navbar-mobile-btn>
            <x-navbar-mobile-link :href="route('employee.shared.reports.map')">Peta Sebaran</x-navbar-mobile-link>
        @endif
        @if ($isRegent)
            <x-navbar-mobile-link :href="route('regent.dashboard')">Dashboard</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('regent.reports.index')">Daftar Laporan</x-navbar-mobile-link>
            {{-- <x-navbar-mobile-link :href="route('regent.departments.compare')">Komparasi Instansi</x-navbar-mobile-link> --}}
            <x-navbar-mobile-link :href="route('regent.reports.map')">Peta Sebaran</x-navbar-mobile-link>
            {{-- <x-navbar-mobile-btn  :href="route('regent.reports.priority')">Rekomendasi Prioritas</x-navbar-mobile-btn> --}}
        @endif

        @if ($isDistrictChief)
            <x-navbar-mobile-link :href="route('district-chief.dashboard')">Dashboard</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('district-chief.reports.index')">Daftar Laporan</x-navbar-mobile-link>
            <x-navbar-mobile-link :href="route('district-chief.reports.map')">Peta Sebaran</x-navbar-mobile-link>
            <x-navbar-mobile-btn  :href="route('district-chief.reports.compare')">Komparasi Instansi</x-navbar-mobile-btn>
        @endif

    </div>
</nav>