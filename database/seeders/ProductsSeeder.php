<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Order\Models\Ingredient;
use Module\Order\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IngredientsSeeder::class);

        $beef = Ingredient::where('name', 'Beef')->firstOrFail();
        $cheese = Ingredient::where('name', 'Cheese')->firstOrFail();
        $onion = Ingredient::where('name', 'Onion')->firstOrFail();

        $burgerProduct = Product::firstOrCreate(
            [
                'name' => 'Burger',
            ]
        );

        $burgerProduct->ingredients()->attach(
            [
                   $beef->id => [
                        'quantity' => 150,
                    ]
                ,

                    $cheese->id => [
                        'quantity' => 30,
                    ]
                ,

                    $onion->id => [
                        'quantity' => 20,
                    ]
                ,
            ]
        );

        $nuggetsProduct = Product::firstOrCreate(
            [
                'name' => 'Nuggets',
            ]
        );
        $nuggetsProduct->ingredients()->attach(
            [

                    $beef->id => [
                        'quantity' => 200,
                    ]
                ,

                    $cheese->id => [
                        'quantity' => 20,
                    ]
                ,

                    $onion->id => [
                        'quantity' => 15,
                    ]
                ,
            ]
        );
    }
}
