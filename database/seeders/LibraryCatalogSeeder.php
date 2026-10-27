<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class LibraryCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $technology = Category::firstOrCreate(
            ['name' => 'Teknologi'],
            ['description' => 'Buku teknologi dan informatika.'],
        );

        $literacy = Category::firstOrCreate(
            ['name' => 'Literasi'],
            ['description' => 'Buku literasi dan pengembangan diri.'],
        );

        foreach ([
            [
                'category_id' => $technology->id,
                'title' => 'Pengantar Basis Data',
                'author' => 'Tim Ruang Pustaka',
                'publisher' => 'Ruang Pustaka',
                'publication_year' => 2026,
                'description' => 'Contoh data katalog untuk lingkungan pengembangan.',
            ],
            [
                'category_id' => $technology->id,
                'title' => 'Dasar Pemrograman Web',
                'author' => 'Tim Ruang Pustaka',
                'publisher' => 'Ruang Pustaka',
                'publication_year' => 2026,
                'description' => 'Contoh data katalog untuk lingkungan pengembangan.',
            ],
            [
                'category_id' => $literacy->id,
                'title' => 'Literasi Digital untuk Mahasiswa',
                'author' => 'Tim Ruang Pustaka',
                'publisher' => 'Ruang Pustaka',
                'publication_year' => 2026,
                'description' => 'Contoh data katalog untuk lingkungan pengembangan.',
            ],
        ] as $book) {
            Book::firstOrCreate(
                ['title' => $book['title'], 'author' => $book['author']],
                $book + [
                    'total_stock' => 3,
                    'available_stock' => 3,
                ],
            );
        }
    }
}
