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
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-lg font-semibold tracking-tight text-brand-700">Ruang Pustaka</a>
            <nav aria-label="Navigasi utama" class="flex items-center gap-5 text-sm font-medium">
                <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center text-brand-700">Katalog</a>
                @guest
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-4 font-semibold text-white hover:bg-brand-600">Masuk</a>
                @else
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
