<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class LibraryCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Teknologi' => 'Buku teknologi, pemrograman, dan informatika.',
            'Sastra' => 'Novel dan karya sastra Indonesia.',
            'Sains' => 'Buku sains dan pengetahuan alam.',
            'Sejarah' => 'Sejarah Indonesia dan dunia.',
            'Pengembangan Diri' => 'Buku pengembangan diri dan filsafat praktis.',
        ];

        $categoryIds = [];

        foreach ($categories as $name => $description) {
            $categoryIds[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['description' => $description],
            )->id;
        }

        foreach ($this->books() as $book) {
            $category = $book['category'];
            unset($book['category']);

            Book::firstOrCreate(
                ['title' => $book['title'], 'author' => $book['author']],
                $book + [
                    'category_id' => $categoryIds[$category],
                    'total_stock' => 3,
                    'available_stock' => 3,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Sample catalog used for development and demonstrations.
     *
     * @return array<int, array<string, mixed>>
     */
    private function books(): array
    {
        return [
            [
                'category' => 'Teknologi',
                'title' => 'Pemrograman Web Dinamis Menggunakan PHP dan MySQL',
                'author' => 'Rudyanto Arief',
                'publisher' => 'Andi Publisher',
                'publication_year' => 2011,
                'isbn' => '978-979-29-2759-7',
                'description' => 'Membahas dasar pemrograman web dinamis dengan PHP dan MySQL, mulai dari struktur HTML, pengelolaan basis data, hingga pembuatan aplikasi sederhana.',
            ],
            [
                'category' => 'Teknologi',
                'title' => 'Membuat Aplikasi Web dengan PHP dan Database MySQL',
                'author' => 'Abdul Kadir',
                'publisher' => 'Andi Publisher',
                'publication_year' => 2023,
                'isbn' => '978-623-01-3278-0',
                'description' => 'Panduan praktis membangun aplikasi web menggunakan PHP dan basis data MySQL, disertai contoh kasus yang dapat diikuti langkah demi langkah.',
            ],
            [
                'category' => 'Teknologi',
                'title' => 'Pemrograman Berorientasi Objek Menggunakan C#',
                'author' => 'Adi Nugroho',
                'publisher' => 'Andi Publisher',
                'publication_year' => 2017,
                'isbn' => '978-979-29-6665-7',
                'description' => 'Pengantar konsep pemrograman berorientasi objek dengan C#, mencakup kelas, pewarisan, polimorfisme, dan penerapannya pada aplikasi nyata.',
            ],
            [
                'category' => 'Sastra',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'isbn' => '979-3062-79-7',
                'description' => 'Kisah sepuluh anak Belitung yang menempuh pendidikan dengan keterbatasan, mengangkat tema persahabatan, ketekunan, dan harapan.',
            ],
            [
                'category' => 'Sastra',
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 1980,
                'isbn' => '978-979-97312-3-4',
                'description' => 'Novel pertama tetralogi Buru yang mengisahkan Minke pada masa kolonial, menyoroti kolonialisme, pendidikan, dan kesetaraan.',
            ],
            [
                'category' => 'Sastra',
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2002,
                'isbn' => '978-602-03-1258-3',
                'description' => 'Novel yang mengisahkan Dewi Ayu dan keluarganya, membentang dari masa kolonial hingga awal kemerdekaan Indonesia.',
            ],
            [
                'category' => 'Sains',
                'title' => 'Eksplorasi Tata Surya',
                'author' => 'A. Gunawan Admiranto',
                'publisher' => 'Mizan',
                'publication_year' => 2017,
                'isbn' => '978-602-441-009-4',
                'description' => 'Menjelaskan anggota tata surya, misi antariksa, dan perkembangan astronomi dalam bahasa yang mudah dipahami pembaca umum.',
            ],
            [
                'category' => 'Sejarah',
                'title' => 'Sejarah Indonesia Modern 1200-2008',
                'author' => 'M. C. Ricklefs',
                'publisher' => 'Serambi',
                'publication_year' => 2008,
                'isbn' => '978-979-024-115-2',
                'description' => 'Uraian menyeluruh sejarah Indonesia sejak masa kerajaan awal hingga era reformasi, menjadi rujukan penting bagi studi sejarah Indonesia.',
            ],
            [
                'category' => 'Pengembangan Diri',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'publication_year' => 2019,
                'isbn' => '978-602-412-518-9',
                'description' => 'Pengantar filsafat Stoa untuk kehidupan sehari-hari, membahas pengelolaan emosi, kebahagiaan, dan ketenangan diri.',
            ],
            [
                'category' => 'Pengembangan Diri',
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2019,
                'isbn' => '978-602-06-3317-6',
                'description' => 'Menjabarkan cara membangun kebiasaan baik dan menghilangkan kebiasaan buruk melalui perubahan kecil yang dilakukan secara konsisten.',
            ],
        ];
    }
}
