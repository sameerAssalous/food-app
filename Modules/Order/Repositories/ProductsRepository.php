<?php
declare(strict_types=1);

namespace Module\Order\Repositories;

use Module\Order\Models\Product;

class ProductsRepository
{
    public function getProductsWithIngredientsByIds(array $productsIds)
    {
        return Product::with('ingredients')->whereIn('id', $productsIds)->get();
    }
}
