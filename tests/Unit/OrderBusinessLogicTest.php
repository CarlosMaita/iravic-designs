<?php

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;

/**
 * Test Order model business logic.
 * These tests verify order status transitions and business rules.
 */
class OrderBusinessLogicTest extends TestCase
{
    /**
     * Test order status constants are defined.
     *
     * @test
     */
    public function order_status_constants_are_defined()
    {
        $this->assertEquals('creada', Order::STATUS_CREATED);
        $this->assertEquals('pagada', Order::STATUS_PAID);
        $this->assertEquals('enviada', Order::STATUS_SHIPPED);
        $this->assertEquals('completada', Order::STATUS_COMPLETED);
        $this->assertEquals('cancelada', Order::STATUS_CANCELLED);
    }

    /**
     * Test getStatuses returns all available statuses.
     *
     * @test
     */
    public function get_statuses_returns_all_available_statuses()
    {
        $statuses = Order::getStatuses();

        $this->assertIsArray($statuses);
        $this->assertArrayHasKey(Order::STATUS_CREATED, $statuses);
        $this->assertArrayHasKey(Order::STATUS_PAID, $statuses);
        $this->assertArrayHasKey(Order::STATUS_SHIPPED, $statuses);
        $this->assertArrayHasKey(Order::STATUS_COMPLETED, $statuses);
        $this->assertArrayHasKey(Order::STATUS_CANCELLED, $statuses);
    }

    /**
     * Test getShippingAgencies returns array of agencies.
     *
     * @test
     */
    public function get_shipping_agencies_returns_array()
    {
        $agencies = Order::getShippingAgencies();

        $this->assertIsArray($agencies);
        $this->assertContains('MRW', $agencies);
        $this->assertContains('ZOOM', $agencies);
        $this->assertContains('Domesa', $agencies);
    }

    /**
     * Test that fillable attributes are properly defined.
     *
     * @test
     */
    public function fillable_attributes_are_defined()
    {
        $order = new Order();
        $fillable = $order->getFillable();

        $this->assertContains('customer_id', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('total', $fillable);
        $this->assertContains('subtotal', $fillable);
        $this->assertContains('shipping_name', $fillable);
        $this->assertContains('shipping_address', $fillable);
    }

    /**
     * Test canBePaid logic.
     *
     * @test
     */
    public function order_can_be_paid_only_when_created()
    {
        $order = new Order(['status' => Order::STATUS_CREATED]);
        $this->assertTrue($order->canBePaid());

        $order = new Order(['status' => Order::STATUS_PAID]);
        $this->assertFalse($order->canBePaid());

        $order = new Order(['status' => Order::STATUS_SHIPPED]);
        $this->assertFalse($order->canBePaid());
    }

    /**
     * Test canBeShipped logic.
     *
     * @test
     */
    public function order_can_be_shipped_only_when_paid()
    {
        $order = new Order(['status' => Order::STATUS_CREATED]);
        $this->assertFalse($order->canBeShipped());

        $order = new Order(['status' => Order::STATUS_PAID]);
        $this->assertTrue($order->canBeShipped());

        $order = new Order(['status' => Order::STATUS_SHIPPED]);
        $this->assertFalse($order->canBeShipped());
    }

    /**
     * Test canBeCompleted logic.
     *
     * @test
     */
    public function order_can_be_completed_only_when_shipped()
    {
        $order = new Order(['status' => Order::STATUS_PAID]);
        $this->assertFalse($order->canBeCompleted());

        $order = new Order(['status' => Order::STATUS_SHIPPED]);
        $this->assertTrue($order->canBeCompleted());

        $order = new Order(['status' => Order::STATUS_COMPLETED]);
        $this->assertFalse($order->canBeCompleted());
    }

    /**
     * Test canBeCancelled logic.
     *
     * @test
     */
    public function order_can_be_cancelled_only_when_created()
    {
        $order = new Order(['status' => Order::STATUS_CREATED]);
        $this->assertTrue($order->canBeCancelled());

        $order = new Order(['status' => Order::STATUS_PAID]);
        $this->assertFalse($order->canBeCancelled());

        $order = new Order(['status' => Order::STATUS_SHIPPED]);
        $this->assertFalse($order->canBeCancelled());

        $order = new Order(['status' => Order::STATUS_COMPLETED]);
        $this->assertFalse($order->canBeCancelled());

        $order = new Order(['status' => Order::STATUS_CANCELLED]);
        $this->assertFalse($order->canBeCancelled());
    }
}
