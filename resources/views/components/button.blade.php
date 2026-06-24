{{--
    <x-button>           → primary (merah solid)
    <x-button variant="secondary"> → outline putih (untuk di atas bg gelap)
    <x-button variant="outline">   → outline abu (untuk di atas bg terang)
    <x-button variant="ghost">     → tanpa border, teks saja

    Props:
    @prop string  $variant  = 'primary' | 'secondary' | 'outline' | 'ghost'
    @prop string  $size     = 'sm' | 'md' | 'lg'
    @prop string  $href     = null   → render <a>, null → render <button>
    @prop string  $type     = 'button' | 'submit' | 'reset'
    @prop bool    $disabled = false
    @prop bool    $external = false  → tambah target="_blank" rel="noopener"

    Contoh:
    <x-button href="{{ route('reports.create') }}">Laporkan Sekarang</x-button>
    <x-button variant="outline" href="{{ route('reports.index') }}">Lacak Laporan</x-button>
    <x-button variant="secondary" size="sm">Lacak Laporan</x-button>
    <x-button type="submit" size="lg">Kirim Laporan</x-button>
    <x-button variant="ghost" href="{{ route('reports.index') }}">
        Lihat Semua
        <x-slot name="icon">
            <svg class="w-4 h-4" .../>
        </x-slot>
    </x-button>
--}}

@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'href'     => null,
    'type'     => 'button',
    'disabled' => false,
    'external' => false,
])

@php
$sizes = [
    'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
    'md' => 'px-5 py-2.5 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2.5',
];

$variants = [
    'primary'   => 'bg-primary-500 text-white shadow-sm hover:bg-primary-700 focus-visible:ring-primary-500',
    'secondary' => 'bg-white/15 text-white border border-white/40 hover:bg-white/25 focus-visible:ring-white backdrop-blur-sm',
    'outline'   => 'bg-white text-gray-700 border border-gray-200 shadow-sm hover:border-primary-500 hover:text-primary-500 focus-visible:ring-primary-500',
    'ghost'     => 'bg-transparent text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus-visible:ring-gray-400',
    'danger'    => 'bg-error text-white shadow-sm hover:opacity-90 focus-visible:ring-error',
];

$base = 'inline-flex items-center justify-center font-semibold rounded-lg
         transition-all duration-150 active:scale-[.97]
         focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2
         disabled:opacity-50 disabled:pointer-events-none select-none';

$classes = implode(' ', [
    $base,
    $sizes[$size] ?? $sizes['md'],
    $variants[$variant] ?? $variants['primary'],
]);
@endphp

@if ($href)
    <a
        href="{{ $disabled ? '#' : $href }}"
        {{ $external ? 'target=_blank rel=noopener noreferrer' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        {{ $slot }}
        @isset($icon) {{ $icon }} @endisset
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
        @isset($icon) {{ $icon }} @endisset
    </button>
@endif
