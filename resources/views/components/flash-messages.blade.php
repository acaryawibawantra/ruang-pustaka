<div {{ $attributes->class(['space-y-3 empty:hidden']) }}>
    @if (session('success'))<x-alert variant="success" class="mb-6">{{ session('success') }}</x-alert>@endif
    @if (session('error'))<x-alert variant="error" class="mb-6">{{ session('error') }}</x-alert>@endif
    @if ($errors->any())
        <x-alert variant="error" class="mb-6"><p class="font-medium">Periksa kembali isian berikut:</p><ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></x-alert>
    @endif
</div>
