@props(['title' => 'Masuk', 'heading' => null, 'description' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Ruang Pustaka</title>
    <link rel="icon" href="{{ asset('images/perpustakaan-its-mark.png') }}" type="image/png">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-svh flex-col bg-surface font-sans text-ink antialiased">
    <a href="#konten-utama" class="skip-link">Lewati ke konten utama</a>
    <header class="border-b border-line bg-white">
        <div class="mx-auto flex min-h-20 max-w-6xl items-center justify-between gap-4 px-5 sm:px-8">
            <x-brand subtitle />
            <x-nav-link :href="route('home')">← Beranda</x-nav-link>
        </div>
    </header>
    <main id="konten-utama" tabindex="-1" class="flex flex-1 items-start justify-center px-5 py-12 sm:py-16">
        <div class="w-full max-w-md rounded-xl border border-line bg-white p-6 sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight">{{ $heading }}</h1>
            @if ($description)<p class="mt-2 text-sm leading-6 text-muted">{{ $description }}</p>@endif
            <div class="mt-6 space-y-5">{{ $slot }}</div>
        </div>
    </main>
</body>
</html>
