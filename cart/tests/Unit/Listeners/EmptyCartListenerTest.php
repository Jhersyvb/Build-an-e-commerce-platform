<?php

namespace Tests\Unit\Listeners;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use App\Listeners\Order\EmptyCart;

class EmptyCartListenerTest extends TestCase
{
    public function test_it_should_clear_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create()
        );

        $listener = new EmptyCart($cart);

        $listener->handle();

        $this->assertEmpty($user->cart);
    }
}
