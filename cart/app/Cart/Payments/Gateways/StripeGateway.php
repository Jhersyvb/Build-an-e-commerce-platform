<?php

namespace App\Cart\Payments\Gateways;

use App\Models\User;
use App\Cart\Payments\Gateway;
use App\Cart\Payments\Gateways\StripeGatewayCustomer;

class StripeGateway implements Gateway
{
    protected $user;

    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function createCustomer()
    {
        return new StripeGatewayCustomer();
    }
}
