@props(['name', 'label', 'hint' => null, 'required' => false])
<div {{ $attributes }}>
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium">{{ $label }} @if ($required)<span class="text-red-700" aria-hidden="true">*</span><span class="sr-only">(wajib)</span>@endif</label>
    {{ $slot }}
    @if ($hint)<p id="{{ $name }}-hint" class="mt-2 text-xs leading-5 text-muted">{{ $hint }}</p>@endif
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
