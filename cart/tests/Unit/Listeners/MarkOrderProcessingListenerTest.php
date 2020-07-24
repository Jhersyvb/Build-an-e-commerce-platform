<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Events\Order\OrderPaid;
use App\Listeners\Order\MarkOrderProcessing;

class MarkOrderProcessingListenerTest extends TestCase
{
    public function test_it_marks_order_as_processing()
    {
        $event = new OrderPaid(
            $order = factory(Order::class)->create([
                'user_id' => factory(User::class)->create(),
            ])
        );

        $listener = new MarkOrderProcessing();

        $listener->handle($event);

        $this->assertEquals($order->fresh()->status, Order::PROCESSING);
    }
}
