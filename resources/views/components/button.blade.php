@props(['variant' => 'primary'])

@php
    $variants = [
        'primary' => 'bg-brand-700 text-white hover:bg-brand-600',
        'secondary' => 'border border-brand-700 text-brand-700 hover:bg-brand-50',
    ];
@endphp

<button {{ $attributes->class(['min-h-11 rounded-md px-5 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-700 disabled:cursor-not-allowed disabled:border-transparent disabled:bg-slate-200 disabled:text-slate-500', $variants[$variant] ?? $variants['primary']])->merge(['type' => 'button']) }}>
    {{ $slot }}
</button>
