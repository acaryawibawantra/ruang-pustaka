# Kebutuhan data untuk dibahas bersama Anang dan Atma

Dokumen ini adalah bahan koordinasi, bukan skema final. Sepakati model, nama kolom, tipe data, foreign key, aturan penghapusan dan integrasi sebelum membuat migration atau seeder.

## Entitas dari PRD

| Entitas | Kebutuhan awal |
| --- | --- |
| Pengguna | Nama, NRP/NIM, email, password, peran dan status aktif. Registrasi anggota memerlukan nama, NRP/NIM, email dan password. |
| Kategori | Nama dan deskripsi; satu kategori memiliki banyak buku. |
| Buku | Judul, penulis, penerbit, tahun, ISBN, deskripsi, sampul, stok dan kategori. Satu buku dapat muncul dalam banyak transaksi sepanjang waktu. |
| Peminjaman | Anggota, buku, tanggal pinjam, jatuh tempo, tanggal kembali dan status. Satu anggota memiliki banyak transaksi. |

## Keputusan yang perlu disepakati

- Bentuk stok total dan stok tersedia, serta cara menjaganya konsisten saat transaksi bersamaan.
- Aturan identitas unik NRP/NIM, email dan ISBN, termasuk data yang boleh kosong.
- Cara menonaktifkan anggota atau buku tanpa menghapus riwayat, dan perilaku saat kategori dihapus.
- Status transaksi dan cara menentukan keterlambatan dari tanggal jatuh tempo.
- Durasi pinjaman dan aturan tanggal; tarif denda hanya jika fitur denda dikerjakan.
- Cara aman menyiapkan akun admin dan cara membatasi anggota hanya melihat riwayatnya sendiri.
- Penanggung jawab pembuatan migration, data contoh dan pengaturan MySQL lokal.

## Aturan yang harus dijaga

Buku hanya dipinjam saat stok tersedia lebih dari nol. Peminjaman berhasil mengurangi stok; pengembalian berhasil menambah stok. Transaksi tidak boleh dikembalikan dua kali. Pinjaman aktif yang melewati jatuh tempo ditampilkan terlambat. Riwayat tetap tersedia saat buku atau anggota dinonaktifkan. Pengujian perlu mencakup aturan tersebut dan batas akses anggota.
