@props(['active' => false])
<a @if ($active) aria-current="page" @endif {{ $attributes->class(['inline-flex min-h-11 items-center text-sm transition-colors', 'font-semibold text-brand-700' => $active, 'font-medium text-muted hover:text-brand-700' => ! $active]) }}>{{ $slot }}</a>
