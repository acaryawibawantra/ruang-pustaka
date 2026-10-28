<x-layouts.public>
    <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-sm text-muted hover:text-brand-700">← Beranda</a>
    <p class="mt-4 text-xs font-semibold tracking-[0.16em] text-brand-700 uppercase">Jelajahi koleksi</p>
    <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Temukan bacaanmu.</h1>
    <p class="mt-3 text-sm leading-7 text-muted">Cari buku, periksa ketersediaannya, dan lihat informasi lengkapnya.</p>

    <form method="GET" action="{{ route('catalog.index') }}" class="mt-8 grid items-end gap-4 border-y border-line py-5 sm:grid-cols-[1fr_200px_auto]">
        <div><label for="q" class="mb-2 block text-sm font-medium">Judul atau penulis</label><x-input id="q" name="q" type="search" :value="$search" maxlength="100" placeholder="Cari buku…" /></div>
        <div><label for="category" class="mb-2 block text-sm font-medium">Kategori</label><select id="category" name="category" class="min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 text-sm"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <x-button type="submit">Cari buku</x-button>
        @if ($errors->any())<p role="alert" class="text-sm text-red-700 sm:col-span-3">{{ $errors->first() }}</p>@endif
    </form>
    <div class="my-6 flex flex-wrap items-center justify-between gap-3 text-sm text-muted"><p>{{ $books->total() }} buku ditemukan</p>@if ($search !== '' || $selectedCategory)<a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center font-medium text-brand-700 hover:underline">Hapus filter</a>@endif</div>
    @if ($books->isNotEmpty())
        <div class="grid gap-x-7 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($books as $book)
                <article>
                    <a href="{{ route('catalog.show', $book) }}" class="group block rounded-lg" aria-label="Lihat detail {{ $book->title }}"><x-book-cover :book="$book" /><h2 class="mt-5 text-lg font-semibold leading-snug group-hover:text-brand-700">{{ $book->title }}</h2></a>
                    <p class="mt-2 text-sm text-muted">{{ $book->author }}</p>
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3"><span class="text-xs text-brand-700">{{ $book->category->name }}</span><x-badge :variant="$book->available_stock > 0 ? 'available' : 'unavailable'">{{ $book->available_stock > 0 ? 'Tersedia · '.$book->available_stock : 'Tidak tersedia' }}</x-badge></div>
                </article>
            @endforeach
        </div>
        <div class="mt-12">{{ $books->onEachSide(1)->links() }}</div>
    @else
        <div class="rounded-lg border border-dashed border-line px-5 py-16 text-center"><h2 class="text-lg font-semibold">{{ $search !== '' || $selectedCategory ? 'Buku belum ditemukan' : 'Koleksi belum tersedia' }}</h2><p class="mt-3 text-sm leading-7 text-muted">{{ $search !== '' || $selectedCategory ? 'Coba kata kunci lain atau pilih kategori yang berbeda.' : 'Buku akan ditampilkan setelah petugas menambahkan koleksi.' }}</p></div>
    @endif
</x-layouts.public>
