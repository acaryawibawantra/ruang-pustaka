<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_view_and_edit_members(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create(['name' => 'Atma', 'nrp_nim' => '12345678', 'email' => 'atma@example.test']);
        User::factory()->create(['name' => 'Tidak ditemukan']);

        $this->actingAs($admin)->get(route('admin.members.index', ['q' => '12345678']))
            ->assertOk()->assertSee('Atma')->assertDontSee('Tidak ditemukan');
        $this->get(route('admin.members.show', $member))->assertOk()->assertSee('atma@example.test');
        $this->put(route('admin.members.update', $member), ['name' => 'Atma Baru', 'nrp_nim' => '12345678', 'email' => 'baru@example.test', 'is_active' => 1])
            ->assertRedirect(route('admin.members.show', $member));
        $this->assertDatabaseHas('users', ['id' => $member->id, 'name' => 'Atma Baru', 'nrp_nim' => '12345678', 'email' => 'baru@example.test']);
    }

    public function test_duplicate_identity_is_rejected_but_current_values_are_allowed(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create(['nrp_nim' => '11111111', 'email' => 'one@example.test']);
        $other = User::factory()->create(['nrp_nim' => '22222222', 'email' => 'two@example.test']);

        $this->actingAs($admin)->put(route('admin.members.update', $member), ['name' => $member->name, 'nrp_nim' => $other->nrp_nim, 'email' => $other->email, 'is_active' => 1])->assertSessionHasErrors(['nrp_nim', 'email']);
        $this->put(route('admin.members.update', $member), ['name' => $member->name, 'nrp_nim' => $member->nrp_nim, 'email' => $member->email, 'is_active' => 1])->assertRedirect();
    }

    public function test_deactivation_preserves_loan_history_and_disallows_role_changes(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $member = User::factory()->create();
        $book = Category::create(['name' => 'Teknologi'])->books()->create(['title' => 'Basis Data', 'author' => 'Penulis', 'total_stock' => 1, 'available_stock' => 0]);
        $loan = Loan::create(['user_id' => $member->id, 'book_id' => $book->id, 'borrowed_at' => today(), 'due_at' => today()->addWeek()]);

        $this->actingAs($admin)->patch(route('admin.members.toggle-status', $member))->assertRedirect(route('admin.members.index'));
        $this->assertDatabaseHas('users', ['id' => $member->id, 'is_active' => false]);
        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'user_id' => $member->id]);
        $this->put(route('admin.members.update', $member), ['name' => $member->name, 'nrp_nim' => $member->nrp_nim, 'email' => $member->email, 'role' => User::ROLE_ADMIN])->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $member->id, 'role' => User::ROLE_MEMBER]);
    }

    public function test_guests_and_members_cannot_access_member_management_or_update(): void
    {
        $member = User::factory()->create();

        $this->get(route('admin.members.index'))->assertRedirect(route('login'));
        $this->actingAs($member)->get(route('admin.members.index'))->assertForbidden();
        $this->actingAs($member)->put(route('admin.members.update', $member), ['name' => 'Tidak boleh', 'nrp_nim' => $member->nrp_nim, 'email' => $member->email])->assertForbidden();
    }
}
