<?php

namespace Tests\Unit\Models\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\ShippingMethod;

class OrderTest extends TestCase
{
    public function test_it_has_a_default_status_of_pending()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertEquals($order->status, Order::PENDING);
    }

    public function test_it_belongs_to_user()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belongs_to_an_address()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_it_belongs_to_a_shipping_method()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }
}
