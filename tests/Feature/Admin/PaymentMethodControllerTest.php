<?php

namespace Tests\Feature\Admin;

use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->admin = User::factory()->create();
    }

    /** @test */
    public function test_admin_can_view_payment_methods_index()
    {
        $this->actingAs($this->admin);

        PaymentMethod::create([
            'name' => 'Test Method',
            'code' => 'test_method',
            'is_active' => true,
        ]);

        $response = $this->get(route('admin.payment-methods.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Method');
    }

    /** @test */
    public function test_admin_can_view_create_payment_method_form()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.payment-methods.create'));

        $response->assertStatus(200);
        $response->assertSee('Crear Método de Pago');
    }

    /** @test */
    public function test_admin_can_create_payment_method()
    {
        $this->actingAs($this->admin);

        $data = [
            'name' => 'New Payment Method',
            'code' => 'new_payment',
            'instructions' => 'Test instructions for new payment method',
            'is_active' => true,
            'sort_order' => 1,
        ];

        $response = $this->post(route('admin.payment-methods.store'), $data);

        $response->assertRedirect(route('admin.payment-methods.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'New Payment Method',
            'code' => 'new_payment',
        ]);
    }

    /** @test */
    public function test_admin_cannot_create_payment_method_with_duplicate_code()
    {
        $this->actingAs($this->admin);

        PaymentMethod::create([
            'name' => 'Existing Method',
            'code' => 'existing_code',
            'is_active' => true,
        ]);

        $data = [
            'name' => 'New Method',
            'code' => 'existing_code', // Duplicate code
            'is_active' => true,
        ];

        $response = $this->post(route('admin.payment-methods.store'), $data);

        $response->assertSessionHasErrors('code');
    }

    /** @test */
    public function test_admin_can_view_edit_payment_method_form()
    {
        $this->actingAs($this->admin);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Test Method',
            'code' => 'test_method',
            'is_active' => true,
        ]);

        $response = $this->get(route('admin.payment-methods.edit', $paymentMethod));

        $response->assertStatus(200);
        $response->assertSee('Editar Método de Pago');
        $response->assertSee($paymentMethod->name);
    }

    /** @test */
    public function test_admin_can_update_payment_method()
    {
        $this->actingAs($this->admin);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Original Name',
            'code' => 'original_code',
            'instructions' => 'Original instructions',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $data = [
            'name' => 'Updated Name',
            'code' => 'original_code',
            'instructions' => 'Updated instructions',
            'is_active' => false,
            'sort_order' => 2,
        ];

        $response = $this->put(route('admin.payment-methods.update', $paymentMethod), $data);

        $response->assertRedirect(route('admin.payment-methods.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
            'name' => 'Updated Name',
            'instructions' => 'Updated instructions',
        ]);
    }

    /** @test */
    public function test_admin_can_delete_payment_method_without_payments()
    {
        $this->actingAs($this->admin);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Delete Me',
            'code' => 'delete_me',
            'is_active' => true,
        ]);

        $response = $this->delete(route('admin.payment-methods.destroy', $paymentMethod));

        $response->assertRedirect(route('admin.payment-methods.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('payment_methods', [
            'id' => $paymentMethod->id,
        ]);
    }

    /** @test */
    public function test_admin_cannot_delete_payment_method_with_payments()
    {
        $this->actingAs($this->admin);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Used Method',
            'code' => 'used_method',
            'is_active' => true,
        ]);

        // Create a payment using this method
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
            'payment_method' => 'used_method',
            'status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->delete(route('admin.payment-methods.destroy', $paymentMethod));

        $response->assertRedirect(route('admin.payment-methods.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
        ]);
    }

    /** @test */
    public function test_admin_can_toggle_payment_method_active_status()
    {
        $this->actingAs($this->admin);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Toggle Method',
            'code' => 'toggle_method',
            'is_active' => true,
        ]);

        $response = $this->postJson(route('admin.payment-methods.toggle-active', $paymentMethod));

        $response->assertJson([
            'success' => true,
            'is_active' => false,
        ]);

        $this->assertFalse($paymentMethod->fresh()->is_active);
    }

    /** @test */
    public function test_guest_cannot_access_payment_methods_admin_routes()
    {
        $response = $this->get(route('admin.payment-methods.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.payment-methods.create'));
        $response->assertRedirect(route('login'));
    }
}
