<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id,
        'name' => $faker->unique()->name,
        'product_variation_type_id' => factory(ProductVariationType::class)->create()->id,
    ];
});
