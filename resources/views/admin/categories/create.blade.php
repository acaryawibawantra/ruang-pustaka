<x-layouts.admin title="Tambah kategori" subtitle="Buat kategori untuk mengelompokkan buku.">
    <x-slot:actions><a href="{{ route('admin.categories.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700">Kembali ke daftar</a></x-slot:actions>
    <x-admin.categories.form :category="$category" :action="route('admin.categories.store')" method="POST" submit-label="Simpan kategori" />
</x-layouts.admin>
