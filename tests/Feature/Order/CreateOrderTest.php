<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Module\Order\Commands\CreateOrderCommand;
use Module\Order\Commands\CreateOrderHandler;
use Module\Order\Exceptions\InsufficientIngredientsException;
use Module\Order\Exceptions\InvalidOrderIdException;
use Module\Order\Jobs\NotifyDegradedIngredients;
use Module\Order\Models\Ingredient;
use Module\Order\Models\Product;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use WithFaker;
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private Ingredient $ingredient1;
    private Ingredient $ingredient2;

    private Product $product1;
    private Product $product2;
    public function setUp(): void
    {

        parent::setUp();
        $this->ingredient1 = Ingredient::factory()->create();
        $this->ingredient2 = Ingredient::factory()->create();

        $this->product1 = Product::factory()->create();
        $this->product1->ingredients()->attach($this->ingredient1->id, ['quantity' => 10,]);
        $this->product1->ingredients()->attach($this->ingredient2->id, ['quantity' => 15,]);

        $this->product2 = Product::factory()->create();
        $this->product2->ingredients()->attach($this->ingredient1->id, ['quantity' => 10,]);
        $this->product2->ingredients()->attach($this->ingredient2->id, ['quantity' => 15,]);

    }
    public function test_basic_test()
    {
        $this->assertTrue(true);
    }

    public function testCreateSimpleOrder(){
        //Arrange
        $command = new CreateOrderCommand(
            [
                [
                    'product_id' => $this->product1->id,
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
                [
                    'product_id' => $this->product2->id,
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
            ]
        );
        $createOrderHandler = app()->make(CreateOrderHandler::class);
        //Act
        $order = $createOrderHandler->create($command);
        //Assert
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertDatabaseHas('order_products', [
            'order_id' => $order->id,
            'product_id' => $this->product1->id
        ]);
    }

    public function testCreateOrderWithInvalidOrderId(){
        //Assert
        $this->expectException(InvalidOrderIdException::class);

        //Arrange
        $command = new CreateOrderCommand(
            [
                [
                    'product_id' => $this->faker->uuid(),
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
                [
                    'product_id' => $this->product2->id,
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
            ]
        );
        $createOrderHandler = app()->make(CreateOrderHandler::class);
        //Act
        $order = $createOrderHandler->create($command);

    }

    public function testCreateOrderInsufficientIngredients(){
        //Assert
        $this->expectException(InsufficientIngredientsException::class);

        $ingredient3 = Ingredient::factory()->create(['quantity' => 10]);
        $this->product1->ingredients()->attach($ingredient3->id, ['quantity' => 1,]);

        //Arrange
        $command = new CreateOrderCommand(
            [
                [
                    'product_id' => $this->product1->id,
                    'quantity' => 11,
                ],
                [
                    'product_id' => $this->product2->id,
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
            ]
        );
        $createOrderHandler = app()->make(CreateOrderHandler::class);
        //Act
        $order = $createOrderHandler->create($command);
    }

    public function testCreateOrderNotifyDegradedIngredients(){
        //Assert
        $this->expectsJobs(NotifyDegradedIngredients::class);

        $ingredient3 = Ingredient::factory()->create(
            ['quantity' => 10 , 'standard_quantity'=> 9]
        );
        $this->product1->ingredients()->attach($ingredient3->id, ['quantity' => 1,]);

        //Arrange
        $command = new CreateOrderCommand(
            [
                [
                    'product_id' => $this->product1->id,
                    'quantity' => 2,
                ],
                [
                    'product_id' => $this->product2->id,
                    'quantity' => $this->faker->numberBetween(1,5),
                ],
            ]
        );
        $createOrderHandler = app()->make(CreateOrderHandler::class);
        //Act
        $order = $createOrderHandler->create($command);
    }
}
