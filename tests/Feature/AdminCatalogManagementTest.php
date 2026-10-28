<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_shows_active_catalog_books_only(): void
    {
        $category = Category::create(['name' => 'Teknologi']);
        $category->books()->create(['title' => 'Buku tampil', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 1, 'is_active' => true]);
        $category->books()->create(['title' => 'Buku tersembunyi', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 0, 'is_active' => false]);

        $this->get(route('home'))->assertOk()->assertSee('Buku tampil')->assertDontSee('Buku tersembunyi');
    }

    public function test_member_cannot_access_book_or_category_actions(): void
    {
        $member = User::factory()->create();
        $this->actingAs($member)->get(route('admin.books.index'))->assertForbidden();
        $this->actingAs($member)->post(route('admin.categories.store'), ['name' => 'Teknologi'])->assertForbidden();
    }

    public function test_admin_can_create_book_with_cover_and_filter_books(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::create(['name' => 'Teknologi']);
        $this->actingAs($admin)->post(route('admin.books.store'), ['category_id' => $category->id, 'title' => 'Basis Data', 'author' => 'Penulis', 'total_stock' => 4, 'available_stock' => 4, 'is_active' => 1, 'cover_image' => UploadedFile::fake()->image('cover.png')])->assertRedirect(route('admin.books.index'))->assertSessionHas('success');
        $book = Book::firstOrFail();
        $this->assertSame('covers/'.basename($book->cover_image), $book->cover_image);
        Storage::disk('public')->assertExists($book->cover_image);
        $this->actingAs($admin)->get(route('admin.books.index', ['q' => 'Basis']))->assertOk()->assertSee('Basis Data');
    }

    public function test_invalid_cover_and_duplicate_isbn_are_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::create(['name' => 'Teknologi']);
        $payload = ['category_id' => $category->id, 'title' => 'Buku', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 1, 'isbn' => '123'];
        $this->actingAs($admin)->post(route('admin.books.store'), $payload + ['cover_image' => UploadedFile::fake()->create('cover.pdf', 10, 'application/pdf')])->assertSessionHasErrors('cover_image');
        $this->actingAs($admin)->post(route('admin.books.store'), $payload)->assertRedirect();
        $this->actingAs($admin)->post(route('admin.books.store'), $payload)->assertSessionHasErrors('isbn');
        $this->assertCount(1, Book::all());
    }

    public function test_stock_cannot_be_edited_below_active_loans_and_category_in_use_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Teknologi']);
        $book = $category->books()->create(['title' => 'Basis Data', 'author' => 'Penulis', 'total_stock' => 2, 'available_stock' => 1]);
        Loan::create(['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => today(), 'due_at' => today()->addWeek()]);
        $this->actingAs($admin)->put(route('admin.books.update', $book), ['category_id' => $category->id, 'title' => $book->title, 'author' => $book->author, 'total_stock' => 2, 'available_stock' => 0, 'is_active' => 1])->assertSessionHasErrors('available_stock');
        $this->assertSame(1, $book->fresh()->available_stock);
        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_deactivate_book_without_deleting_history(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::create(['name' => 'Teknologi']);
        $book = $category->books()->create(['title' => 'Arsip', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 1]);
        $this->actingAs($admin)->patch(route('admin.books.deactivate', $book))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('books', ['id' => $book->id, 'is_active' => false]);
    }
}
