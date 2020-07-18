<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartUpdateTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $response = $this->json('PATCH', 'api/cart/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_product_cant_be_found()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'PATCH', 'api/cart/1')
            ->assertStatus(404);
    }

    public function test_it_requires_a_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}")
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_numeric_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => 'one',
        ])->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_quantity_of_one_or_more()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => 0,
        ])->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_updates_the_quantity_of_a_product()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),
            [
                'quantity' => 1,
            ]
        );

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => $quantity = 5,
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }
}
