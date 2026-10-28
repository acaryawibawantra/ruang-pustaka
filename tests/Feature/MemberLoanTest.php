<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberLoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_only_read_their_own_loan_history(): void
    {
        $member = User::factory()->create();
        $otherMember = User::factory()->create();
        $book = Category::create(['name' => 'Teknologi'])->books()->create([
            'title' => 'Basis Data', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 0,
        ]);
        $ownLoan = Loan::create([
            'user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => today(),
            'due_at' => today()->addWeek(),
        ]);
        $otherLoan = Loan::create([
            'user_id' => $otherMember->id, 'book_id' => $book->id, 'borrowed_at' => today(),
            'due_at' => today()->addWeek(),
        ]);

        $this->actingAs($member)->get(route('member.loans.index'))->assertOk()
            ->assertSee(route('member.loans.show', $ownLoan))
            ->assertDontSee(route('member.loans.show', $otherLoan));
        $this->get(route('member.loans.show', $otherLoan))->assertNotFound();
        $this->get(route('member.loans.show', $ownLoan))->assertOk()->assertSee('Basis Data');
    }

    public function test_guest_is_redirected_to_login_for_member_history(): void
    {
        $this->get(route('member.loans.index'))->assertRedirect(route('login'));
    }
}
