@props(['href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'block px-3 py-2.5 text-sm font-medium text-gray-700
                    rounded-lg hover:text-primary-500 hover:bg-primary-50
                    transition-colors'
    ]) }}
>
    {{ $slot }}
</a>