<?php
declare(strict_types=1);

namespace Module\Order\Repositories;

use Illuminate\Support\Facades\DB;
use Module\Order\Models\Ingredient;

class IngredientsRepository
{
    public function isOrderIngredientsExist(array $ingredientsArray)
    {
        foreach ($ingredientsArray as $ingredientId => $ingredientDetails)
        {
            if(!Ingredient::where('id', $ingredientId)
                ->whereRaw('quantity - ? >= 0', [$ingredientDetails['quantity']])
                ->exists()
            ){
                return false;
            }
        }
        return true;
    }

    public function updateIngredientsQuantity(array $ingredientsArray)
    {
        foreach ($ingredientsArray as $ingredientId => $ingredientDetails)
        {
            $quantity = (int) $ingredientDetails['quantity'];
            Ingredient::where('id', $ingredientId)->update(
                [
                    'quantity' => DB::raw('quantity - ' . $quantity)
                ]
            );
        }
    }

    public function checkIngredientsDownStandard(array $ingredientsArray): array
    {
        $ingredientDownStandard = [];
        foreach ($ingredientsArray as $ingredientId => $ingredientDetails)
        {
            if(Ingredient::where('id', $ingredientId)
                ->whereRaw('quantity <= standard_quantity')
                ->whereRaw('quantity + ? >= standard_quantity',[$ingredientDetails['quantity']])
                ->exists()
            ){
                $ingredientDownStandard[$ingredientId] = ['name' => $ingredientDetails['name']] ;
            }
        }
        return $ingredientDownStandard;
    }
}
