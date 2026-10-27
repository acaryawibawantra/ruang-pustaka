@props(['variant' => 'info'])
@php
    $variants = [
        'info' => 'border-brand-100 bg-brand-50 text-brand-700',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'error' => 'border-red-200 bg-red-50 text-red-800',
    ];
@endphp
<div {{ $attributes->class(['rounded-lg border px-4 py-3 text-sm leading-6', $variants[$variant] ?? $variants['info']])->merge(['role' => $variant === 'error' ? 'alert' : 'status']) }}>{{ $slot }}</div>
