<div x-data="{ sidebarOpen: false, showLogoutModal: false }">
    {{-- ════════════════════════════════════════════════════════════
         MOBILE HEADER (Hanya Tampil di Layar Kecil)
    ════════════════════════════════════════════════════════════ --}}
    <div class="md:hidden flex items-center justify-between bg-primary-500 text-white p-4 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center text-primary-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0... (potong logo Anda) ... 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="font-bold text-lg tracking-wide">SILABA Admin</span>
        </div>
        <button @click="sidebarOpen = true" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         OVERLAY MOBILE
    ════════════════════════════════════════════════════════════ --}}
    <div x-show="sidebarOpen" 
         x-transition.opacity
         class="fixed inset-0 bg-gray-900/60 z-40 md:hidden backdrop-blur-sm" 
         @click="sidebarOpen = false" 
         style="display: none;"></div>

    {{-- ════════════════════════════════════════════════════════════
         SIDEBAR UTAMA
    ════════════════════════════════════════════════════════════ --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-[280px] bg-primary-500 text-white flex flex-col transition-transform duration-300 ease-in-out transform md:translate-x-0 shadow-2xl md:shadow-none"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        
        {{-- ── Brand Header ── --}}
        <div class="px-6 py-7 border-b border-primary-700/60 flex items-center gap-3.5">
            <div class="w-10 h-10 bg-secondary-500 rounded-xl flex items-center justify-center text-primary-500 shadow-inner shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0... (potong logo Anda) ... 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg leading-tight tracking-wide">Admin Sistem</h2>
                <p class="text-xs text-primary-100 font-medium tracking-wider">SILABA</p>
            </div>
        </div>

        {{-- ── Menu Items ── --}}
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            {{-- Manajemen Pengguna --}}
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manajemen Pengguna
            </a>

            {{-- Manajemen Kecamatan --}}
            <a href="{{ route('admin.districts.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.districts.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.districts.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Manajemen Kecamatan
            </a>

            {{-- Manajemen Kategori --}}
            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c-1.105 0-2 .895-2 2s.895 2 2 2h12c1.105 0 2-.895 2-2s-.895-2-2-2H9z" />
                </svg>
                Manajemen Kategori
            </a>

            {{-- Manajemen OPD --}}
            <a href="{{ route('admin.departments.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.departments.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.departments.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Manajemen OPD
            </a>

            {{-- Pemetaan Kategori OPD --}}
            <a href="{{ route('admin.mappings.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.mappings.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.mappings.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Pemetaan Kategori OPD
            </a>

            {{-- Manajemen Reward --}}
            <a href="{{ route('admin.rewards.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.rewards.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.rewards.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                Manajemen Reward
            </a>

            {{-- Notifikasi --}}
            <a href="{{ route('admin.notifications.index') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-semibold transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-secondary-500 text-gray-900 font-bold shadow-sm' : 'text-primary-50 hover:bg-primary-700 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.notifications.*') ? '' : 'opacity-80' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifikasi
            </a>

        </nav>

        {{-- ── Profil Footer ── --}}
        <div class="border-t border-primary-700/60 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center text-gray-900 font-bold text-sm shrink-0 shadow-sm">
                    {{-- Diambil dari Auth Laravel --}}
                    {{ substr(Auth::user()->name ?? 'A S', 0, 2) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm text-white truncate">{{ Auth::user()->name ?? 'Admin Super' }}</p>
                    <p class="text-xs text-primary-100 truncate">{{ Auth::user()->email ?? 'admin@badungkab.go.id' }}</p>
                </div>
            </div>
            
            {{-- Tombol Trigger Modal Logout Alpine.js --}}
            <button type="button" @click="showLogoutModal = true" class="w-full bg-white text-gray-900 hover:bg-gray-100 font-bold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </div>
    </aside>

    {{-- ════════════════════════════════════════════════════════════
         MODAL KONFIRMASI LOGOUT (ALPINE.JS)
    ════════════════════════════════════════════════════════════ --}}
    <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center px-4">
        {{-- Background Overlay dengan transisi --}}
        <div x-show="showLogoutModal" 
             x-transition.opacity 
             @click="showLogoutModal = false" 
             class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        
        {{-- Modal Box dengan transisi --}}
        <div x-show="showLogoutModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="bg-white rounded-2xl shadow-xl w-full max-w-sm z-10 overflow-hidden transform relative">
            
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-primary-50 text-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Keluar</h3>
                <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin keluar dari sistem SILABA?</p>
                
                <div class="flex gap-3">
                    {{-- Tombol Batal menutup modal --}}
                    <button type="button" @click="showLogoutModal = false" class="flex-1 bg-gray-100 text-gray-700 hover:bg-gray-200 font-bold py-2.5 rounded-xl transition-colors">
                        Batal
                    </button>
                    
                    {{-- Form Logout Sesungguhnya --}}
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-primary-500 text-white hover:bg-primary-700 font-bold py-2.5 rounded-xl transition-colors shadow-sm">
                            Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>