<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use function Laravel\Prompts\text;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'price' => rand(100, 999)
        ];
    }
}
