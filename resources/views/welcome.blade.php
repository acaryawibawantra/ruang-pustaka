<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ruang Pustaka, prototipe perpustakaan untuk lingkungan ITS.">
    <title>Ruang Pustaka</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="beranda" class="bg-white font-sans text-ink antialiased">
    <a href="#konten-utama" class="skip-link">Lewati ke konten utama</a>
    <header class="relative z-20 border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-20 max-w-6xl items-center justify-between gap-4 px-5 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm font-semibold tracking-tight text-brand-700 sm:text-base">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-700 text-white" aria-hidden="true"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13"/></svg></span>
                <span>Ruang Pustaka<span class="mt-0.5 block text-[10px] font-normal tracking-wide text-muted sm:text-xs">Perpustakaan kampus</span></span>
            </a>
            <nav aria-label="Navigasi utama" class="flex items-center gap-5 text-sm font-medium sm:gap-8">
                <a href="#katalog" class="hidden text-muted hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-700 sm:block">Katalog</a>
                <a href="#cara-peminjaman" class="hidden text-muted hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-700 md:block">Cara peminjaman</a>
                @can('access-admin')
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex min-h-11 items-center text-brand-700">Admin</a>
                @endcan
                @auth
                    <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="min-h-11 rounded-md border border-brand-700 px-4 font-semibold text-brand-700 hover:bg-brand-50">Keluar</button></form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-5 font-semibold text-white hover:bg-brand-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-700">Masuk <span aria-hidden="true" class="ml-3">↗</span></a>
                @endauth
            </nav>
        </div>
        <nav aria-label="Navigasi bagian halaman" class="flex items-center gap-6 border-t border-line px-5 text-xs font-medium text-muted sm:hidden">
            <a href="#katalog" class="inline-flex min-h-12 items-center hover:text-brand-700">Katalog buku</a>
            <a href="#cara-peminjaman" class="inline-flex min-h-12 items-center hover:text-brand-700">Cara peminjaman</a>
        </nav>
    </header>

    <main id="konten-utama" tabindex="-1">
        <section class="hero-scene" aria-labelledby="hero-title" data-parallax>
            <div class="hero-copy" data-parallax-copy>
                <p class="hero-eyebrow">BACA. TEMUKAN. KEMBANGKAN.</p>
                <h1 id="hero-title">Ruang untuk<br><span>terus belajar.</span></h1>
                <p class="hero-description">Temukan bacaan untuk setiap langkah belajarmu.</p>
            </div>
            <div class="hero-foreground" data-parallax-foreground aria-hidden="true" style="background-image: url('{{ asset('images/perpustakaan-its-removebg.webp') }}');"></div>
            <div class="hero-bottom" aria-hidden="true"></div>
            <a href="#katalog" class="hero-scroll focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"><span>Jelajahi katalog</span><span class="hero-scroll-line" aria-hidden="true"></span>↓</a>
            <span class="hero-caption">GEDUNG PERPUSTAKAAN ITS <span aria-hidden="true">/</span> SURABAYA</span>
        </section>
        <div class="hero-transition" aria-hidden="true"></div>
        @include('partials.catalog')
        @include('partials.borrowing-guide')
    </main>
    @include('partials.site-footer')
</body>
</html>
