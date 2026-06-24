{{--
    Card kontak — ikon bulat + label + nilai, linkable.

    Props:
    @prop string $label    Nama saluran, e.g. "Telepon"
    @prop string $nilai    Nilai kontak, e.g. "(0361) 123-4567"
    @prop string $href     URL tujuan (tel:, mailto:, https://wa.me/...)
    @prop bool   $external Buka di tab baru (default false)

    Slot:
    $ikon → SVG ikon (wajib diisi via x-slot)

    Contoh:
    <x-contact-card label="Telepon" nilai="(0361) 123-4567" href="tel:+623611234567">
        <x-slot name="ikon">
            <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-colors duration-150" .../>
        </x-slot>
    </x-contact-card>
--}}

@props([
    'label'    => '',
    'nilai'    => '',
    'href'     => '#',
    'external' => false,
])

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener noreferrer" @endif
    class="group flex flex-col items-center gap-3 p-6 rounded-2xl
           bg-white border border-gray-50
           hover:border-primary-200 hover:bg-primary-50
           transition-all duration-150"
>
    <div class="w-12 h-12 rounded-full bg-primary-50 border border-primary-100
                flex items-center justify-center
                group-hover:bg-primary-500 transition-colors duration-150"
         aria-hidden="true">
        @isset($ikon) {{ $ikon }} @endisset
    </div>

    <div class="text-center">
        <p class="text-sm font-semibold text-gray-900">{{ $label }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ $nilai }}</p>
    </div>
</a>
