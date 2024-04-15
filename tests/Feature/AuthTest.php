<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_product(): void
    {
        // Get the products index page
        $response = $this->get('/products/index');

        // Assert status is code 302 (redirect code)
        $response->assertStatus(302);

        // Assert that page gets redirected to the login page
        $response->assertRedirect('login');
    }

    public function test_login_redirects_to_products()
    {
        // Create a new user
        User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password123')
        ]);

        // Login with details
        $response = $this->post('/login', [
            'email' => 'user@user.com',
            'password' => 'password123'
        ]);

        // Assert status is code 302 (redirect code)
        $response->assertStatus(302);

        // Assert that page gets redirected to the login page
        $response->assertRedirect('/products/index');
    }
}
