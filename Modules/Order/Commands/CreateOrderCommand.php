<?php
declare(strict_types=1);

namespace Module\Order\Commands;

use App\Exceptions\AppException;
use Module\Order\Exceptions\InvalidOrderIdException;
use Module\Order\Exceptions\InvalidProductQuantity;
use Ramsey\Uuid\Uuid;

class CreateOrderCommand
{
    public function __construct(
        private array $productsArray,
    ){
        $this->validate();
    }

    private function validate()
    {
        foreach($this->productsArray as $product)
        {
            if(!Uuid::isValid($product['product_id'])){
                throw new InvalidOrderIdException();
            }
            if(!is_int((int) $product['quantity'])){
                throw new InvalidProductQuantity();
            }
        }
    }

    public function getProductsArray(): array
    {
        return $this->productsArray;
    }
}
