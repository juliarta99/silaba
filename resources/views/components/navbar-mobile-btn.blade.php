@props(['href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'block text-center px-4 py-2.5 my-1 text-sm font-semibold
                    bg-primary-500 text-white rounded-lg
                    hover:bg-primary-700 active:scale-[.98]
                    transition-all duration-150'
    ]) }}
>
    {{ $slot }}
</a>