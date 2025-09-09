<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderCancellationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_order_can_be_cancelled_when_created()
    {
        $order = Order::create([
            'status' => Order::STATUS_CREATED,
            'total' => 100.00,
            'subtotal' => 100.00,
            'date' => now(),
        ]);

        $this->assertTrue($order->canBeCancelled());
        $this->assertTrue($order->cancel());
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }

    /** @test */
    public function test_order_can_be_cancelled_when_paid()
    {
        $order = Order::create([
            'status' => Order::STATUS_PAID,
            'total' => 100.00,
            'subtotal' => 100.00,
            'date' => now(),
        ]);

        $this->assertTrue($order->canBeCancelled());
        $this->assertTrue($order->cancel());
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }

    /** @test */
    public function test_order_cannot_be_cancelled_when_shipped()
    {
        $order = Order::create([
            'status' => Order::STATUS_SHIPPED,
            'total' => 100.00,
            'subtotal' => 100.00,
            'date' => now(),
        ]);

        $this->assertFalse($order->canBeCancelled());
        $this->assertFalse($order->cancel());
        $this->assertEquals(Order::STATUS_SHIPPED, $order->status);
    }

    /** @test */
    public function test_order_cannot_be_cancelled_when_completed()
    {
        $order = Order::create([
            'status' => Order::STATUS_COMPLETED,
            'total' => 100.00,
            'subtotal' => 100.00,
            'date' => now(),
        ]);

        $this->assertFalse($order->canBeCancelled());
        $this->assertFalse($order->cancel());
        $this->assertEquals(Order::STATUS_COMPLETED, $order->status);
    }

    /** @test */
    public function test_order_cannot_be_cancelled_when_already_cancelled()
    {
        $order = Order::create([
            'status' => Order::STATUS_CANCELLED,
            'total' => 100.00,
            'subtotal' => 100.00,
            'date' => now(),
        ]);

        $this->assertFalse($order->canBeCancelled());
        $this->assertFalse($order->cancel());
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }
}