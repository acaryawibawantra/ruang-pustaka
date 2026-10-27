# Backlog awal

Usulan penanggung jawab adalah nama anggota, bukan penetapan akun GitHub. Semua pekerjaan ditinjau bersama tim dan mengikuti PRD.

## Kesepakatan data dan rancangan database
Area: database · Prioritas: tinggi · Usulan: Anang

Diskusikan kebutuhan data katalog dan transaksi bersama tim, lalu rancang model konseptual dan fisik MySQL.

Selesai ketika: entitas, relasi, foreign key, aturan penghapusan, stok, dan keputusan dalam `koordinasi-data.md` disepakati serta didokumentasikan; migration, model, dan seeder yang diperlukan dapat dijalankan dan ditinjau.

## UI dasar dan navigasi
Area: UI · Prioritas: tinggi · Usulan: Acarya

Buat layout Blade responsif dengan Tailwind dan navigasi sesuai peran.

Selesai ketika: layout dapat digunakan di ponsel dan laptop, navigasi jelas, dan keadaan kosong serta pesan sukses/kesalahan tersedia.

## Katalog, pencarian, dan detail buku
Area: katalog · Prioritas: tinggi · Usulan: Acarya

Tampilkan koleksi dan detail dengan pencarian judul atau penulis dan filter kategori setelah kontrak data disepakati.

Selesai ketika: katalog menampilkan sampul, judul, penulis dan ketersediaan; pencarian, filter, detail dan kondisi tanpa hasil berfungsi.

## Pengelolaan buku dan kategori
Area: administrasi · Prioritas: tinggi · Usulan: Anang

Buat pengelolaan buku dan kategori untuk admin berdasarkan rancangan data tim.

Selesai ketika: admin dapat menambah, melihat, mengubah dan menghapus data sesuai aturan riwayat dan validasi.

## Pengelolaan anggota
Area: administrasi · Prioritas: tinggi · Usulan: Atma

Buat halaman admin untuk melihat, memperbarui dan menonaktifkan anggota.

Selesai ketika: akses dibatasi untuk admin, validasi berjalan, dan riwayat tetap terjaga saat anggota dinonaktifkan.

## Autentikasi dan hak akses
Area: autentikasi · Prioritas: tinggi · Usulan: Atma

Buat registrasi anggota, login, logout, dan pembatasan peran serta mekanisme aman untuk menyiapkan admin.

Selesai ketika: alur autentikasi bekerja, hak akses anggota dan admin diuji, dan anggota hanya dapat melihat riwayatnya sendiri.

## Peminjaman, pengembalian, stok, dan riwayat
Area: transaksi · Prioritas: tinggi · Usulan: Atma

Admin mencatat pinjaman dan pengembalian; anggota melihat riwayatnya sendiri.

Selesai ketika: stok diperiksa dan diperbarui secara konsisten, pengembalian ganda dicegah, keterlambatan terlihat, dan riwayat tetap terjaga. Uji kondisi transaksi bersamaan.

## Dashboard admin
Area: administrasi · Prioritas: sedang · Usulan: Acarya

Sajikan ringkasan koleksi, pinjaman aktif, dan keterlambatan untuk admin.

Selesai ketika: angka sesuai data, akses dibatasi, dan tampilan responsif serta memiliki keadaan kosong.

## Pengujian, deployment, dan laporan
Area: kualitas · Prioritas: tinggi · Usulan: Acarya, Anang, dan Atma

Uji integrasi dan aturan bisnis, pilih hosting yang kompatibel, deploy, dan susun laporan sesuai instruksi tugas.

Selesai ketika: alur utama serta batas akses dan stok diuji, aplikasi dapat diakses, source tersedia, laporan mencerminkan fitur dan kontribusi aktual, dan deklarasi integritas ditandatangani.
