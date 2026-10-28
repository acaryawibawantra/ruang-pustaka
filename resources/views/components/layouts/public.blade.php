@props(['title' => 'Katalog buku'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Ruang Pustaka</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="beranda" class="bg-white font-sans text-ink antialiased">
    <a href="#konten-utama" class="skip-link">Lewati ke konten utama</a>
    <header class="border-b border-line">
        <div class="mx-auto flex min-h-20 max-w-6xl flex-wrap items-center justify-between gap-x-4 gap-y-2 px-5 py-3 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm font-semibold tracking-tight text-brand-700 sm:text-base">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-700 text-white" aria-hidden="true"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13"/></svg></span>
                <span>Ruang Pustaka<span class="mt-0.5 block text-[10px] font-normal tracking-wide text-muted sm:text-xs">Perpustakaan kampus</span></span>
            </a>
            <nav aria-label="Navigasi utama" class="flex flex-wrap items-center gap-x-5 gap-y-1 text-sm font-medium">
                <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-brand-700">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center text-brand-700">Katalog</a>
                @guest
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-4 font-semibold text-white hover:bg-brand-600">Masuk</a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex min-h-11 items-center text-brand-700">Dashboard</a>
                    <a href="{{ route('member.loans.index') }}" class="inline-flex min-h-11 items-center text-brand-700">Riwayat pinjaman</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf <x-button type="submit" variant="secondary">Keluar</x-button></form>
                @endguest
            </nav>
        </div>
    </header>
    <main id="konten-utama" tabindex="-1" class="mx-auto min-h-[60vh] max-w-6xl px-5 py-10 sm:px-8 sm:py-16">{{ $slot }}</main>
    <div class="border-t border-line">@include('partials.site-footer')</div>
</body>
</html>
