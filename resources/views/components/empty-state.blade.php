@props(['title' => 'Belum ada data', 'description' => null])
<div {{ $attributes->class(['rounded-lg border border-dashed border-line bg-white px-6 py-14 text-center']) }}>
    <span aria-hidden="true" class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-brand-50 text-xl text-brand-700">▤</span>
    <h2 class="mt-5 text-lg font-semibold">{{ $title }}</h2>
    @if ($description)<p class="mx-auto mt-2 max-w-md text-sm leading-7 text-muted">{{ $description }}</p>@endif
    @if (! $slot->isEmpty())<div class="mt-6">{{ $slot }}</div>@endif
</div>
