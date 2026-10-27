# Ruang Pustaka

Ruang Pustaka adalah prototipe website perpustakaan untuk lingkungan ITS dalam tugas Web Programming. Ini adalah proyek mahasiswa, bukan layanan resmi ITS. Kebutuhan produk tersedia dalam [PRD](docs/PRD_Ruang_Pustaka.pdf).

## Stack dan pembagian tugas

Laravel 13 (PHP 8.3 atau lebih baru), Blade, Tailwind CSS 4, Vite, MySQL, Git, dan GitHub.

- **Acarya:** UI, katalog, dan detail buku.
- **Anang:** database, migration, seeder, model, dan relasi.
- **Atma:** autentikasi, hak akses, peminjaman, dan pengembalian.
- **Bersama:** integrasi, pengujian, deployment, dan laporan. Kontribusi dalam laporan harus sesuai pekerjaan aktual.

Migration dan seeder domain perpustakaan akan ditambahkan Anang setelah tim menyepakati [kebutuhan data awal](docs/koordinasi-data.md). Kerangka Laravel menyertakan migration dan seeder bawaan; jangan jalankan migration sebelum disepakati. [Backlog awal](docs/backlog.md) adalah bahan koordinasi tim.

## Instalasi lokal

Pastikan PHP 8.3+, Composer, Node.js 20.19+ atau 22.12+, npm, dan MySQL tersedia. Jalankan dari folder proyek:

```sh
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Di Windows, salin `.env.example` menjadi `.env` menggunakan perintah atau file manager yang sesuai. Buat database MySQL lokal, misalnya `ruang_pustaka`, lalu isi `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env` sesuai konfigurasi masing-masing. Jangan bagikan `.env` atau kredensial. Session dan cache awal menggunakan file; queue menggunakan `sync` agar fondasi dapat dijalankan sebelum skema disepakati.

Jalankan aplikasi dan server aset dalam dua terminal:

```sh
php artisan serve
```

```sh
npm run dev
```

Buka alamat yang ditampilkan Artisan, biasanya http://localhost:8000. Tidak perlu menjalankan migration untuk melihat halaman awal. Setelah skema disepakati dan migration tersedia, ikuti instruksi Anang untuk menjalankannya dan seed data yang disetujui.

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
