<x-layouts.public :title="$book->title">
    <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700">← Kembali ke katalog</a>
    <div class="mt-6 grid items-start gap-8 md:grid-cols-[1fr_1.6fr] md:gap-14">
        <x-book-cover :book="$book" class="aspect-[4/5] p-10" />
        <div>
            <p class="text-xs font-semibold tracking-wider text-brand-700 uppercase">{{ $book->category->name }}</p>
            <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight sm:text-4xl">{{ $book->title }}</h1>
            <p class="mt-4 text-lg text-muted">{{ $book->author }}</p>
            <div class="mt-6"><x-badge :variant="$book->available_stock > 0 ? 'available' : 'unavailable'">{{ $book->available_stock > 0 ? $book->available_stock.' eksemplar tersedia' : 'Tidak tersedia' }}</x-badge></div>
            <dl class="mt-8 grid grid-cols-2 gap-x-5 gap-y-6 border-y border-line py-6 text-sm">
                <div><dt class="text-muted">Penerbit</dt><dd class="mt-2 font-medium">{{ $book->publisher ?: 'Belum dicantumkan' }}</dd></div>
                <div><dt class="text-muted">Tahun terbit</dt><dd class="mt-2 font-medium">{{ $book->publication_year ?: 'Belum dicantumkan' }}</dd></div>
                <div><dt class="text-muted">ISBN</dt><dd class="mt-2 break-all font-medium">{{ $book->isbn ?: 'Belum dicantumkan' }}</dd></div>
                <div><dt class="text-muted">Stok total</dt><dd class="mt-2 font-medium">{{ $book->total_stock }} eksemplar</dd></div>
            </dl>
            <h2 class="mt-8 text-lg font-semibold">Tentang buku</h2><p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted">{{ $book->description ?: 'Deskripsi buku belum tersedia.' }}</p>
            <div class="mt-8 rounded-lg bg-brand-50 p-5"><h2 class="text-sm font-semibold text-brand-700">Ingin meminjam buku ini?</h2><p class="mt-2 text-sm leading-7 text-muted">{{ $book->available_stock > 0 ? 'Datangi petugas untuk mencatat peminjaman. Ketersediaan buku dapat berubah sebelum transaksi dicatat.' : 'Saat ini buku belum tersedia untuk dipinjam. Periksa kembali ketersediaannya nanti.' }}</p></div>
        </div>
    </div>
</x-layouts.public>
