<?php

namespace Tests\Unit;

use App\Models\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /** @test */
    public function test_notification_has_correct_type_constants()
    {
        $this->assertEquals('welcome', Notification::TYPE_WELCOME);
        $this->assertEquals('order_created', Notification::TYPE_ORDER_CREATED);
        $this->assertEquals('payment_submitted', Notification::TYPE_PAYMENT_SUBMITTED);
        $this->assertEquals('payment_confirmed', Notification::TYPE_PAYMENT_CONFIRMED);
        $this->assertEquals('shipped', Notification::TYPE_SHIPPED);
        $this->assertEquals('review_request', Notification::TYPE_REVIEW_REQUEST);
    }

    /** @test */
    public function test_notification_fillable_fields_are_correct()
    {
        $notification = new Notification();
        $fillable = $notification->getFillable();

        $this->assertContains('customer_id', $fillable);
        $this->assertContains('type', $fillable);
        $this->assertContains('title', $fillable);
        $this->assertContains('message', $fillable);
        $this->assertContains('action_url', $fillable);
        $this->assertContains('is_read', $fillable);
        $this->assertContains('read_at', $fillable);
    }

    /** @test */
    public function test_notification_casts_are_correct()
    {
        $notification = new Notification();
        $casts = $notification->getCasts();

        $this->assertEquals('boolean', $casts['is_read']);
        $this->assertEquals('datetime', $casts['read_at']);
    }
}
