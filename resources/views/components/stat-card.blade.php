@props(['label', 'value' => null, 'description' => null, 'attention' => false])
<div {{ $attributes->class(['rounded-lg border bg-white p-5 sm:p-6', 'border-amber-200' => $attention, 'border-line' => ! $attention]) }}>
    <h2 class="text-sm font-medium text-muted">{{ $label }}</h2>
    <p @class(['mt-5 text-4xl font-semibold tracking-tight tabular-nums', 'text-amber-800' => $attention, 'text-ink' => ! $attention])>{{ $value === null ? '—' : number_format($value, 0, ',', '.') }}</p>
    @if ($description)<p class="mt-3 text-xs leading-6 text-muted">{{ $description }}</p>@endif
</div>
