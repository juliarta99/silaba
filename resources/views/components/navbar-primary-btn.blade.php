@props(['href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                    bg-primary-500 text-white shadow-sm whitespace-nowrap
                    hover:bg-primary-700 active:scale-[.98]
                    transition-all duration-150'
    ]) }}
>
    {{ $slot }}
</a>