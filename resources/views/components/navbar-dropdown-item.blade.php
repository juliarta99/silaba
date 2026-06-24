@props(['href' => '#'])

<a
    href="{{ $href }}"
    role="menuitem"
    {{ $attributes->merge([
        'class' => 'block px-3.5 py-2 text-sm font-medium text-gray-700
                    hover:text-primary-500 hover:bg-primary-50
                    rounded-lg transition-colors'
    ]) }}
>
    {{ $slot }}
</a>