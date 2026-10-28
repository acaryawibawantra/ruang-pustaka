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
<body class="min-h-svh bg-white font-sans text-ink antialiased">
    <a href="#konten-utama" class="skip-link">Lewati ke konten utama</a>
    <div class="grid min-h-svh lg:grid-cols-[1.05fr_1fr]">
        <aside class="relative hidden flex-col justify-between overflow-hidden bg-brand-700 px-12 py-12 text-white lg:sticky lg:top-0 lg:flex lg:h-svh">
            <span aria-hidden="true" class="pointer-events-none absolute -right-20 top-16 h-64 w-64 rounded-full border border-white/10"></span>
            <span aria-hidden="true" class="pointer-events-none absolute -right-36 top-44 h-96 w-96 rounded-full border border-white/5"></span>

            <div class="relative flex items-center gap-3">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white p-2">
                    <img
                        src="{{ asset('images/perpustakaan-its-mark.png') }}"
                        alt="Logo Perpustakaan ITS"
                        class="h-full w-full object-contain"
                        width="48"
                        height="48"
                    >
                </span>
                <span class="text-base font-semibold tracking-tight">
                    Ruang Pustaka
                    <span class="mt-0.5 block text-xs font-normal text-white/70">Perpustakaan ITS</span>
                </span>
            </div>

            <div class="relative max-w-md">
                <h2 class="mt-5 text-3xl font-semibold leading-tight tracking-tight">Telusuri koleksi, ajukan peminjaman, dan pantau tenggat dalam satu tempat.</h2>
                <p class="mt-4 text-sm leading-6 text-white/75">Akun yang sama dipakai untuk melihat katalog, mengelola pinjaman aktif, dan riwayat bacaan Anda.</p>
            </div>

            <p class="relative text-xs text-white/60">Perpustakaan Institut Teknologi Sepuluh Nopember</p>
        </aside>

        <main id="konten-utama" tabindex="-1" class="flex min-h-svh flex-col bg-white px-5 py-10 sm:px-10 lg:px-16 lg:py-12">
            <div class="flex items-center justify-between lg:hidden">
                <x-brand subtitle />
                <x-nav-link :href="route('home')">← Beranda</x-nav-link>
            </div>

            <div class="flex flex-1 items-center justify-center py-10 lg:py-0">
                <div class="w-full max-w-sm">
                    <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ $heading }}</h1>
                    @if ($description)<p class="mt-3 text-sm leading-6 text-muted">{{ $description }}</p>@endif
                    <div class="mt-8 space-y-5">{{ $slot }}</div>
                </div>
            </div>

            <div class="hidden justify-center lg:flex">
                <x-nav-link :href="route('home')">← Kembali ke beranda</x-nav-link>
            </div>
        </main>
    </div>
</body>
</html>
