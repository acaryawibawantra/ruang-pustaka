<x-layouts.admin title="Buku" subtitle="Kelola koleksi, ketersediaan, dan status buku.">
    <x-slot:actions><a href="{{ route('admin.books.create') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-4 text-sm font-semibold text-white hover:bg-brand-600">Tambah buku</a></x-slot:actions>
    <form method="GET" class="grid gap-4 rounded-lg border border-line bg-white p-5 sm:grid-cols-[1fr_200px_160px_auto] sm:items-end">
        <x-form-field name="q" label="Cari buku"><x-input name="q" type="search" value="{{ $search }}" placeholder="Judul atau penulis" /></x-form-field>
        <x-form-field name="category" label="Kategori"><x-select name="category"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>@endforeach</x-select></x-form-field>
        <x-form-field name="status" label="Status"><x-select name="status"><option value="">Semua status</option><option value="active" @selected($selectedStatus === 'active')>Aktif</option><option value="inactive" @selected($selectedStatus === 'inactive')>Nonaktif</option></x-select></x-form-field>
        <div class="flex gap-3"><x-button type="submit">Filter</x-button><a href="{{ route('admin.books.index') }}" class="inline-flex min-h-11 items-center px-2 text-sm font-medium text-muted hover:text-brand-700">Reset</a></div>
    </form>
    <div class="mt-6 overflow-hidden rounded-lg border border-line bg-white"><x-table :headers="['Buku', 'Kategori', 'Stok', 'Status', 'Tindakan']" caption="Daftar buku">
        @forelse ($books as $book)
            <tr><td><span class="block font-medium">{{ $book->title }}</span><span class="mt-1 block text-xs text-muted">{{ $book->author }}</span></td><td>{{ $book->category->name }}</td><td class="whitespace-nowrap">{{ $book->available_stock }} / {{ $book->total_stock }}</td><td>{{ $book->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="whitespace-nowrap"><a class="font-semibold text-brand-700 hover:underline" href="{{ route('admin.books.edit', $book) }}">Edit</a>@if ($book->is_active)<form method="POST" action="{{ route('admin.books.deactivate', $book) }}" class="ml-4 inline">@csrf @method('PATCH')<button class="font-semibold text-red-700 hover:underline" type="submit">Nonaktifkan</button></form>@endif</td></tr>
        @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-muted">Belum ada buku yang sesuai filter.</td></tr>
        @endforelse
    </x-table></div><div class="mt-6">{{ $books->onEachSide(1)->links() }}</div>
</x-layouts.admin>
