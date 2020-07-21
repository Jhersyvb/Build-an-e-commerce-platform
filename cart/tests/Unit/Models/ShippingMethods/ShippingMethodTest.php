<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\Country;
use App\Models\ShippingMethod;

class ShippingMethodTest extends TestCase
{
    public function test_it_belongs_to_many_countries()
    {
        $shipping =  factory(ShippingMethod::class)->create();

        $shipping->countries()->attach(
            factory(Country::class)->create()
        );

        $this->assertInstanceOf(Country::class, $shipping->countries->first());
    }

    public function test_it_returns_a_money_instance_for_the_price()
    {
        $shipping =  factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $shipping =  factory(ShippingMethod::class)->create([
            'price' => 0,
        ]);

        $this->assertEquals($shipping->formattedPrice, "S/ 0.00");
    }
}
