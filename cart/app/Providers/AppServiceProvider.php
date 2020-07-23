<?php

namespace App\Providers;

use App\Cart\Cart;
use Stripe\Stripe;
use App\Cart\Payments\Gateway;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Cart\Payments\Gateways\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            if ($app->auth->user()) {
                $app->auth->user()->load([
                    'cart.stock',
                ]);
            }

            return new Cart($app->auth->user());
        });

        $this->app->singleton(Gateway::class, function () {
            return new StripeGateway();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Stripe::setApiKey(config('services.stripe.secret'));
    }
}
