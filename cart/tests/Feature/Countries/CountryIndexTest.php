<?php

namespace Tests\Feature\Countries;

use Tests\TestCase;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryIndexTest extends TestCase
{
    public function test_it_returns_countries()
    {
        $country = factory(Country::class)->create();

        $this->json('GET', 'api/countries')
            ->assertJsonFragment([
                'id' => $country->id,
            ]);
    }
}
