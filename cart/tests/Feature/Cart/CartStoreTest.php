<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartStoreTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', 'api/cart')
            ->assertStatus(401);
    }

    public function test_it_requires_products()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart')
            ->assertJsonValidationErrors(['products']);
    }

    public function test_it_requires_products_to_be_an_array()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => 1,
        ])->assertJsonValidationErrors(['products']);
    }

    public function test_it_requires_products_to_have_an_id()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity' => 1],
            ],
        ])->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_to_exists()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 1],
            ],
        ])->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_quantity_to_be_numeric()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 'one'],
            ],
        ])->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_requires_products_quantity_to_be_at_least_one()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 0],
            ],
        ])->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_requires_products_quantity_to_the_users_cart()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
