<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_a_loan_and_decrease_one_copy(): void
    {
        [$admin, $member, $book] = $this->library();

        $this->actingAs($admin)->post(route('admin.loans.store'), ['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => '2026-10-28', 'due_at' => '2026-11-04'])->assertRedirect(route('admin.loans.index'));

        $this->assertDatabaseHas('loans', ['user_id' => $member->id, 'book_id' => $book->id, 'status' => Loan::STATUS_BORROWED]);
        $this->assertSame(0, $book->fresh()->available_stock);
    }

    public function test_empty_stock_does_not_create_a_loan(): void
    {
        [$admin, $member, $book] = $this->library(0);

        $this->actingAs($admin)->post(route('admin.loans.store'), ['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => '2026-10-28', 'due_at' => '2026-11-04'])->assertSessionHasErrors('book_id');

        $this->assertDatabaseCount('loans', 0);
        $this->assertSame(0, $book->fresh()->available_stock);
    }

    public function test_return_increases_stock_once_and_second_submit_is_rejected(): void
    {
        [$admin, $member, $book] = $this->library(0);
        $loan = Loan::create(['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => '2026-10-28', 'due_at' => '2026-11-04']);

        $this->actingAs($admin)->put(route('admin.loans.update', $loan), ['returned_at' => '2026-10-30'])->assertRedirect();
        $this->assertSame(1, $book->fresh()->available_stock);

        $this->actingAs($admin)->put(route('admin.loans.update', $loan), ['returned_at' => '2026-10-30'])->assertSessionHasErrors('returned_at');
        $this->assertSame(1, $book->fresh()->available_stock);
        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'status' => Loan::STATUS_RETURNED]);
        $this->assertSame('2026-10-30', $loan->fresh()->returned_at->toDateString());
    }

    public function test_inactive_member_and_book_cannot_be_selected_for_new_loan(): void
    {
        [$admin, $member, $book] = $this->library();
        $member->update(['is_active' => false]);
        $book->update(['is_active' => false]);

        $this->actingAs($admin)->get(route('admin.loans.create'))->assertOk()->assertDontSee($member->name)->assertDontSee($book->title);
        $this->actingAs($admin)->post(route('admin.loans.store'), ['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => '2026-10-28', 'due_at' => '2026-11-04'])->assertSessionHasErrors('user_id');
    }

    public function test_member_cannot_open_admin_transactions(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('admin.loans.index'))->assertForbidden();
    }

    /** @return array{User, User, Book} */
    private function library(int $availableStock = 1): array
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create();
        $book = Category::create(['name' => 'Test'])->books()->create(['title' => 'Buku Test', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => $availableStock]);

        return [$admin, $member, $book];
    }
}
