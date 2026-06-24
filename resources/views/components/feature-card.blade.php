{{--
    Card fitur kecil — ikon + judul + deskripsi.
    Dipakai di section "Tentang SILABA" dan halaman about.

    Props:
    @prop string $judul  Judul fitur
    @prop string $desc   Deskripsi singkat

    Slot:
    $ikon  → SVG ikon (wajib diisi via x-slot)

    Contoh:
    <x-feature-card judul="Mudah Melaporkan" desc="Proses pelaporan yang sederhana dan cepat.">
        <x-slot name="ikon">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24">
                <path d="..." stroke="currentColor" .../>
            </svg>
        </x-slot>
    </x-feature-card>
--}}

@props([
    'judul' => '',
    'desc'  => '',
])

<div class="flex flex-col gap-3 p-5 rounded-2xl bg-white border border-gray-50 hover:bg-primary-50 group transition-all">

    @isset($ikon)
    <div class="w-10 h-10 rounded-xl bg-primary-50 group-hover:bg-primary-100 flex items-center justify-center shrink-0"
         aria-hidden="true">
        {{ $ikon }}
    </div>
    @endisset

    <div>
        <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $judul }}</h3>
        <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
    </div>

</div>
