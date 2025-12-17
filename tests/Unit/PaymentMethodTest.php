<?php

namespace Tests\Unit;

use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_payment_method_can_be_created()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Test Payment',
            'code' => 'test_payment',
            'instructions' => 'Test instructions',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'Test Payment',
            'code' => 'test_payment',
        ]);
    }

    /** @test */
    public function test_payment_method_code_must_be_unique()
    {
        PaymentMethod::create([
            'name' => 'Test Payment 1',
            'code' => 'test_code',
            'is_active' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        PaymentMethod::create([
            'name' => 'Test Payment 2',
            'code' => 'test_code', // Duplicate code
            'is_active' => true,
        ]);
    }

    /** @test */
    public function test_active_scope_returns_only_active_payment_methods()
    {
        PaymentMethod::create([
            'name' => 'Active Method',
            'code' => 'active',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name' => 'Inactive Method',
            'code' => 'inactive',
            'is_active' => false,
        ]);

        $activeMethods = PaymentMethod::active()->get();

        $this->assertCount(1, $activeMethods);
        $this->assertEquals('Active Method', $activeMethods->first()->name);
    }

    /** @test */
    public function test_ordered_scope_returns_payment_methods_in_correct_order()
    {
        PaymentMethod::create([
            'name' => 'Third',
            'code' => 'third',
            'sort_order' => 3,
        ]);

        PaymentMethod::create([
            'name' => 'First',
            'code' => 'first',
            'sort_order' => 1,
        ]);

        PaymentMethod::create([
            'name' => 'Second',
            'code' => 'second',
            'sort_order' => 2,
        ]);

        $methods = PaymentMethod::ordered()->pluck('name')->toArray();

        $this->assertEquals(['First', 'Second', 'Third'], $methods);
    }

    /** @test */
    public function test_payment_method_has_relationship_with_payments()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Test Method',
            'code' => 'test_method',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'qualification' => 'Bueno',
        ]);

        $order = Order::create([
            'customer_id' => $customer->id,
            'date' => now(),
            'total' => 100,
            'status' => 'creada',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'date' => now(),
            'amount' => 100,
            'payment_method' => 'test_method',
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->assertCount(1, $paymentMethod->payments);
    }

    /** @test */
    public function test_payment_method_can_be_toggled_active()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Test Method',
            'code' => 'test',
            'is_active' => true,
        ]);

        $this->assertTrue($paymentMethod->is_active);

        $paymentMethod->is_active = false;
        $paymentMethod->save();

        $this->assertFalse($paymentMethod->fresh()->is_active);
    }
}
