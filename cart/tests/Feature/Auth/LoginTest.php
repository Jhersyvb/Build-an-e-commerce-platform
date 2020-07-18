<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/auth/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_requires_a_validation_error_if_credentials_dont_match()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'nope',
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_it_returns_a_token_if_credentials_do_match()
    {
        $user = factory(User::class)->create([
            'password' => 'sara',
        ]);

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'sara',
        ])->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
    }

    public function test_it_returns_a_user_if_credentials_do_match()
    {
        $user = factory(User::class)->create([
            'password' => 'sara',
        ]);

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'sara',
        ])->assertJsonFragment([
            'email' => $user->email,
        ]);
    }
}
