<?php
declare(strict_types=1);

namespace Module\Order\Commands;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\DB;
use Module\Order\Exceptions\InsufficientIngredientsException;
use Module\Order\Exceptions\InvalidOrderIdException;
use Module\Order\Jobs\NotifyDegradedIngredients;
use Module\Order\Repositories\IngredientsRepository;
use Module\Order\Repositories\OrderRepository;
use Module\Order\Repositories\ProductsRepository;

class CreateOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductsRepository $productsRepository,
        private IngredientsRepository $ingredientsRepository,
    ){
    }

    public function create(CreateOrderCommand $command)
    {
        $orderIngredients = $this->getOrderIngredients($command->getProductsArray());
        $order = null;
        DB::transaction(function () use ($command, $orderIngredients, &$order) {
            if(!$this->ingredientsRepository->isOrderIngredientsExist($orderIngredients)){
                throw new InsufficientIngredientsException();
            }

            $order = $this->orderRepository->saveOrder($command->getProductsArray());

            $this->ingredientsRepository->updateIngredientsQuantity($orderIngredients);
        }, 3);


        $downStandardIngredients = $this->ingredientsRepository->checkIngredientsDownStandard(
            $orderIngredients
        );

        if(!empty($downStandardIngredients)){
            dispatch((new NotifyDegradedIngredients($downStandardIngredients)));
        }
        return $order;
    }

    public function getOrderIngredients(array $productsArray): array
    {
        $productsIds = array_column($productsArray, 'product_id');

        $productsWithIngredients = $this->productsRepository
            ->getProductsWithIngredientsByIds($productsIds)
            ->keyBy('id');

        if(count($productsWithIngredients) !== count($productsArray)){
            throw new InvalidOrderIdException();
        }

        $ingredientsArray = [];
        foreach($productsArray as $orderProduct)
        {
            foreach($productsWithIngredients[$orderProduct['product_id']]->ingredients as $ingredient)
            {
                if(!isset($ingredientsArray[$ingredient->id]))
                {
                    $ingredientsArray[$ingredient->id]['name'] = $ingredient->name;
                    $ingredientsArray[$ingredient->id]['quantity'] = $ingredient->pivot->quantity * $orderProduct['quantity'];
                }else{
                    $ingredientsArray[$ingredient->id]['quantity'] += $ingredient->pivot->quantity * $orderProduct['quantity'];
                }
            }
        }

        return $ingredientsArray;
    }


}
