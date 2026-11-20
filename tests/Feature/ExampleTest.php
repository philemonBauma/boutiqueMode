<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_the_application_starts_at_the_login_screen(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
