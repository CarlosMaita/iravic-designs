<?php

namespace Tests\Feature\Ecommerce;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Customer Dashboard functionality.
 */
class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that dashboard counts only non-archived orders.
     *
     * @test
     */
    public function dashboard_counts_only_non_archived_orders()
    {
        // Create a customer
        $customer = Customer::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
        ]);

        // Create 2 non-archived orders
        Order::factory()->count(2)->create([
            'customer_id' => $customer->id,
            'archived' => false,
        ]);

        // Create 9 archived orders
        Order::factory()->count(9)->create([
            'customer_id' => $customer->id,
            'archived' => true,
        ]);

        // Authenticate as customer
        $this->actingAs($customer, 'customer');

        // Visit dashboard
        $response = $this->get(route('customer.dashboard'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert the ordersCount variable passed to view is 2 (not 11)
        $response->assertViewHas('ordersCount', 2);

        // Assert total orders in database is 11
        $this->assertEquals(11, $customer->orders()->count());
        
        // Assert non-archived orders count is 2
        $this->assertEquals(2, $customer->orders()->notArchived()->count());
    }

    /**
     * Test that dashboard shows correct count when all orders are archived.
     *
     * @test
     */
    public function dashboard_shows_zero_when_all_orders_are_archived()
    {
        // Create a customer
        $customer = Customer::factory()->create([
            'username' => 'testuser2',
            'password' => bcrypt('password'),
        ]);

        // Create 5 archived orders
        Order::factory()->count(5)->create([
            'customer_id' => $customer->id,
            'archived' => true,
        ]);

        // Authenticate as customer
        $this->actingAs($customer, 'customer');

        // Visit dashboard
        $response = $this->get(route('customer.dashboard'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert the ordersCount is 0
        $response->assertViewHas('ordersCount', 0);
    }

    /**
     * Test that dashboard shows correct count when customer has no orders.
     *
     * @test
     */
    public function dashboard_shows_zero_when_customer_has_no_orders()
    {
        // Create a customer
        $customer = Customer::factory()->create([
            'username' => 'testuser3',
            'password' => bcrypt('password'),
        ]);

        // Authenticate as customer
        $this->actingAs($customer, 'customer');

        // Visit dashboard
        $response = $this->get(route('customer.dashboard'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert the ordersCount is 0
        $response->assertViewHas('ordersCount', 0);
    }
}
