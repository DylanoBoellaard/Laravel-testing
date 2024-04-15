<?php

namespace Tests\Feature;

use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_contains_filled_table(): void
    {
        Products::create([
            'name' => 'Product 1',
            'price' => 5.00
        ]);


        $response = $this->get('products/index');

        $response->assertStatus(200);

        $response->assertDontSee('No products found');
    }

    // Will fail - database inserts data during migration
    public function test_homepage_contains_empty_table(): void
    {
        $response = $this->get('products/index');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }
}
