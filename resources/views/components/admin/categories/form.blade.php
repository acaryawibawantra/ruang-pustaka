@props(['category', 'action', 'method' => 'POST', 'submitLabel'])

<form method="POST" action="{{ $action }}" class="max-w-3xl space-y-6">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_240px]">
        <div class="rounded-xl border border-line bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-6 border-b border-line pb-5"><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">Informasi kategori</p><p class="mt-2 text-sm leading-6 text-muted">Gunakan nama yang singkat dan mudah ditemukan saat menambahkan buku.</p></div>
            <div class="space-y-5">
                <x-form-field name="name" label="Nama kategori" required hint="Maksimal 100 karakter. Nama tidak boleh sama dengan kategori lain."><x-input name="name" value="{{ old('name', $category->name) }}" placeholder="Contoh: Teknologi Informasi" required autofocus /></x-form-field>
                <x-form-field name="description" label="Deskripsi" hint="Opsional. Jelaskan jenis buku yang termasuk dalam kategori ini."><x-textarea name="description" placeholder="Contoh: Buku tentang pemrograman, jaringan, dan sistem informasi.">{{ old('description', $category->description) }}</x-textarea></x-form-field>
            </div>
        </div>
        <aside class="h-fit rounded-xl border border-brand-100 bg-brand-50 p-5"><p class="font-semibold text-brand-700">Tips kategori</p><ul class="mt-3 space-y-3 text-sm leading-5 text-muted"><li>• Pilih nama yang konsisten dengan koleksi yang sudah ada.</li><li>• Deskripsi membantu admin lain memilih kategori yang tepat.</li><li>• Kategori yang masih memiliki buku tidak dapat dihapus.</li></ul></aside>
    </div>
    <div class="flex flex-wrap gap-3"><x-button type="submit">{{ $submitLabel }}</x-button><a href="{{ route('admin.categories.index') }}" class="inline-flex min-h-11 items-center rounded-md border border-line px-4 text-sm font-medium text-muted hover:bg-white hover:text-ink">Batal</a></div>
</form>
