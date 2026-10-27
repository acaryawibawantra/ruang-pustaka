<footer class="bg-white" aria-label="Informasi Ruang Pustaka">
    <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:grid-cols-2 sm:px-8 lg:grid-cols-[2fr_1fr_1fr]">
        <div class="max-w-sm">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center gap-3 text-lg font-semibold tracking-tight text-brand-700">
                <svg aria-hidden="true" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13"/></svg>
                Ruang Pustaka
            </a>
            <p class="mt-3 text-sm leading-7 text-muted">Ruang untuk menemukan bacaan, menumbuhkan rasa ingin tahu, dan melanjutkan perjalanan belajar.</p>
        </div>
        <nav aria-label="Jelajahi halaman">
            <h2 class="mb-3 text-sm font-semibold">Jelajahi</h2>
            <ul class="text-sm text-muted">
                <li><a href="#beranda" class="inline-flex min-h-11 items-center hover:text-brand-700">Beranda</a></li>
                <li><a href="#katalog" class="inline-flex min-h-11 items-center hover:text-brand-700">Katalog buku</a></li>
                <li><a href="#cara-peminjaman" class="inline-flex min-h-11 items-center hover:text-brand-700">Cara peminjaman</a></li>
            </ul>
        </nav>
        <div>
            <h2 class="mb-3 text-sm font-semibold">Tentang proyek</h2>
            <p class="text-sm leading-7 text-muted">Prototipe mahasiswa untuk lingkungan ITS. Bukan layanan resmi Institut Teknologi Sepuluh Nopember.</p>
            <p class="mt-4 text-xs leading-6 text-muted">Dikembangkan oleh Acarya, Anang, dan Atma.</p>
        </div>
    </div>
    <div class="border-t border-line">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-5 py-5 text-xs text-muted sm:flex-row sm:items-center sm:justify-between sm:px-8">
            <span>© {{ date('Y') }} Ruang Pustaka · Proyek Web Programming</span>
            <a href="#beranda" class="inline-flex min-h-11 items-center gap-3 self-start font-medium text-brand-700 sm:min-h-6">Kembali ke atas <span aria-hidden="true">↑</span></a>
        </div>
    </div>
</footer>
