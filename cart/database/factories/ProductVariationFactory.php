<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\ProductVariation;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});
