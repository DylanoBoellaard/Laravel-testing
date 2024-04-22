<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    // Required for setUp()
    private User $user;
    private User $admin;

    // Setup global variable ($user)
    protected function setUp(): void
    {
        // Required
        parent::setUp();

        // Setup and create a user
        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
    }

    // Test to check if homepage contains created data
    public function test_homepage_contains_filled_table(): void
    {
        // Create a new product using the model
        $product = Products::create([
            'name' => 'Product 1',
            'price' => 5.00
        ]);

        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/index');

        // Check if the page can succesfully be reached
        $response->assertStatus(200);

        // Assert if the view does not contain 'No products found' (So the view contains data)
        $response->assertDontSee('No products found');

        // Assert if view receives controller variable $productList and if the created test product is visible on the page
        $response->assertViewHas('productList', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    // Test to check if homepage contains no product data
    public function test_homepage_contains_empty_table(): void
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if the page contains 'No products found'
        $response->assertSee('No products found');
    }

    public function test_paginated_products_table_doesnt_contain_11th_record()
    {
        // Create 11 new products using the factory
        $products = Products::factory(11)->create();

        // Get the last product in the collection
        $lastProduct = $products->last();

        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if view receives controller variable $productList and if the last product in the collection is not visible on the page
        $response->assertViewHas('productList', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->admin)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if view receives controller variable $productList and if the last product in the collection is not visible on the page
        $response->assertSee('Add a new product');
    }

    public function test_non_admin_cannot_see_products_create_button()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if view receives controller variable $productList and if the last product in the collection is not visible on the page
        $response->assertDontSee('Add a new product');
    }

    public function test_admin_can_access_products_create_page()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->admin)->get('products/create');

        // Check if the page can successfully be reached
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_products_create_page()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/create');

        // Check if the page can successfully be reached
        $response->assertStatus(403);
    }

    public function test_create_product_successful()
    {
        // Create product
        $product = [
            'name' => 'Product 123',
            'price' => 1234
        ];

        // Insert product to create -> post route
        $response = $this->actingAs($this->admin)->post(route('products.store'), $product);

        // Assert that the product has been created and admin gets redirected to index page
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));

        // Assert if database has the newly created product
        $this->assertDatabaseHas('products', $product);

        // Get latest product from database
        $lastProduct = Products::latest()->first();

        // Check if latest product is the same as the newly created product
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    private function createUser(bool $isAdmin = false): User
    {
        // Create a new user
        return User::factory()->create([
            'is_admin' =>$isAdmin
        ]);
    }
}
