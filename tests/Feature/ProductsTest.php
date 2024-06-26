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

    // Test if pagination doesn't contain 11th record
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

    // Test admin can see products create button on index page
    public function test_admin_can_see_products_create_button()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->admin)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if view receives controller variable $productList and if the last product in the collection is not visible on the page
        $response->assertSee('Add a new product');
    }

    // Test non admin cannot see products create button on index page
    public function test_non_admin_cannot_see_products_create_button()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/index');

        // Check if the page can successfully be reached
        $response->assertStatus(200);

        // Assert if view receives controller variable $productList and if the last product in the collection is not visible on the page
        $response->assertDontSee('Add a new product');
    }

    // Test admin can access products create page
    public function test_admin_can_access_products_create_page()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->admin)->get('products/create');

        // Check if the page can successfully be reached
        $response->assertStatus(200);
    }

    // Test non admin cannot see products create page
    public function test_non_admin_cannot_access_products_create_page()
    {
        // Get the route for index acting as the newly created user
        $response = $this->actingAs($this->user)->get('products/create');

        // Check if the page can successfully be reached
        $response->assertStatus(403);
    }

    // Test successful creation of product
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

    // Test edit contains correct values
    public function test_product_edit_contains_correct_values()
    {
        $product = Products::factory()->create();

        $response = $this->actingAs($this->admin)->get('products/' . 'edit/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product', $product);
    }

    // Test update validation error
    public function test_product_update_validation_error_redirects_back_to_form()
    {
        $product = Products::factory()->create();

        $response = $this->actingAs($this->admin)->put('products/' . 'update/' . $product->id, [
            'name' => '',
            'price' => ''
        ]);

        $response->assertStatus(302);
        // $response->assertSessionHasErrors(['name']); Checks if session has errors. assertInvalid checks for any errors as well
        $response->assertInvalid(['name', 'price']);
    }

    // Test successful deletion of product
    public function test_product_delete_successful()
    {
        $product = Products::factory()->create();

        $response = $this->actingAs($this->admin)->delete('products/' . 'delete/' . $product->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        
        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
    }

    // Test API returns product list
    public function test_api_returns_products_list()
    {
        $product = Products::factory()->create();
        $response = $this->getJson('/api/products');

        $response->assertJson([$product->toArray()]);
    }

    // Test API successful store
    public function test_api_product_store_successful()
    {
        $product = [
            'name' => 'Product 1',
            'price' => 123
        ];

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(201);
        $response->assertJson($product);
    }

    // Test API unsuccessful store
    public function test_api_product_invalid_store_returns_error()
    {
        $product = [
            'name' => '',
            'price' => 123
        ];

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(422);
    }

    private function createUser(bool $isAdmin = false): User
    {
        // Create a new user
        return User::factory()->create([
            'is_admin' =>$isAdmin
        ]);
    }
}
