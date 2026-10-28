<section id="katalog" data-catalog class="mx-auto max-w-6xl px-5 pb-20 pt-6 sm:px-8 sm:pb-28" aria-labelledby="catalog-title">
    <div class="flex flex-wrap items-end justify-between gap-5">
        <div><p class="text-xs font-semibold tracking-[0.16em] text-brand-700 uppercase">Jelajahi koleksi</p><h2 id="catalog-title" class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Temukan bacaanmu.</h2><p class="mt-3 text-sm leading-6 text-muted">Mulai dari topik yang kamu sukai, temukan pengetahuan baru.</p></div>
        <div class="flex flex-col gap-2"><a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center gap-3 text-sm font-semibold text-brand-700">Buka katalog lengkap <span aria-hidden="true">→</span></a><span class="text-xs text-muted">Koleksi aktif Ruang Pustaka</span></div>
    </div>
    <div class="mt-8 grid gap-4 border-y border-line py-5 sm:grid-cols-[1fr_220px]">
        <div><label for="catalog-search" class="mb-2 block text-xs font-medium text-muted">Judul atau penulis</label><x-input id="catalog-search" data-catalog-search type="search" placeholder="Cari buku yang ingin kamu baca…" autocomplete="off" /></div>
        <div><label for="catalog-category" class="mb-2 block text-xs font-medium text-muted">Kategori</label><select id="catalog-category" data-catalog-category class="min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-100"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
    </div>
    <p data-catalog-count role="status" aria-live="polite" class="my-6 text-xs text-muted">Menampilkan {{ $books->count() }} buku</p>
    @if ($books->isNotEmpty())
        <div class="grid grid-cols-1 gap-x-7 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($books as $book)
                <article data-catalog-book data-title="{{ $book->title }}" data-author="{{ $book->author }}" data-category="{{ $book->category_id }}" class="min-w-0"><a href="{{ route('catalog.show', $book) }}" class="group block rounded-lg" aria-label="Lihat detail {{ $book->title }}"><x-book-cover :book="$book" /><h3 class="mt-5 text-lg font-semibold leading-snug tracking-tight group-hover:text-brand-700">{{ $book->title }}</h3></a><p class="mt-2 text-sm text-muted">{{ $book->author }}</p><div class="mt-4 flex flex-wrap items-center justify-between gap-3"><span class="text-xs font-medium text-brand-700">{{ $book->category->name }}</span><x-badge :variant="$book->available_stock > 0 ? 'available' : 'unavailable'" class="shrink-0">{{ $book->available_stock > 0 ? 'Tersedia · '.$book->available_stock : 'Tidak tersedia' }}</x-badge></div></article>
            @endforeach
        </div>
        <div data-catalog-empty hidden class="rounded-lg border border-dashed border-line px-5 py-14 text-center"><h3 class="text-lg font-semibold">Buku belum ditemukan</h3><p class="mt-2 text-sm text-muted">Coba judul lain atau pilih kategori yang berbeda.</p><x-button data-catalog-reset variant="secondary" class="mt-5">Hapus pencarian</x-button></div>
    @else
        <div class="rounded-lg border border-dashed border-line px-5 py-14 text-center"><h3 class="text-lg font-semibold">Koleksi belum tersedia</h3><p class="mt-2 text-sm text-muted">Buku akan ditampilkan setelah petugas menambahkan koleksi.</p></div>
    @endif
    <noscript><p class="mt-6 text-sm text-muted">Gunakan katalog lengkap untuk mencari dan memfilter seluruh koleksi.</p></noscript>
</section>
