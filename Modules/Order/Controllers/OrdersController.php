<?php
declare(strict_types=1);

namespace Module\Order\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Module\Order\Commands\CreateOrderHandler;
use Module\Order\Requests\CreateOrderRequest;
use Module\Order\Resources\OrderResource;

class OrdersController extends Controller
{
    public function __construct(private CreateOrderHandler $createOrderHandler)
    {
    }

    public function createOrder(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->createOrderHandler->create(
            $request->createCreateOrderCommand()
        );
        return response()->json(
            [
                'status' => 'success',
                'data' => [
                    'order' => new OrderResource($order),
                    ]
            ],
            201);
    }
}
