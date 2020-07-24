<?php

namespace App\Listeners\Order;

use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkOrderPaymentFailed
{
    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $event->order->update([
            'status' => Order::PAYMENT_FAILED,
        ]);
    }
}
