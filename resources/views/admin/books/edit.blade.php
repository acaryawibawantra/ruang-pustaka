<x-layouts.admin title="Edit buku" subtitle="Perbarui informasi koleksi dengan tetap menjaga stok pinjaman aktif.">
    <x-slot:actions><a href="{{ route('admin.books.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700">Kembali ke daftar</a></x-slot:actions>
    <x-admin.books.form :book="$book" :categories="$categories" :action="route('admin.books.update', $book)" method="PUT" submit-label="Simpan perubahan" />
</x-layouts.admin>
