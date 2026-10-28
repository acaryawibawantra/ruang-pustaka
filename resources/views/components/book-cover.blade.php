@props(['book'])
@php
    $coverPath = $book->cover_image;
    $hasCover = $coverPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($coverPath);
    $media = 'aspect-[2/3] w-full max-w-[220px] motion-safe:transition-transform motion-safe:duration-200 motion-safe:group-hover:-translate-y-1';
@endphp
<div {{ $attributes->class(['flex items-center justify-center rounded-lg bg-surface p-6']) }}>
    @if ($hasCover)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($coverPath) }}" alt="Sampul {{ $book->title }}" loading="lazy" width="240" height="360" class="{{ $media }} rounded object-cover shadow-sm">
    @else
        <div class="{{ $media }} flex flex-col justify-between rounded-sm border-l-4 border-brand-700/20 bg-brand-50 p-4 text-brand-700 shadow-sm">
            <span class="text-[9px] font-medium tracking-wide uppercase">Ruang Pustaka</span>
            <span class="text-sm font-semibold leading-snug">{{ $book->title }}</span>
            <span class="text-[9px]">Sampul belum tersedia</span>
        </div>
    @endif
</div>
