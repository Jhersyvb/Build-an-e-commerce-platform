<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Events\Order\OrderPaymentFailed;
use App\Listeners\Order\MarkOrderPaymentFailed;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    public function test_it_marks_order_as_payment_failed()
    {
        $event = new OrderPaymentFailed(
            $order = factory(Order::class)->create([
                'user_id' => factory(User::class)->create(),
            ])
        );

        $listener = new MarkOrderPaymentFailed();

        $listener->handle($event);

        $this->assertEquals($order->fresh()->status, Order::PAYMENT_FAILED);
    }
}
