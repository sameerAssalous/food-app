<?php
declare(strict_types=1);

namespace Module\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Module\Order\Commands\CreateOrderCommand;
use Ramsey\Uuid\Uuid;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products' => 'required|array',
            'products.*.product_id'=> 'string|required|exists:products,id',
            'products.*.quantity'=> 'integer|required|min:1',
        ];
    }

    public function createCreateOrderCommand(): CreateOrderCommand
    {
        return new CreateOrderCommand(
            $this->products
        );
    }
}
