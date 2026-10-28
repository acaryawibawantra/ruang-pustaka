<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_favicon_png_file_exists_and_is_valid(): void
    {
        $path = public_path('images/perpustakaan-its-mark.png');

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        $contents = file_get_contents($path);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $contents);
    }

    public function test_pages_contain_favicon_link(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('<link rel="icon" href="'.asset('images/perpustakaan-its-mark.png').'" type="image/png">', false);

        $loginResponse = $this->get(route('login'));
        $loginResponse->assertOk();
        $loginResponse->assertSee('<link rel="icon" href="'.asset('images/perpustakaan-its-mark.png').'" type="image/png">', false);
    }

    public function test_home_button_has_open_book_icon_across_pages(): void
    {
        $logoAsset = asset('images/perpustakaan-its-mark.png');

        // Home page
        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee($logoAsset, false);

        // Public catalog page
        $catalog = $this->get(route('catalog.index'));
        $catalog->assertOk();
        $catalog->assertSee($logoAsset, false);

        // Auth pages
        $login = $this->get(route('login'));
        $login->assertOk();
        $login->assertSee($logoAsset, false);

        $register = $this->get(route('register'));
        $register->assertOk();
        $register->assertSee($logoAsset, false);

        // Admin workspace
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => true]);
        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee($logoAsset, false);
    }
}
