<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Module\Order\Models\Ingredient;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredientsArr =[
            [
            'name' => 'Beef',
            'quantity' => 20 * 1000,
            'standard_quantity' => 10000,
            ],
            [
                'name' => 'Cheese',
                'quantity' => 5 * 1000,
                'standard_quantity' => 10000,
            ],
            [
                'name' => 'Onion',
                'quantity' => 1000,
                'standard_quantity' => 10000,
            ],
        ];
        foreach ($ingredientsArr as $key => $ingredientArr)
        Ingredient::firstOrCreate($ingredientArr);
    }
}
