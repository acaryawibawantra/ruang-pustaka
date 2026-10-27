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
