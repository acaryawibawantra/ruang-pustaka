<?php

namespace Tests\Feature;

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
}
