<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Member Test',
            'nrp_nim' => '12345678',
            'email' => 'member@example.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role' => User::ROLE_ADMIN,
            'is_active' => false,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'member@example.test',
            'role' => User::ROLE_MEMBER,
            'is_active' => true,
        ]);
    }

    public function test_active_member_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_inactive_member_cannot_log_in(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected_with_a_clear_error(): void
    {
        $user = User::factory()->create();

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors([
            'email' => 'Email atau password tidak valid.',
        ]);

        $this->assertGuest();
    }

    public function test_registration_rejects_a_password_confirmation_mismatch(): void
    {
        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Member Test', 'nrp_nim' => '20249999',
            'email' => 'member-mismatch@example.test', 'password' => 'secret-password',
            'password_confirmation' => 'different-password',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('password');
    }

    public function test_admin_is_redirected_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->post(route('login.staff.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_log_in_through_the_member_portal(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Akun ini terdaftar sebagai petugas. Silakan masuk melalui halaman petugas.']);

        $this->assertGuest();
    }

    public function test_member_cannot_log_in_through_the_staff_portal(): void
    {
        $member = User::factory()->create();

        $this->from(route('login.staff'))->post(route('login.staff.store'), [
            'email' => $member->email,
            'password' => 'password',
        ])->assertRedirect(route('login.staff'))
            ->assertSessionHasErrors(['email' => 'Akun ini bukan akun petugas. Silakan masuk melalui halaman mahasiswa.']);

        $this->assertGuest();
    }

    public function test_staff_portal_renders_its_own_login_page(): void
    {
        $this->get(route('login.staff'))->assertOk()
            ->assertSee('Masuk sebagai petugas')
            ->assertSee('Masuk sebagai mahasiswa');
    }

    public function test_registration_hashes_password_and_rejects_duplicate_identity(): void
    {
        $existing = User::factory()->create(['nrp_nim' => '20240001', 'email' => 'existing@example.test']);

        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Another Member', 'nrp_nim' => $existing->nrp_nim,
            'email' => 'new@example.test', 'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('nrp_nim');

        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Another Member', 'nrp_nim' => '20240002',
            'email' => $existing->email, 'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('email');

        $this->post(route('register.store'), [
            'name' => 'New Member', 'nrp_nim' => '20240003',
            'email' => 'new@example.test', 'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $created = User::query()->where('email', 'new@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('secret-password', $created->password));
        $this->assertNotSame('secret-password', $created->password);
    }

    public function test_authenticated_access_is_revoked_when_user_is_deactivated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $user->forceFill(['is_active' => false])->save();

        $this->get(route('member.loans.index'))->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_member_visiting_login_is_redirected_to_dashboard(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('login'))->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_member_can_still_browse_the_landing_page(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('home'))->assertOk();
    }

    public function test_authenticated_admin_visiting_login_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('login'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get(route('home'))->assertOk();
    }
}
