<?php

namespace App\Listeners\Order;

use App\Cart\Cart;
use App\Cart\Payments\Gateway;
use App\Events\Order\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayment implements ShouldQueue
{
    protected $gateway;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        $this->gateway->withUser($order->user)
            ->getCustomer()
            ->charge(
                $order->paymentMethod,
                $order->total()->amount()
            );
    }
}
