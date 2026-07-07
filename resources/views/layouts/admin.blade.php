<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - @yield('title', config('app.long_name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @livewireStyles

    @yield('styles')
</head>
<body class="bg-gray-10 text-gray-900 antialiased font-plus-jakarta-sans" x-data="{ sidebarOpen: false }">

    {{-- Memanggil Komponen Sidebar (Header Mobile, Overlay, dan Aside) --}}
    <x-admin.sidebar />

    {{-- ════════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ════════════════════════════════════════════════════════════ --}}
    <main class="md:ml-[280px] min-h-screen flex flex-col transition-all duration-300">
        <div class="p-6 sm:p-8 flex-1">
            
            {{-- Header Halaman --}}
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                    @yield('page_title', 'Dashboard Admin Sistem')
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    @yield('page_subtitle', 'Kelola seluruh konfigurasi sistem, pengguna, dan data master')
                </p>
            </div>

            {{-- Tempat Konten Dynamic Laravel --}}
            @yield('content')

        </div>
    </main>

    @yield('scripts')

    @livewireScripts
</body>
</html>