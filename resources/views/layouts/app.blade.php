<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.long_name', 'Sistem Layanan Pelaporan Terpadu')) | {{ config('app.name', 'SILABA') }}</title>

        <meta name="description" content="@yield('meta_description', 'Platform resmi layanan pelaporan dan pengaduan masyarakat terpadu untuk Kabupaten Badung. Sampaikan laporan Anda dengan mudah dan pantau progresnya.')">
        <meta name="keywords" content="@yield('meta_keywords', 'silaba, pelaporan masyarakat, pengaduan badung, lapor badung, pemkab badung, layanan publik')">
        <meta name="author" content="Pemerintah Kabupaten Badung">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
        
        <meta property="og:site_name" content="{{ config('app.name', 'SILABA Badung') }}">
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', config('app.long_name', 'Sistem Layanan Pelaporan Terpadu')) | {{ config('app.name', 'SILABA') }}">
        <meta property="og:description" content="@yield('meta_description', 'Platform resmi layanan pelaporan dan pengaduan masyarakat terpadu untuk Kabupaten Badung.')">
        
        <meta property="og:image" content="@yield('og_image', asset('assets/images/og-image.jpg'))">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="800">
        <meta property="og:image:alt" content="Banner {{ config('app.name', 'SILABA') }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="@yield('title', config('app.long_name', 'Sistem Layanan Pelaporan Terpadu')) | {{ config('app.name', 'SILABA') }}">
        <meta name="twitter:description" content="@yield('meta_description', 'Platform resmi layanan pelaporan dan pengaduan masyarakat terpadu untuk Kabupaten Badung.')">
        <meta name="twitter:image" content="@yield('og_image', asset('assets/images/og-image.jpg'))">

        <meta name="theme-color" content="#C01818"> <meta name="msapplication-TileColor" content="#C01818">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @livewireStyles
        @yield('styles')
    </head>
    <body class="font-plus-jakarta-sans">
        <x-navbar />
        
        <main>
            @yield('content')
        </main>

        <x-footer />

        @yield('scripts')

        @livewireScripts
    </body>
</html>