<x-layouts.admin title="Tambah buku" subtitle="Tambahkan koleksi baru ke katalog.">
    <x-slot:actions><a href="{{ route('admin.books.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700">Kembali ke daftar</a></x-slot:actions>
    <x-admin.books.form :book="$book" :categories="$categories" :action="route('admin.books.store')" method="POST" submit-label="Simpan buku" />
</x-layouts.admin>
