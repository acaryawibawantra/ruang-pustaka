@props(['variant' => 'available'])

@php
    $variants = [
        'available' => 'bg-emerald-50 text-emerald-800',
        'unavailable' => 'bg-slate-100 text-slate-700',
    ];
@endphp

<span {{ $attributes->class(['inline-flex rounded-md px-3 py-1.5 text-xs font-semibold', $variants[$variant] ?? $variants['available']]) }}>{{ $slot }}</span>
