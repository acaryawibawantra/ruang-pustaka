<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    #[DataProvider('adminAccessCases')]
    public function test_admin_gate_requires_both_admin_role_and_active_status(string $role, bool $active, bool $allowed): void
    {
        $user = User::factory()->make(['role' => $role, 'is_active' => $active]);

        $this->assertSame($allowed, Gate::forUser($user)->allows('access-admin'));
    }

    public static function adminAccessCases(): array
    {
        return [
            'active admin' => [User::ROLE_ADMIN, true, true],
            'inactive admin' => [User::ROLE_ADMIN, false, false],
            'active member' => [User::ROLE_MEMBER, true, false],
            'inactive member' => [User::ROLE_MEMBER, false, false],
        ];
    }

    public function test_member_cannot_open_admin_workspace(): void
    {
        $user = User::factory()->make(['role' => User::ROLE_MEMBER]);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_sees_workspace_and_escaped_identity_with_no_fake_statistics(): void
    {
        $user = User::factory()->make(['role' => User::ROLE_ADMIN, 'name' => '<script>alert(1)</script>']);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk()
            ->assertSee('Tidak ada pinjaman terlambat')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee(route('logout'))->assertSee('Peminjaman')
            ->assertDontSee('Contoh komponen pengelolaan');
    }

    public function test_preview_renders_shared_components_without_database_or_login(): void
    {
        $this->get(route('design-system.admin'))->assertOk()->assertSee('Pratinjau kerangka admin')
            ->assertSee('Contoh komponen pengelolaan')->assertSee('Tabel data contoh')
            ->assertSee('name="example-title"', false)
            ->assertSee('id="example-title"', false)
            ->assertSee('aria-describedby="example-title-hint"', false)
            ->assertSee('name="example-category"', false)
            ->assertSee('name="example-description"', false);
    }

    public function test_preview_is_not_accessible_in_production(): void
    {
        $this->app->instance('env', 'production');
        $this->get(route('design-system.admin'))->assertNotFound();
    }

    public function test_workspace_displays_flash_messages_and_validation_summary_safely(): void
    {
        $user = User::factory()->make(['role' => User::ROLE_ADMIN]);
        $this->actingAs($user)->withSession([
            'success' => 'Data berhasil disimpan.',
            'error' => '<script>danger()</script>',
        ])->get(route('admin.dashboard'))->assertOk()->assertSee('Data berhasil disimpan.')
            ->assertSee('&lt;script&gt;danger()&lt;/script&gt;', false)
            ->assertDontSee('<script>danger()</script>', false);
    }

    public function test_design_system_field_links_validation_error_to_control(): void
    {
        $this->withViewErrors(['example-title' => 'Judul wajib diisi.'])
            ->view('admin.dashboard', ['preview' => true, 'metrics' => null, 'overdueLoans' => collect()])
            ->assertSee('Judul wajib diisi.')
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="example-title-hint example-title-error"', false)
            ->assertSee('id="example-title-error"', false);
    }
}
