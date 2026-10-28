<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_inactive_member_is_blocked_from_dashboard(): void
    {
        $inactive = User::factory()->create(['is_active' => false]);

        $this->actingAs($inactive)
            ->get('/dashboard')
            ->assertRedirect(route('login'));
    }

    public function test_member_sees_only_own_active_loans_ordered_by_due_date(): void
    {
        $member = User::factory()->create();
        $otherMember = User::factory()->create();

        $category = Category::create(['name' => 'Sains']);
        $book1 = $category->books()->create([
            'title' => 'Fisika Dasar', 'author' => 'Author A', 'total_stock' => 2, 'available_stock' => 1,
        ]);
        $book2 = $category->books()->create([
            'title' => 'Kimia Organik', 'author' => 'Author B', 'total_stock' => 2, 'available_stock' => 1,
        ]);
        $otherBook = $category->books()->create([
            'title' => 'Biologi Molekuler', 'author' => 'Author C', 'total_stock' => 2, 'available_stock' => 1,
        ]);

        $laterLoan = Loan::create([
            'user_id' => $member->id,
            'book_id' => $book1->id,
            'borrowed_at' => today()->subDays(5),
            'due_at' => today()->addDays(5),
            'status' => Loan::STATUS_BORROWED,
        ]);

        $overdueLoan = Loan::create([
            'user_id' => $member->id,
            'book_id' => $book2->id,
            'borrowed_at' => today()->subDays(10),
            'due_at' => today()->subDays(2),
            'status' => Loan::STATUS_BORROWED,
        ]);

        $otherLoan = Loan::create([
            'user_id' => $otherMember->id,
            'book_id' => $otherBook->id,
            'borrowed_at' => today()->subDays(2),
            'due_at' => today()->addDays(7),
            'status' => Loan::STATUS_BORROWED,
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('activeLoans', function ($activeLoans) use ($overdueLoan, $laterLoan, $otherLoan) {
            return $activeLoans->count() === 2
                && $activeLoans->first()->id === $overdueLoan->id
                && $activeLoans->last()->id === $laterLoan->id
                && ! $activeLoans->contains('id', $otherLoan->id);
        });
    }

    public function test_dashboard_provides_summary_metrics(): void
    {
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Literatur']);
        $book = $category->books()->create([
            'title' => 'Sastra Klasik', 'author' => 'Author X', 'total_stock' => 5, 'available_stock' => 1,
        ]);

        // Overdue loan
        Loan::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(10),
            'due_at' => today()->subDays(3),
            'status' => Loan::STATUS_BORROWED,
        ]);

        // Approaching due date loan (due in 2 days)
        Loan::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(5),
            'due_at' => today()->addDays(2),
            'status' => Loan::STATUS_BORROWED,
        ]);

        // Normal active loan (due in 8 days)
        Loan::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(1),
            'due_at' => today()->addDays(8),
            'status' => Loan::STATUS_BORROWED,
        ]);

        // Returned loan
        Loan::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(20),
            'due_at' => today()->subDays(10),
            'returned_at' => today()->subDays(9),
            'status' => Loan::STATUS_RETURNED,
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('metrics', [
            'active' => 3,
            'overdue' => 1,
            'approaching' => 1,
            'returned_total' => 1,
        ]);
    }

    public function test_dashboard_renders_badges_for_overdue_approaching_and_active_loans(): void
    {
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Sains']);

        $overdueBook = $category->books()->create([
            'title' => 'Fisika Kuantum', 'author' => 'Author A', 'total_stock' => 2, 'available_stock' => 0,
        ]);
        $approachingBook = $category->books()->create([
            'title' => 'Kimia Organik', 'author' => 'Author B', 'total_stock' => 2, 'available_stock' => 0,
        ]);
        $activeBook = $category->books()->create([
            'title' => 'Biologi Molekuler', 'author' => 'Author C', 'total_stock' => 2, 'available_stock' => 0,
        ]);

        Loan::create([
            'user_id' => $member->id,
            'book_id' => $overdueBook->id,
            'borrowed_at' => today()->subDays(10),
            'due_at' => today()->subDays(3),
            'status' => Loan::STATUS_BORROWED,
        ]);

        Loan::create([
            'user_id' => $member->id,
            'book_id' => $approachingBook->id,
            'borrowed_at' => today()->subDays(5),
            'due_at' => today()->addDays(2),
            'status' => Loan::STATUS_BORROWED,
        ]);

        Loan::create([
            'user_id' => $member->id,
            'book_id' => $activeBook->id,
            'borrowed_at' => today()->subDays(1),
            'due_at' => today()->addDays(10),
            'status' => Loan::STATUS_BORROWED,
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertOk();
        $response->assertSeeText('Fisika Kuantum');
        $response->assertSeeText('Kimia Organik');
        $response->assertSeeText('Biologi Molekuler');
        $response->assertSeeText('Terlambat • Lewat 3 hari');
        $response->assertSeeText('Mendekati tempo • 2 hari lagi');
        $response->assertSeeText('Dipinjam • 10 hari lagi');
    }

    public function test_dashboard_renders_empty_state_when_no_active_loans(): void
    {
        $member = User::factory()->create();

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertOk();
        $response->assertSeeText('Tidak ada pinjaman aktif');
        $response->assertSeeText('Jelajahi katalog');
    }

    public function test_dashboard_lists_recently_returned_loans_with_history_link(): void
    {
        $member = User::factory()->create();
        $category = Category::create(['name' => 'Literatur']);
        $book = $category->books()->create([
            'title' => 'Sastra Klasik', 'author' => 'Author X', 'total_stock' => 3, 'available_stock' => 3,
        ]);

        Loan::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(20),
            'due_at' => today()->subDays(6),
            'returned_at' => today()->subDays(5),
            'status' => Loan::STATUS_RETURNED,
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertOk();
        $response->assertSeeText('Sastra Klasik');
        $response->assertSeeText('Dikembalikan');
        $response->assertSee(route('member.loans.index'), false);
    }

    public function test_navigation_header_shows_dashboard_link_for_authenticated_member(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('catalog.index'))
            ->assertOk()
            ->assertSee(route('dashboard'), false);
    }

    public function test_navigation_header_shows_landing_page_link_for_authenticated_member(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('catalog.index'))
            ->assertOk()
            ->assertSee('>Beranda</a>', false);
    }
}
