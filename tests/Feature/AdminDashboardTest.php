<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_database_returns_zero_metrics_and_an_empty_state(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()
            ->assertViewHas('metrics', ['titles' => 0, 'copies' => 0, 'available' => 0, 'active' => 0, 'overdue' => 0])
            ->assertSee('Tidak ada pinjaman terlambat');
    }

    public function test_metrics_distinguish_active_collection_outstanding_and_returned_loans(): void
    {
        $this->travelTo(today()->setTime(12, 0));
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Teknologi']);
        $first = $category->books()->create(['title' => 'Basis Data', 'author' => 'Penulis', 'total_stock' => 6, 'available_stock' => 2, 'is_active' => true]);
        $category->books()->create(['title' => 'Web', 'author' => 'Penulis', 'total_stock' => 4, 'available_stock' => 4, 'is_active' => true]);
        $inactive = $category->books()->create(['title' => 'Buku Arsip', 'author' => 'Penulis', 'total_stock' => 5, 'available_stock' => 0, 'is_active' => false]);
        $inactiveMember = User::factory()->create(['is_active' => false]);
        $this->loan($first, $member, ['due_at' => today()->addDay()]);
        $this->loan($first, $member, ['due_at' => today()]);
        $overdue = $this->loan($first, $member, ['due_at' => today()->subDay()]);
        $oldInactive = $this->loan($inactive, $inactiveMember, ['due_at' => today()->subDays(3)]);
        $this->loan($first, $member, ['due_at' => today()->subDays(5), 'status' => Loan::STATUS_RETURNED, 'returned_at' => today()->subDay()]);
        $this->loan($first, $member, ['due_at' => today()->subDays(4), 'returned_at' => today()->subDay()]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()
            ->assertViewHas('metrics', ['titles' => 2, 'copies' => 10, 'available' => 6, 'active' => 4, 'overdue' => 2])
            ->assertViewHas('overdueLoans', fn ($loans): bool => $loans->pluck('id')->all() === [$oldInactive->id, $overdue->id])
            ->assertSee('Buku Arsip')->assertSee('3 hari')->assertSee('1 hari');
    }

    public function test_overdue_list_is_limited_to_five_oldest_and_escapes_member_and_book_names(): void
    {
        $this->travelTo(today()->setTime(12, 0));
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create(['name' => '<script>member()</script>']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = $category->books()->create(['title' => '<script>book()</script>', 'author' => 'Penulis', 'total_stock' => 7, 'available_stock' => 0]);
        $oldestIds = [];
        for ($days = 7; $days >= 1; $days--) {
            $oldestIds[] = $this->loan($book, $member, ['due_at' => today()->subDays($days)])->id;
        }

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()
            ->assertViewHas('overdueLoans', fn ($loans): bool => $loans->pluck('id')->all() === array_slice($oldestIds, 0, 5))
            ->assertSee('Menampilkan 5 dari 7 pinjaman terlambat.')
            ->assertSee('&lt;script&gt;member()&lt;/script&gt;', false)
            ->assertSee('&lt;script&gt;book()&lt;/script&gt;', false)
            ->assertDontSee('<script>member()</script>', false)->assertDontSee('<script>book()</script>', false);
    }

    public function test_returned_loan_disappears_from_metrics_after_page_is_reloaded(): void
    {
        $this->travelTo(today()->setTime(12, 0));
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Teknologi']);
        $book = $category->books()->create(['title' => 'Data', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 0]);
        $loan = $this->loan($book, $member, ['due_at' => today()->subDay()]);
        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertViewHas('metrics', fn ($metrics): bool => $metrics['active'] === 1 && $metrics['overdue'] === 1);

        $loan->update(['status' => Loan::STATUS_RETURNED, 'returned_at' => today()]);
        $book->update(['available_stock' => 1]);
        $this->get(route('admin.dashboard'))->assertOk()
            ->assertViewHas('metrics', ['titles' => 1, 'copies' => 1, 'available' => 1, 'active' => 0, 'overdue' => 0])
            ->assertSee('Tidak ada pinjaman terlambat');
    }

    private function loan(Book $book, User $member, array $attributes = []): Loan
    {
        return $book->loans()->create(array_merge([
            'user_id' => $member->id, 'borrowed_at' => today()->subDays(14),
            'due_at' => today()->addDay(), 'status' => Loan::STATUS_BORROWED,
            'returned_at' => null,
        ], $attributes));
    }
}
