@props(['variant' => null])

@php
    $slotText = trim(strip_tags((string) $slot));
    $resolvedVariant = $variant ?? (str_contains($slotText, 'Tidak tersedia') ? 'unavailable' : 'available');

    $variants = [
        'available' => 'bg-emerald-50 text-emerald-800',
        'unavailable' => 'bg-gray-100 text-gray-700',
        'gray' => 'bg-gray-100 text-gray-700',
        'overdue' => 'bg-rose-50 text-rose-800',
        'approaching' => 'bg-amber-50 text-amber-800',
        'active' => 'bg-brand-50 text-brand-700',
        'returned' => 'bg-emerald-50 text-emerald-800',
    ];
@endphp

<span {{ $attributes->class(['inline-flex rounded-md px-3 py-1.5 text-xs font-semibold', $variants[$resolvedVariant] ?? $variants['available']]) }}>{{ $slot }}</span>
