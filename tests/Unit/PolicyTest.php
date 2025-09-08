<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Policies\BrandPolicy;
use App\User;
use App\Models\Customer;
use App\Models\Brand;

class PolicyTest extends TestCase
{
    public function test_brand_policy_with_user_and_customer()
    {
        // Create a mock user that has the necessary methods
        $user = $this->getMockBuilder(User::class)
                     ->disableOriginalConstructor()
                     ->getMock();
        
        // Mock the permissions method to return an empty collection 
        $user->method('permissions')->willReturn(collect([]));
        
        // Create a mock customer
        $customer = $this->getMockBuilder(Customer::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        
        $policy = new BrandPolicy();
        
        // Test that policy works with User objects
        $this->assertFalse($policy->viewAny($user));
        
        // Test that policy returns false for Customer objects (no admin access)
        $this->assertFalse($policy->viewAny($customer));
        
        // Test other methods work with both types
        $this->assertFalse($policy->create($user));
        $this->assertFalse($policy->create($customer));
    }
}