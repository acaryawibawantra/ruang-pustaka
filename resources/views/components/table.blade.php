@props(['headers' => [], 'caption' => 'Daftar data'])
<div class="overflow-hidden rounded-lg border border-line bg-white">
    <div class="overflow-x-auto" role="region" aria-label="{{ $caption }}" tabindex="0">
        <table {{ $attributes->class(['w-full min-w-[560px] text-left text-sm']) }}>
            <caption class="sr-only">{{ $caption }}</caption>
            <thead class="border-b border-line bg-surface text-xs text-muted"><tr>@foreach ($headers as $header)<th scope="col" class="px-5 py-4 font-medium">{{ $header }}</th>@endforeach</tr></thead>
            <tbody class="divide-y divide-line [&_td]:px-5 [&_td]:py-4">{{ $slot }}</tbody>
        </table>
    </div>
    @isset($pagination)<div class="border-t border-line p-4">{{ $pagination }}</div>@endisset
</div>
