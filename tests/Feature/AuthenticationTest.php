<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ])->assertRedirect(route('home'));

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
}
