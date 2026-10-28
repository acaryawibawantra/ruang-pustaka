<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibrarySchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_library_relationships_and_authentication_fields_are_available(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Teknologi']);
        $book = $category->books()->create([
            'title' => 'Pengantar Basis Data',
            'author' => 'Tim Ruang Pustaka',
            'total_stock' => 2,
            'available_stock' => 2,
        ]);
        $loan = $book->loans()->create([
            'user_id' => $user->id,
            'borrowed_at' => today(),
            'due_at' => today()->addDays(14),
        ]);

        $this->assertSame('member', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertTrue(password_verify('password', $user->password));
        $this->assertTrue($user->loans->contains($loan));
        $this->assertTrue($category->books->contains($book));
        $this->assertTrue($book->loans->contains($loan));
        $this->assertSame($user->id, $loan->user->id);
        $this->assertSame($book->id, $loan->book->id);
    }

    public function test_overdue_state_is_derived_from_due_date_and_return_status(): void
    {
        $this->travelTo(today()->setTime(12, 0));

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Literasi']);
        $book = $category->books()->create([
            'title' => 'Literasi Digital',
            'author' => 'Tim Ruang Pustaka',
        ]);
        $loan = $book->loans()->create([
            'user_id' => $user->id,
            'borrowed_at' => today()->subDays(10),
            'due_at' => today()->subDay(),
        ]);

        $this->assertTrue($loan->is_overdue);

        $loan->update([
            'status' => Loan::STATUS_RETURNED,
            'returned_at' => today(),
        ]);

        $this->assertFalse($loan->fresh()->is_overdue);
    }

    public function test_nrp_nim_must_be_unique(): void
    {
        $user = User::factory()->create();

        $this->expectException(QueryException::class);

        User::factory()->create(['nrp_nim' => $user->nrp_nim]);
    }

    public function test_catalog_seeder_is_repeatable_and_does_not_create_an_admin(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('books', 10);
        $this->assertDatabaseCount('users', 0);
    }
}
