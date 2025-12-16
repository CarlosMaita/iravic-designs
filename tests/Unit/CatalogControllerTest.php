<?php

namespace Tests\Unit;

use App\Http\Controllers\ecommerce\CatalogController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_available_genders_returns_sequential_array()
    {
        // Create a controller instance
        $controller = new CatalogController();
        
        // Use reflection to access the private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getAvailableGenders');
        $method->setAccessible(true);
        
        // Call the method
        $genders = $method->invoke($controller);
        
        // Assert it's an array
        $this->assertIsArray($genders);
        
        // Assert it has sequential keys (important for JSON encoding as JS array)
        // Handle empty array case: range(0, -1) returns [0, -1] instead of []
        $expectedKeys = count($genders) > 0 ? range(0, count($genders) - 1) : [];
        $this->assertEquals($expectedKeys, array_keys($genders));
        
        // Assert JSON encoding produces an array, not an object
        $jsonEncoded = json_encode($genders);
        $this->assertStringStartsWith('[', $jsonEncoded);
        $this->assertStringEndsWith(']', $jsonEncoded);
        
        // Verify it doesn't encode as an object (empty array is still [] not {})
        if (count($genders) > 0) {
            $this->assertStringNotContainsString('{', $jsonEncoded);
        }
    }
}