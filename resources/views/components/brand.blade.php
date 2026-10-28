@props(['subtitle' => false, 'size' => 'md'])
@php
    $logoSize = $size === 'sm' ? 'h-11 w-11' : 'h-12 w-12';
@endphp
<a href="{{ route('home') }}" {{ $attributes->class(['flex shrink-0 items-center gap-3 font-semibold tracking-tight text-brand-700']) }}>
    <img
        src="{{ asset('images/perpustakaan-its-mark.png') }}"
        alt="Logo Perpustakaan ITS"
        class="{{ $logoSize }} shrink-0 object-contain"
        width="48"
        height="48"
    >
    <span class="text-sm sm:text-base">Ruang Pustaka
        @if ($subtitle)
            <span class="mt-0.5 block text-[10px] font-normal tracking-wide text-muted sm:text-xs">Perpustakaan kampus</span>
        @endif
    </span>
</a>
