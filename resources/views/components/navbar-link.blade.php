@props(['href' => '#', 'active' => false])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors whitespace-nowrap '
            . ($active
                ? 'text-primary-500 bg-primary-50'
                : 'text-gray-700 hover:text-primary-500 hover:bg-primary-50')
    ]) }}
>
    {{ $slot }}
</a>