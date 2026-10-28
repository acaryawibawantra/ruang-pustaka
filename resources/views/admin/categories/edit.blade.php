<x-layouts.admin title="Edit kategori" subtitle="Perbarui nama atau penjelasan kategori.">
    <x-slot:actions><a href="{{ route('admin.categories.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700">Kembali ke daftar</a></x-slot:actions>
    <x-admin.categories.form :category="$category" :action="route('admin.categories.update', $category)" method="PUT" submit-label="Simpan perubahan" />
</x-layouts.admin>
