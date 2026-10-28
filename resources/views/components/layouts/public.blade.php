@props(['title' => 'Katalog buku'])
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
<body id="beranda" class="bg-white font-sans text-ink antialiased">
    <a href="#konten-utama" class="skip-link">Lewati ke konten utama</a>
    <header class="border-b border-line">
        <div class="mx-auto flex min-h-20 max-w-6xl flex-wrap items-center justify-between gap-x-4 gap-y-2 px-5 py-3 sm:px-8">
            <x-brand subtitle />
            <nav aria-label="Navigasi utama" class="flex flex-wrap items-center gap-x-5 gap-y-1">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Beranda</x-nav-link>
                <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">Katalog</x-nav-link>
                @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('member.loans.index')" :active="request()->routeIs('member.loans.*')">Riwayat pinjaman</x-nav-link>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-5 text-sm font-semibold text-white hover:bg-brand-600">Masuk <span aria-hidden="true" class="ml-3">↗</span></a>
                @else
                    <form method="POST" action="{{ route('logout') }}">@csrf <x-button type="submit" variant="secondary">Keluar</x-button></form>
                @endguest
            </nav>
        </div>
    </header>
    <main id="konten-utama" tabindex="-1" class="mx-auto min-h-[60vh] max-w-6xl px-5 py-10 sm:px-8 sm:py-16">{{ $slot }}</main>
    <div class="border-t border-line">@include('partials.site-footer')</div>
</body>
</html>
