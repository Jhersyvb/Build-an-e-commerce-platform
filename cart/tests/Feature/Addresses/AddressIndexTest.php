<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressIndexTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('GET', 'api/addresses')
            ->assertStatus(401);
    }

    public function test_it_shows_addresses()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $this->jsonAs($user, 'GET', 'api/addresses')
            ->assertJsonFragment([
                'id' => $address->id,
            ]);
    }
}
