<?php

namespace Database\Factories\Module\Order\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Module\Order\Models\Ingredient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name' =>  $this->faker->sentence(),
            'quantity' =>  $this->faker->numberBetween(1000, 10000),
            'standard_quantity' => $this->faker->numberBetween(1000, 10000),
        ];
    }
}
