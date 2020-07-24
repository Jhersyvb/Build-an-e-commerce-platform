<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\Address;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;
use App\Events\Order\OrderCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStoreTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('POST', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_requires_an_address()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders')
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_exists()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => 1,
        ])->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
        ])->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_a_shipping_method()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders')
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_that_exists()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders', [
            'shipping_method_id' => 1,
        ])->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_valid_for_the_given_address()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $this->jsonAs($user, 'POST', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id,
        ])->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_can_create_an_order()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping, $payment) = $this->orderDependencies($user);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);
    }

    public function test_it_attaches_the_products_to_the_order()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping, $payment) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $this->assertDatabaseHas('order_product_variation', [
            'product_variation_id' => $product->id,
            'order_id' => json_decode($response->getContent())->data->id,
        ]);
    }

    public function test_it_fails_to_create_order_if_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync([
            ($product = $this->productWithStock())->id =>
            [
                'quantity' => 0,
            ]
        ]);

        list($address, $shipping, $payment) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ])->assertStatus(400);
    }

    public function test_it_fires_an_order_created_event()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping, $payment) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        Event::assertDispatched(OrderCreated::class, function ($event) use ($response) {
            return $event->order->id === json_decode($response->getContent())->data->id;
        });
    }

    public function test_it_empties_the_cart_when_ordering()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping, $payment) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,
        ]);

        $this->assertEmpty($user->cart);
    }

    protected function productWithStock()
    {
        $product = factory(ProductVariation::class)->create();

        factory(Stock::class)->create([
            'product_variation_id' => $product->id,
        ]);

        return $product;
    }

    protected function orderDependencies(User $user)
    {
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $user->email,
        ]);

        $user->update([
            'gateway_customer_id' => $stripeCustomer->id,
        ]);

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach($address->country);

        $payment = factory(PaymentMethod::class)->create([
            'user_id' => $user->id,
        ]);

        return [$address, $shipping, $payment];
    }
}
