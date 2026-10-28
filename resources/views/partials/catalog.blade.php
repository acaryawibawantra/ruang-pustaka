@php
    $previewBooks = [
        ['title' => 'Pengantar Basis Data', 'category' => 'Teknologi', 'number' => '01', 'color' => 'bg-sky-100 text-sky-900'],
        ['title' => 'Dasar Pemrograman Web', 'category' => 'Teknologi', 'number' => '02', 'color' => 'bg-slate-200 text-slate-800'],
        ['title' => 'Literasi Digital untuk Mahasiswa', 'category' => 'Literasi', 'number' => '03', 'color' => 'bg-stone-200 text-stone-800'],
    ];
@endphp

<section id="katalog" data-catalog class="mx-auto max-w-6xl px-5 pb-20 pt-6 sm:px-8 sm:pb-28" aria-labelledby="catalog-title">
    <div class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="text-xs font-semibold tracking-[0.16em] text-brand-700 uppercase">Jelajahi koleksi</p>
            <h2 id="catalog-title" class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Temukan bacaanmu.</h2>
            <p class="mt-3 text-sm leading-6 text-muted">Mulai dari topik yang kamu sukai, temukan pengetahuan baru.</p>
        </div>
        <div class="flex flex-col gap-2"><a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center gap-3 text-sm font-semibold text-brand-700">Buka katalog lengkap <span aria-hidden="true">→</span></a><span class="text-xs text-muted">Pratinjau tampilan · Data contoh</span></div>
    </div>

    <div class="mt-8 grid gap-4 border-y border-line py-5 sm:grid-cols-[1fr_220px]">
        <div>
            <label for="catalog-search" class="mb-2 block text-xs font-medium text-muted">Judul atau penulis</label>
            <x-input id="catalog-search" data-catalog-search type="search" placeholder="Cari buku yang ingin kamu baca…" autocomplete="off" />
        </div>
        <div>
            <label for="catalog-category" class="mb-2 block text-xs font-medium text-muted">Kategori</label>
            <select id="catalog-category" data-catalog-category class="min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-100">
                <option value="">Semua kategori</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Literasi">Literasi</option>
            </select>
        </div>
    </div>

    <p data-catalog-count role="status" aria-live="polite" class="my-6 text-xs text-muted">Menampilkan 3 buku contoh</p>
    <div class="grid grid-cols-1 gap-x-7 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($previewBooks as $book)
            <article data-catalog-book data-title="{{ $book['title'] }}" data-author="Tim Ruang Pustaka" data-category="{{ $book['category'] }}" class="min-w-0">
                <div class="flex aspect-[4/3] items-center justify-center rounded-lg bg-surface p-6 sm:p-8">
                    <div class="relative flex aspect-[2/3] h-full max-h-64 flex-col justify-between border-l-[5px] border-black/10 p-4 shadow-sm sm:p-5 {{ $book['color'] }}" aria-label="Sampul pengganti untuk {{ $book['title'] }}">
                        <span class="text-[9px] font-medium tracking-[0.12em] uppercase">Ruang Pustaka / {{ $book['number'] }}</span>
                        <span class="text-base font-semibold leading-tight tracking-tight sm:text-lg">{{ $book['title'] }}</span>
                        <span class="border-t border-current/20 pt-3 text-[9px] uppercase tracking-wider">Sampul belum tersedia</span>
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-between gap-3">
                    <span class="text-xs font-medium text-brand-700">{{ $book['category'] }}</span>
                    <x-badge class="shrink-0">Tersedia · contoh</x-badge>
                </div>
                <h3 class="mt-3 text-lg font-semibold leading-snug tracking-tight">{{ $book['title'] }}</h3>
                <p class="mt-2 text-sm text-muted">Tim Ruang Pustaka</p>
            </article>
        @endforeach
    </div>
    <div data-catalog-empty hidden class="rounded-lg border border-dashed border-line px-5 py-14 text-center">
        <h3 class="text-lg font-semibold">Buku belum ditemukan</h3>
        <p class="mt-2 text-sm text-muted">Coba judul lain atau pilih kategori yang berbeda.</p>
        <x-button data-catalog-reset variant="secondary" class="mt-5">Hapus pencarian</x-button>
    </div>
    <noscript><p class="mt-6 text-sm text-muted">Aktifkan JavaScript untuk mencoba pencarian pada katalog contoh.</p></noscript>
</section>
