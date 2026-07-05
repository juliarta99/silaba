@props([
    'active' => 'dashboard',
    'badge'  => 0,
])

@php
$items = [
    [
        'key'   => 'dashboard',
        'label' => 'Dashboard',
        'route' => 'employee.field-officer.dashboard',
        'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'badge' => false,
    ],
    [
        'key'   => 'assignments',
        'label' => 'Tugas',
        'route' => 'employee.field-officer.assignments.index',
        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'badge' => true,
    ],
    [
        'key'   => 'profile',
        'label' => 'Profil',
        'route' => 'employee.profile',
        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'badge' => false,
    ],
];
@endphp

{{-- Bottom Nav — mobile only --}}
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-100 shadow-lg sm:hidden"
     style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="grid grid-cols-3">
        @foreach ($items as $item)
        @php $isActive = $active === $item['key']; @endphp

        <a href="{{ route($item['route']) }}"
           class="relative flex flex-col items-center justify-center gap-1 py-3 transition-colors
                  border-t-2 {{ $isActive ? 'text-primary-500 border-primary-500' : 'text-gray-400 hover:text-gray-600 border-transparent' }}">

            {{-- Badge jumlah tugas --}}
            @if ($item['badge'] && $badge > 0)
            <span class="absolute top-2 left-1/2 translate-x-1
                         w-4 h-4 rounded-full bg-primary-500 text-white
                         text-[9px] font-bold flex items-center justify-center leading-none">
                {{ $badge > 9 ? '9+' : $badge }}
            </span>
            @endif

            <svg class="w-5 h-5 {{ $isActive ? 'scale-110' : '' }} transition-transform"
                 fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="{{ $item['icon'] }}" stroke="currentColor" stroke-width="1.75"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <span class="text-xs {{ $isActive ? 'font-semibold' : 'font-medium' }}">
                {{ $item['label'] }}
            </span>
        </a>
        @endforeach
    </div>
</div>

{{-- Spacer agar konten tidak tertutup bottom nav --}}
<div class="h-16 sm:hidden" aria-hidden="true"></div>