<?php

namespace Database\Factories\Module\Order\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Module\Order\Models\Order;
use Module\Order\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name' =>  json_encode(['ar' => $this->faker->sentence()]),
            'is_active' => 1,
        ];
    }
}
