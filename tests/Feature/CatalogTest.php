<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_search_matches_title_or_author_and_excludes_inactive_books(): void
    {
        $category = Category::create(['name' => 'Teknologi']);
        $byTitle = $this->book($category, ['title' => 'Belajar Laravel']);
        $byAuthor = $this->book($category, ['title' => 'Panduan Web', 'author' => 'Laravel Community']);
        $this->book($category, ['title' => 'Arsip', 'author' => 'Laravel', 'is_active' => false]);
        $this->book($category, ['title' => 'Matematika']);

        $this->get(route('catalog.index', ['q' => 'laravel']))->assertOk()
            ->assertSee('Belajar Laravel')->assertSee('Panduan Web')
            ->assertDontSee('Arsip')->assertDontSee('Matematika')
            ->assertViewHas('books', fn ($books): bool => $books->total() === 2
                && $books->pluck('id')->sort()->values()->all() === collect([$byTitle->id, $byAuthor->id])->sort()->values()->all());
    }

    public function test_search_and_category_filters_are_combined(): void
    {
        $technology = Category::create(['name' => 'Teknologi']);
        $literacy = Category::create(['name' => 'Literasi']);
        $book = $this->book($technology, ['title' => 'Dasar Digital']);
        $this->book($literacy, ['title' => 'Literasi Digital']);
        $this->book($technology, ['title' => 'Jaringan Komputer']);

        $this->get(route('catalog.index', ['q' => 'Digital', 'category' => $technology->id]))
            ->assertOk()->assertViewHas('books', fn ($books): bool => $books->pluck('id')->all() === [$book->id]);
    }

    public function test_catalog_has_empty_state_and_validates_filter_input(): void
    {
        $this->get(route('catalog.index'))->assertOk()->assertSee('Koleksi belum tersedia');
        $this->get(route('catalog.index', ['q' => 'Tidak ada']))->assertOk()->assertSee('Buku belum ditemukan');
        $this->from(route('catalog.index'))->get(route('catalog.index', ['q' => ['invalid']]))
            ->assertRedirect(route('catalog.index'))->assertSessionHasErrors('q');
        $this->from(route('catalog.index'))->get(route('catalog.index', ['category' => 999]))
            ->assertRedirect(route('catalog.index'))->assertSessionHasErrors('category');
    }

    public function test_pagination_keeps_search_and_category(): void
    {
        $category = Category::create(['name' => 'Teknologi']);
        for ($index = 1; $index <= 13; $index++) {
            $this->book($category, ['title' => sprintf('Buku %02d', $index)]);
        }

        $this->get(route('catalog.index', ['q' => 'Buku', 'category' => $category->id, 'page' => 2]))
            ->assertOk()->assertSee('Buku 13')->assertDontSee('Buku 01')
            ->assertViewHas('books', fn ($books): bool => $books->total() === 13
                && str_contains($books->previousPageUrl(), 'q=Buku')
                && str_contains($books->previousPageUrl(), 'category='.$category->id));
    }

    public function test_detail_shows_metadata_stock_and_uploaded_cover(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('covers/book.jpg', 'image');
        $category = Category::create(['name' => 'Teknologi']);
        $book = $this->book($category, [
            'title' => 'Pengantar Basis Data', 'publisher' => 'Penerbit Kampus',
            'publication_year' => 2026, 'isbn' => '9781234567890',
            'description' => 'Mengenal data relasional.', 'cover_image' => 'covers/book.jpg',
        ]);

        $this->get(route('catalog.show', $book))->assertOk()->assertSee('Pengantar Basis Data')
            ->assertSee('Penerbit Kampus')->assertSee('9781234567890')->assertSee('2026')
            ->assertSee('Mengenal data relasional.')->assertSee('2 eksemplar tersedia')
            ->assertSee(Storage::disk('public')->url('covers/book.jpg'));
    }

    public function test_unavailable_book_has_cover_fallback_and_inactive_or_missing_book_is_not_public(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Teknologi']);
        $book = $this->book($category, ['available_stock' => 0, 'cover_image' => 'covers/missing.jpg']);
        $this->get(route('catalog.show', $book))->assertOk()->assertSee('Tidak tersedia')
            ->assertSee('Sampul belum tersedia')->assertSee('Belum dicantumkan');
        $book->update(['is_active' => false]);
        $this->get(route('catalog.show', $book))->assertNotFound();
        $this->get(route('catalog.show', 999))->assertNotFound();
    }

    private function book(Category $category, array $attributes = []): Book
    {
        return $category->books()->create(array_merge([
            'title' => 'Buku Contoh', 'author' => 'Penulis Kampus',
            'total_stock' => 3, 'available_stock' => 2, 'is_active' => true,
        ], $attributes));
    }
}
