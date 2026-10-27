@props(['book'])
@php
    $coverPath = $book->cover_image;
    $hasCover = $coverPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($coverPath);
@endphp
<div {{ $attributes->class(['flex aspect-[4/3] items-center justify-center overflow-hidden rounded-lg bg-surface p-6']) }}>
    @if ($hasCover)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($coverPath) }}" alt="Sampul {{ $book->title }}" loading="lazy" width="240" height="360" class="h-full w-full object-contain">
    @else
        <div class="flex aspect-[2/3] h-full flex-col justify-between border-l-4 border-brand-700/15 bg-brand-100 p-4 text-brand-700">
            <span class="text-[9px] font-medium tracking-wide uppercase">Ruang Pustaka</span>
            <span class="text-sm font-semibold leading-snug">{{ $book->title }}</span>
            <span class="text-[9px]">Sampul belum tersedia</span>
        </div>
    @endif
</div>
