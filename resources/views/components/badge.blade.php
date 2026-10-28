@props(['variant' => null])

@php
    $slotText = trim(strip_tags((string) $slot));
    $resolvedVariant = $variant ?? (str_contains($slotText, 'Tidak tersedia') ? 'unavailable' : 'available');

    $variants = [
        'available' => 'bg-emerald-50 text-emerald-800',
        'unavailable' => 'bg-gray-100 text-gray-700',
        'gray' => 'bg-gray-100 text-gray-700',
    ];
@endphp

<span {{ $attributes->class(['inline-flex rounded-md px-3 py-1.5 text-xs font-semibold', $variants[$resolvedVariant] ?? $variants['available']]) }}>{{ $slot }}</span>
