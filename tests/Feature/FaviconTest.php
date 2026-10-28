<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_favicon_file_exists_and_is_a_valid_ico(): void
    {
        $path = public_path('favicon.ico');

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        $contents = file_get_contents($path);
        // An ICO file always begins with the 4-byte header: 0x00 0x00 0x01 0x00
        $this->assertStringStartsWith("\x00\x00\x01\x00", $contents);
    }

    public function test_pages_contain_favicon_link(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('<link rel="icon" href="'.asset('favicon.ico').'" sizes="any">', false);

        $loginResponse = $this->get(route('login'));
        $loginResponse->assertOk();
        $loginResponse->assertSee('<link rel="icon" href="'.asset('favicon.ico').'" sizes="any">', false);
    }

    public function test_home_button_has_open_book_icon_across_pages(): void
    {
        $bookPath = 'M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13';

        // Home page
        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee($bookPath, false);

        // Public catalog page
        $catalog = $this->get(route('catalog.index'));
        $catalog->assertOk();
        $catalog->assertSee($bookPath, false);

        // Auth pages
        $login = $this->get(route('login'));
        $login->assertOk();
        $login->assertSee($bookPath, false);

        $register = $this->get(route('register'));
        $register->assertOk();
        $register->assertSee($bookPath, false);

        // Admin workspace
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => true]);
        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee($bookPath, false);
    }
}
