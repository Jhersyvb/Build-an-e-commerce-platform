<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\ShippingMethod;

$factory->define(ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => 'Royal Mail',
        'price' => 1000,
    ];
});
