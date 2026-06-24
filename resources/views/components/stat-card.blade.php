{{--
    Card statistik — angka besar + label kecil di bawahnya.

    Props:
    @prop string      $nilai   Angka/teks yang ditampilkan besar, e.g. "1,234" / "98%" / "7 Hari"
    @prop string      $label   Label di bawah angka, e.g. "Laporan Terselesaikan"
    @prop string|null $ikon    Inline SVG path string (opsional)
    @prop string      $warna   'primary' | 'success' | 'warning' | 'info' | 'error'  (default: primary)

    Contoh:
    <x-stat-card nilai="1,234" label="Laporan Terselesaikan" />
    <x-stat-card nilai="98%"   label="Tingkat Kepuasan"      warna="success" />
    <x-stat-card nilai="7 Hari" label="Rata-rata Penyelesaian" warna="info" />

    Contoh dengan ikon:
    <x-stat-card nilai="247" label="Total Laporan" warna="primary">
        <x-slot name="ikon">
            <svg class="w-5 h-5" .../>
        </x-slot>
    </x-stat-card>
--}}

@props([
    'nilai' => '0',
    'label' => '',
    'warna' => 'primary',
])

@php
$warnaMap = [
    'primary' => 'text-primary-500',
    'success' => 'text-success',
    'warning' => 'text-warning',
    'info'    => 'text-info',
    'error'   => 'text-error',
];
$nilaiClass = $warnaMap[$warna] ?? $warnaMap['primary'];
@endphp

<div class="flex flex-col items-center px-4 py-2 text-center">

    @isset($ikon)
    <div class="mb-2 {{ $nilaiClass }}">{{ $ikon }}</div>
    @endisset

    <span class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tabular-nums {{ $nilaiClass }}">
        {{ $nilai }}
    </span>

    <span class="text-xs sm:text-sm text-gray-500 mt-1 leading-tight">
        {{ $label }}
    </span>

</div>
