<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/**
 * Test Google authentication functionality.
 * These tests verify Google OAuth registration and login flows.
 */
class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that Google registration handles existing customers properly.
     *
     * @test
     */
    public function google_registration_prevents_duplicate_email()
    {
        // Create an existing customer with the email
        $existingCustomer = Customer::create([
            'name' => 'Existing Customer',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'qualification' => 'Bueno',
        ]);

        // Simulate Google session data
        Session::put('google_user', [
            'google_id' => '123456789',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        // Attempt to complete Google registration
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'Test User',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Should redirect to login with error message
        $response->assertRedirect(route('customer.login.form'));
        $response->assertSessionHas('error');

        // Session should be cleared
        $this->assertNull(Session::get('google_user'));

        // Customer count should remain 1
        $this->assertEquals(1, Customer::count());
    }

    /**
     * Test that Google registration handles soft-deleted customers.
     *
     * @test
     */
    public function google_registration_restores_soft_deleted_customer()
    {
        // Create a soft-deleted customer
        $deletedCustomer = Customer::create([
            'name' => 'Deleted Customer',
            'email' => 'deleted@example.com',
            'password' => Hash::make('oldpassword'),
            'qualification' => 'Bueno',
        ]);
        $deletedCustomer->delete(); // Soft delete

        // Verify customer is soft-deleted
        $this->assertEquals(0, Customer::count());
        $this->assertEquals(1, Customer::withTrashed()->count());

        // Simulate Google session data
        Session::put('google_user', [
            'google_id' => '987654321',
            'name' => 'Restored User',
            'email' => 'deleted@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        // Attempt to complete Google registration
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'Restored User',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Should redirect to dashboard with success message
        $response->assertRedirect(route('customer.dashboard'));
        $response->assertSessionHas('success');

        // Customer should be restored
        $this->assertEquals(1, Customer::count());
        
        // Verify customer data was updated
        $restoredCustomer = Customer::where('email', 'deleted@example.com')->first();
        $this->assertNotNull($restoredCustomer);
        $this->assertEquals('Restored User', $restoredCustomer->name);
        $this->assertTrue($restoredCustomer->google_verified);
        $this->assertEquals('987654321', $restoredCustomer->google_id);
        $this->assertTrue(Hash::check('newpassword123', $restoredCustomer->password));

        // Session should be cleared
        $this->assertNull(Session::get('google_user'));
    }

    /**
     * Test successful Google registration for new customer.
     *
     * @test
     */
    public function google_registration_creates_new_customer_successfully()
    {
        // Simulate Google session data
        Session::put('google_user', [
            'google_id' => '111222333',
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        // Complete Google registration
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Should redirect to dashboard with success message
        $response->assertRedirect(route('customer.dashboard'));
        $response->assertSessionHas('success');

        // Customer should be created
        $this->assertEquals(1, Customer::count());
        
        // Verify customer data
        $customer = Customer::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('New User', $customer->name);
        $this->assertTrue($customer->google_verified);
        $this->assertEquals('111222333', $customer->google_id);
        $this->assertEquals('Bueno', $customer->qualification);

        // Session should be cleared
        $this->assertNull(Session::get('google_user'));
    }

    /**
     * Test that registration fails without Google session.
     *
     * @test
     */
    public function google_registration_requires_valid_session()
    {
        // Attempt to complete registration without Google session
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Should redirect to registration form with error
        $response->assertRedirect(route('customer.register.form'));
        $response->assertSessionHas('error');

        // No customer should be created
        $this->assertEquals(0, Customer::count());
    }

    /**
     * Test Google registration form validation.
     *
     * @test
     */
    public function google_registration_validates_input()
    {
        // Simulate Google session data
        Session::put('google_user', [
            'google_id' => '444555666',
            'name' => 'Validation Test',
            'email' => 'validation@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        // Test missing name
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['name']);

        // Test short password
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'Test User',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertSessionHasErrors(['password']);

        // Test password mismatch
        $response = $this->post(route('customer.google.register.complete'), [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);
        $response->assertSessionHasErrors(['password']);

        // No customer should be created from failed validations
        $this->assertEquals(0, Customer::count());
    }
}
