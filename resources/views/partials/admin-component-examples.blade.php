<div class="grid gap-6 xl:grid-cols-2">
    <div class="space-y-5 rounded-lg border border-line bg-white p-5 sm:p-6">
        <div><h3 class="font-semibold">Form buku</h3><p class="mt-2 text-xs leading-6 text-muted">Contoh tampilan isian. Gunakan tombol Simpan setelah form terhubung ke aksi pengelolaan.</p></div>
        <x-form-field name="example-title" label="Judul buku" :required="true" hint="Tuliskan judul sesuai sampul buku."><x-input placeholder="Contoh: Pengantar Basis Data" required /></x-form-field>
        <x-form-field name="example-category" label="Kategori"><x-select><option value="">Pilih kategori</option><option>Teknologi · contoh</option><option>Literasi · contoh</option></x-select></x-form-field>
        <x-form-field name="example-description" label="Deskripsi"><x-textarea placeholder="Ringkasan singkat isi buku" /></x-form-field>
        <div class="flex flex-wrap gap-3"><x-button disabled>Simpan (contoh)</x-button><x-button variant="secondary" disabled>Batal (contoh)</x-button></div>
    </div>
    <div class="space-y-5">
        <x-alert variant="success">Contoh pesan: perubahan berhasil disimpan.</x-alert>
        <x-alert variant="error">Contoh pesan: file sampul tidak valid. Pilih JPEG, PNG, atau WebP.</x-alert>
        <div><h3 class="mb-3 font-semibold">Tabel data contoh</h3><x-table :headers="['Judul buku', 'Kategori', 'Status']" caption="Contoh daftar buku"><tr><td class="font-medium">Pengantar Basis Data</td><td>Teknologi</td><td><x-badge>Tersedia</x-badge></td></tr><tr><td class="font-medium">Literasi Digital</td><td>Literasi</td><td><x-badge variant="unavailable">Tidak tersedia</x-badge></td></tr></x-table></div>
        <p class="text-xs leading-6 text-muted">Tabel mendukung slot pagination agar bisa memakai hasil paginate() dari controller.</p>
    </div>
</div>
