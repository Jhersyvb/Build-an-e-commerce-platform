<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductVariationTest extends TestCase
{
    public function test_it_has_one_variation_type()
    {
        $variaton = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $variaton->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $variaton = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $variaton->product);
    }
}
