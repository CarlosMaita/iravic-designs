<?php

namespace Tests\Feature\Ecommerce;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerPaymentReportingTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a customer
        $this->customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create an order
        $this->order = Order::create([
            'customer_id' => $this->customer->id,
            'date' => now(),
            'total' => 100.00,
            'status' => Order::STATUS_CREATED,
        ]);

        // Create active payment methods
        PaymentMethod::create([
            'name' => 'Pago Móvil',
            'code' => 'pago_movil',
            'instructions' => 'Realizar pago móvil a 0414-1234567',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        PaymentMethod::create([
            'name' => 'Binance',
            'code' => 'binance',
            'instructions' => 'Enviar pago a usuario@binance.com',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        PaymentMethod::create([
            'name' => 'Inactive Method',
            'code' => 'inactive',
            'instructions' => 'This should not be available',
            'is_active' => false,
            'sort_order' => 3,
        ]);
    }

    /** @test */
    public function test_customer_can_view_order_with_payment_button()
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('customer.orders.show', $this->order));

        $response->assertStatus(200);
        $response->assertSee('Realizar Pago');
    }

    /** @test */
    public function test_api_returns_only_active_payment_methods()
    {
        $response = $this->getJson('/api/payment-methods/active');

        $response->assertStatus(200);
        $response->assertJsonCount(2); // Only 2 active methods

        $data = $response->json();
        $codes = array_column($data, 'code');
        
        $this->assertContains('pago_movil', $codes);
        $this->assertContains('binance', $codes);
        $this->assertNotContains('inactive', $codes);
    }

    /** @test */
    public function test_customer_can_report_payment_with_active_method()
    {
        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'pago_movil',
            'reference_number' => '1234567890',
            'date' => now()->toIso8601String(),
            'mobile_payment_date' => now()->toIso8601String(),
            'comment' => 'Test payment',
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $this->order->id,
            'customer_id' => $this->customer->id,
            'amount' => 100.00,
            'payment_method' => 'pago_movil',
            'status' => Payment::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function test_customer_cannot_report_payment_with_inactive_method()
    {
        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'inactive', // Inactive method
            'date' => now()->toIso8601String(),
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors('payment_method');
    }

    /** @test */
    public function test_customer_cannot_report_payment_with_non_existent_method()
    {
        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'non_existent_method',
            'date' => now()->toIso8601String(),
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors('payment_method');
    }

    /** @test */
    public function test_customer_must_provide_reference_for_pago_movil()
    {
        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'pago_movil',
            'date' => now()->toIso8601String(),
            // Missing reference_number
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reference_number');
    }

    /** @test */
    public function test_customer_must_provide_mobile_payment_date_for_pago_movil()
    {
        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'pago_movil',
            'reference_number' => '1234567890',
            'date' => now()->toIso8601String(),
            // Missing mobile_payment_date
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('mobile_payment_date');
    }

    /** @test */
    public function test_customer_cannot_report_payment_for_another_customers_order()
    {
        $otherCustomer = Customer::create([
            'name' => 'Other Customer',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($otherCustomer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'pago_movil',
            'reference_number' => '1234567890',
            'date' => now()->toIso8601String(),
            'mobile_payment_date' => now()->toIso8601String(),
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function test_payment_method_instructions_are_included_in_api_response()
    {
        $response = $this->getJson('/api/payment-methods/active');

        $response->assertStatus(200);
        
        $data = $response->json();
        $pagoMovil = collect($data)->firstWhere('code', 'pago_movil');
        
        $this->assertNotNull($pagoMovil);
        $this->assertEquals('Realizar pago móvil a 0414-1234567', $pagoMovil['instructions']);
    }

    /** @test */
    public function test_error_when_no_active_payment_methods_available()
    {
        // Deactivate all payment methods
        PaymentMethod::query()->update(['is_active' => false]);

        $this->actingAs($this->customer, 'customer');

        $paymentData = [
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'pago_movil',
            'reference_number' => '1234567890',
            'date' => now()->toIso8601String(),
            'mobile_payment_date' => now()->toIso8601String(),
        ];

        $response = $this->postJson(
            route('customer.orders.add_payment', $this->order),
            $paymentData
        );

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'No hay métodos de pago disponibles en este momento.',
        ]);
    }
}
