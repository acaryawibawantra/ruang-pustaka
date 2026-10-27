# Rancangan data awal Ruang Pustaka

Draf implementasi berdasarkan PRD dan keputusan awal pemilik task. Tinjau kembali bersama tim sesuai issue database sebelum dipakai di lingkungan bersama/produksi.

## Entitas dari PRD

| Entitas | Kebutuhan awal |
| --- | --- |
| Pengguna | Nama, NRP/NIM, email, password, peran dan status aktif. Registrasi anggota memerlukan nama, NRP/NIM, email dan password; autentikasi Laravel menggunakan email dan password. |
| Kategori | Nama dan deskripsi; satu kategori memiliki banyak buku. |
| Buku | Judul, penulis, penerbit, tahun, ISBN, deskripsi, sampul, stok total, stok tersedia, status aktif dan kategori. Satu buku dapat muncul dalam banyak transaksi sepanjang waktu. |
| Peminjaman | Anggota, satu buku/eksemplar, tanggal pinjam, jatuh tempo, tanggal kembali dan status. Satu anggota memiliki banyak transaksi. |

## Aturan skema

- `nrp_nim` wajib unik dan maksimal 32 karakter; email wajib unik; ISBN opsional dan unik.
- Pengguna baru berperan `member` dan aktif secara default. Akun admin dibuat melalui proses admin yang aman, bukan seeder.
- Login memakai email/password; registrasi hanya menghasilkan anggota. Akun nonaktif tidak dapat login.
- Buku menyimpan `total_stock` dan `available_stock`; stok tersedia dibatasi agar tidak negatif atau melampaui stok total.
- Pinjam/kembali wajib mengubah stok dan membuat/memperbarui transaksi dalam satu transaksi database, dengan penguncian baris buku untuk menghindari overselling.
- Peminjaman satu baris mewakili satu eksemplar. Status tersimpan hanya `borrowed` atau `returned`; terlambat adalah pinjaman `borrowed` yang tanggal jatuh temponya sebelum hari ini.
- Anggota dan buku dinonaktifkan dengan `is_active`, bukan dihapus. Foreign key menolak penghapusan anggota/buku yang punya riwayat dan menolak penghapusan kategori yang masih memiliki buku.
- Tanggal jatuh tempo dicatat eksplisit; durasi pinjaman dan denda belum ditetapkan oleh PRD.
- Seeder hanya membuat kategori dan buku contoh yang aman untuk pengembangan; tidak membuat akun anggota atau admin.

## Aturan yang harus dijaga

Buku hanya dipinjam saat stok tersedia lebih dari nol. Peminjaman berhasil mengurangi stok; pengembalian berhasil menambah stok. Transaksi tidak boleh dikembalikan dua kali. Pinjaman aktif yang melewati jatuh tempo ditampilkan terlambat. Riwayat tetap tersedia saat buku atau anggota dinonaktifkan. Pengujian perlu mencakup aturan tersebut dan batas akses anggota.
