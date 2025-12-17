<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeCustomer;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Test customer registration functionality.
 * Verifies that customers can register and receive welcome emails.
 */
class CustomerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a customer can register successfully.
     *
     * @test
     */
    public function customer_can_register_successfully()
    {
        Mail::fake();

        $customerData = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/customer/register', $customerData);

        // Should redirect to customer dashboard
        $response->assertRedirect('/e/dashboard');

        // Verify customer was created in database
        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        // Verify welcome email was sent
        Mail::assertSent(WelcomeCustomer::class, function ($mail) use ($customerData) {
            return $mail->hasTo($customerData['email']);
        });
    }

    /**
     * Test that welcome email is sent immediately (not queued).
     *
     * @test
     */
    public function welcome_email_is_sent_immediately()
    {
        Mail::fake();

        $customerData = [
            'name' => 'Immediate Test',
            'email' => 'immediate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->post('/customer/register', $customerData);

        // Assert email was sent (not queued)
        Mail::assertSent(WelcomeCustomer::class);
        Mail::assertNotQueued(WelcomeCustomer::class);
    }

    /**
     * Test that registration fails with invalid data.
     *
     * @test
     */
    public function registration_fails_with_invalid_email()
    {
        $customerData = [
            'name' => 'Test Customer',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/customer/register', $customerData);

        // Should have validation errors
        $response->assertSessionHasErrors('email');

        // Customer should not be created
        $this->assertDatabaseMissing('customers', [
            'name' => 'Test Customer',
        ]);
    }

    /**
     * Test that registration fails when password confirmation doesn't match.
     *
     * @test
     */
    public function registration_fails_when_password_confirmation_does_not_match()
    {
        $customerData = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ];

        $response = $this->post('/customer/register', $customerData);

        // Should have validation errors
        $response->assertSessionHasErrors('password');

        // Customer should not be created
        $this->assertDatabaseMissing('customers', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test that registration fails with duplicate email.
     *
     * @test
     */
    public function registration_fails_with_duplicate_email()
    {
        // Create existing customer
        Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $customerData = [
            'name' => 'New Customer',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/customer/register', $customerData);

        // Should have validation errors
        $response->assertSessionHasErrors('email');
    }
}
