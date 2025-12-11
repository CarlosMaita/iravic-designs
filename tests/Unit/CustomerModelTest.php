<?php

namespace Tests\Unit;

use App\Models\Customer;
use Tests\TestCase;

/**
 * Test Customer model configuration.
 * These tests verify customer model attributes and authentication setup.
 */
class CustomerModelTest extends TestCase
{
    /**
     * Test customer can be instantiated.
     *
     * @test
     */
    public function customer_can_be_instantiated()
    {
        $customer = new Customer();
        $this->assertInstanceOf(Customer::class, $customer);
    }

    /**
     * Test customer has fillable attributes.
     *
     * @test
     */
    public function customer_has_fillable_attributes()
    {
        $customer = new Customer();
        $fillable = $customer->getFillable();

        $this->assertIsArray($fillable);
        $this->assertNotEmpty($fillable);
    }

    /**
     * Test customer can be created with basic attributes.
     *
     * @test
     */
    public function customer_can_be_created_with_basic_attributes()
    {
        $customer = new Customer([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('John Doe', $customer->name);
        $this->assertEquals('john@example.com', $customer->email);
    }

    /**
     * Test customer table name.
     *
     * @test
     */
    public function customer_table_name_is_correct()
    {
        $customer = new Customer();
        $this->assertEquals('customers', $customer->getTable());
    }
}
