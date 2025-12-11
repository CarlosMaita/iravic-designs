<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_notification_can_be_created_for_customer()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $notification = Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => 'Welcome',
            'message' => 'Welcome to our store',
            'action_url' => 'https://example.com',
        ]);

        $this->assertDatabaseHas('notifications', [
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => 'Welcome',
        ]);
    }

    /** @test */
    public function test_notification_can_be_marked_as_read()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $notification = Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => 'Welcome',
            'message' => 'Welcome to our store',
            'is_read' => false,
        ]);

        $this->assertFalse($notification->is_read);

        $notification->markAsRead();

        $this->assertTrue($notification->fresh()->is_read);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    /** @test */
    public function test_customer_can_have_multiple_notifications()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => 'Welcome',
            'message' => 'Welcome to our store',
        ]);

        Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_ORDER_CREATED,
            'title' => 'Order Created',
            'message' => 'Your order has been created',
        ]);

        $this->assertCount(2, $customer->notifications);
    }

    /** @test */
    public function test_unread_scope_filters_unread_notifications()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $notification1 = Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => 'Welcome',
            'message' => 'Welcome to our store',
            'is_read' => false,
        ]);

        $notification2 = Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_ORDER_CREATED,
            'title' => 'Order Created',
            'message' => 'Your order has been created',
            'is_read' => true,
        ]);

        $unreadCount = Notification::where('customer_id', $customer->id)->unread()->count();
        
        $this->assertEquals(1, $unreadCount);
    }
}
