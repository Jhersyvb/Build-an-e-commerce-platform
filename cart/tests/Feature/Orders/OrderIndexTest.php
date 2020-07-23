<?php

namespace Tests\Feature\Orders;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderIndexTest extends TestCase
{
    public function test_it_fails_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_orders()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id' => $user->id,
        ]);

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertJsonFragment([
                'id' => $order->id,
            ]);
    }

    public function test_it_orders_by_the_latest_first()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id' => $user->id,
        ]);

        $anotherOrder = factory(Order::class)->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay(),
        ]);

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertSeeInOrder([
                $order->created_at->toDateTimeString(),
                $anotherOrder->created_at->toDateTimeString(),
            ]);
    }

    public function test_it_has_pagination()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertJsonStructure([
                'links',
                'meta',
            ]);
    }

    public function test_it_returns_a_money_instance_for_the_subtotal()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(Money::class, $order->subtotal);
    }

    public function test_it_returns_a_money_instance_for_the_total()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(Money::class, $order->total());
    }

    public function test_it_adds_shipping_onto_the_total()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
            'subtotal' => 1000,
            'shipping_method_id' => factory(ShippingMethod::class)->create([
                'price' => 1000,
            ]),
        ]);

        $this->assertEquals($order->total()->amount(), 2000);
    }
}
