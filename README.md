# Ruang Pustaka

Ruang Pustaka adalah prototipe website perpustakaan untuk lingkungan ITS dalam tugas Web Programming. Ini adalah proyek mahasiswa, bukan layanan resmi ITS. Kebutuhan produk tersedia dalam [PRD](docs/PRD_Ruang_Pustaka.pdf).

## Stack dan pembagian tugas

Laravel 13 (PHP 8.3 atau lebih baru), Blade, Tailwind CSS 4, Vite, MySQL, Git, dan GitHub.

- **Acarya:** UI, katalog, dan detail buku.
- **Anang:** database, migration, seeder, model, dan relasi.
- **Atma:** autentikasi, hak akses, peminjaman, dan pengembalian.
- **Bersama:** integrasi, pengujian, deployment, dan laporan. Kontribusi dalam laporan harus sesuai pekerjaan aktual.

Migration dan seeder domain perpustakaan akan ditambahkan Anang setelah tim menyepakati [kebutuhan data awal](docs/koordinasi-data.md). Kerangka Laravel menyertakan migration dan seeder bawaan; jangan jalankan migration sebelum disepakati. [Backlog awal](docs/backlog.md) adalah bahan koordinasi tim.

## Model Database

https://app.eraser.io/workspace/elXs74HUojFxSWkMXf9P?origin=share

## Instalasi lokal

Pastikan PHP 8.3+, Composer, Node.js 20.19+ atau 22.12+, npm, dan MySQL tersedia. Jalankan dari folder proyek:

```sh
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Di Windows, salin `.env.example` menjadi `.env` menggunakan perintah atau file manager yang sesuai, lalu isi `DB_PASSWORD` dan `DB_ROOT_PASSWORD` di `.env` dengan password lokal pilihan Anda. Untuk menjalankan MySQL 8.4 melalui Docker Compose, jalankan:

```sh
docker compose up -d mysql
```

File `.env` diabaikan Git dan menyimpan kredensial lokal; jangan bagikan. Jangan gunakan kredensial pengembangan di lingkungan bersama atau produksi. Data MySQL disimpan di named volume `mysql_data`. Hentikan container dengan `docker compose down` (data tetap tersimpan); hapus volume secara terpisah hanya jika memang ingin menghapus database lokal.

Session dan cache awal menggunakan file; queue menggunakan `sync`.

Jalankan aplikasi dan server aset dalam dua terminal:

```sh
php artisan serve
```

```sh
npm run dev
```

Buka alamat yang ditampilkan Artisan, biasanya http://localhost:8000. Setelah MySQL siap, buat skema dan data katalog contoh dengan:

```sh
php artisan migrate --seed
```

Halaman `/login` dan `/register` menyediakan login, registrasi anggota, dan logout. Registrasi hanya memberikan peran anggota; pembuatan akun admin harus dilakukan melalui proses admin yang aman.

## Alur kerja Git

Mulai dari `main` terbaru, lalu buat branch untuk setiap pekerjaan:

```sh
git switch main
git pull origin main
git switch -c fitur/nama-pekerjaan
```

Commit perubahan yang relevan dan push branch:

```sh
git add .
git commit -m "Jelaskan perubahan"
git push -u origin fitur/nama-pekerjaan
```

Buka pull request ke `main`, jelaskan perubahan dan pengujiannya, lalu minta anggota lain meninjau. Setelah digabungkan, kembali ke `main` dan jalankan `git pull origin main`. Hindari memasukkan `.env`, `vendor`, `node_modules`, atau hasil build ke Git. Gunakan status Backlog → Ready → In Progress → In Review → Done untuk melacak pekerjaan.

## Kerangka admin untuk integrasi tim

Layout admin tersedia sebagai `<x-layouts.admin title="Buku" subtitle="Kelola koleksi perpustakaan.">…</x-layouts.admin>`. Konten halaman diletakkan di slot utama; tombol halaman dapat diletakkan pada `<x-slot:actions>`. Halaman `/admin` menampilkan dashboard dari database; hanya dapat dibuka oleh admin aktif.

Untuk melihat layout dan contoh komponen tanpa database, buka `/design-system/admin` saat `APP_ENV=local`. Pratinjau ini tidak menyimpan data dan mengembalikan 404 di produksi. `/design-system` juga memuat contoh komponen form/tabel.

Route admin memakai `auth` dan `can:access-admin`. Gate `access-admin` memeriksa `role=admin` dan `is_active=true` setiap request. Anang perlu meninjau/mengintegrasikan aturan ini pada pekerjaan autentikasi; jangan menambah sistem role kedua. Route pengelolaan baru harus dimasukkan ke group admin yang sama, bukan hanya menampilkan menu berdasarkan role.

Nama route yang digunakan sidebar:

| Nama route | Pemilik implementasi |
| --- | --- |
| `admin.dashboard` | Acarya; dashboard `/admin` tersedia |
| `admin.books.index` | Atma |
| `admin.categories.index` | Atma |
| `admin.members.index` | Atma |
| `admin.loans.index` | Atma |

Sidebar menandai menu yang route-nya belum tersedia dan mengaktifkannya setelah route ditambahkan. Jika nama route diubah tim, perbarui `components/admin/navigation.blade.php` bersama-sama.

Contoh form untuk dipakai Atma (sesuaikan action, method dan field dengan controller):

```blade
<x-layouts.admin title="Tambah buku">
    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
        @csrf
        <x-form-field name="title" label="Judul buku" :required="true" hint="Tuliskan sesuai sampul.">
            <x-input :value="old('title')" required />
        </x-form-field>
        <x-form-field name="description" label="Deskripsi" class="mt-5">
            <x-textarea>{{ old('description') }}</x-textarea>
        </x-form-field>
        <x-button type="submit" class="mt-5">Simpan buku</x-button>
    </form>
</x-layouts.admin>
```

`form-field` meneruskan nama ke input/select/textarea, menghubungkan label, bantuan dan error validasi. Isi `old()`/nilai edit serta validasi server tetap ditangani pembuat halaman. `required` harus dipasang juga pada kontrol jika diperlukan.

Komponen lain: `<x-select>`, `<x-alert variant="success|error|info">`, `<x-empty-state title="…" description="…">` dan `<x-table :headers="['Judul', 'Kategori', 'Aksi']" caption="Daftar buku">`. Isi slot tabel berupa baris `<tr><td>…</td></tr>`. Untuk pagination gunakan `<x-slot:pagination>{{ $books->links() }}</x-slot:pagination>` di dalam `x-table`, dengan paginator dari controller. Pastikan setiap ID form unik dalam satu halaman.

Layout menampilkan `session('success')`, `session('error')` dan ringkasan error validasi secara otomatis. Controller dapat memakai `->with('success', 'Buku berhasil disimpan.')` setelah aksi berhasil. Form harus tetap memakai CSRF, validasi server dan otorisasi yang sesuai.


### Definisi ringkasan dashboard

- **Judul aktif:** jumlah record buku dengan `is_active=true`.
- **Total eksemplar:** jumlah `total_stock` dari buku aktif; keterangan di bawahnya menjumlahkan `available_stock` dari buku aktif.
- **Pinjaman aktif:** status `borrowed` dengan `returned_at` kosong. Termasuk pinjaman pada buku atau anggota nonaktif agar kewajiban pengembalian tetap terlihat.
- **Terlambat:** pinjaman aktif yang `due_at`-nya sebelum hari ini. Jatuh tempo hari ini belum terlambat; pinjaman returned tidak dihitung.

Dashboard menghitung aggregate di database dan menampilkan maksimal lima pinjaman terlambat yang paling lama jatuh temponya, dengan eager loading anggota/buku. Angka diperbarui setiap halaman dimuat ulang. Perhitungan hari menggunakan `today()` dan zona waktu aplikasi yang sama dengan model Loan; konfigurasi aplikasi saat ini UTC. Sepakati perubahan zona waktu bersama tim sebelum mengubahnya, agar dashboard dan transaksi tetap konsisten.

Pratinjau lokal `/design-system/admin` menggunakan tanda “—” untuk metrik dan tidak membaca/mengarang data operasional. Mencoba data sebenarnya memerlukan konfigurasi MySQL serta migration Anang pada database pengembangan yang disepakati. Pengujian otomatis menggunakan SQLite in-memory; belum menggantikan verifikasi integrasi MySQL.
