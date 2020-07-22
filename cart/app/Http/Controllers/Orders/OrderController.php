<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;

class OrderController extends Controller
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->middleware(['auth:api']);

        $this->cart = $cart;
    }

    public function store(OrderStoreRequest $request)
    {
        $order = $this->createOrder($request);
    }

    protected function createOrder(Request $request)
    {
        $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id']), [
                'subtotal' => $this->cart->subtotal()->amount(),
            ])
        );
    }
}
