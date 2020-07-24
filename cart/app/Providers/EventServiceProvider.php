<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\Order\OrderCreated' => [
            'App\Listeners\Order\ProcessPayment',
            'App\Listeners\Order\EmptyCart',
        ],
        'App\Events\Order\OrderPaymentFailed' => [
            'App\Listeners\Order\MarkOrderPaymentFailed',
        ],
        'App\Events\Order\OrderPaid' => [
            'App\Listeners\Order\CreateTransaction',
            'App\Listeners\Order\MarkOrderProcessing',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
