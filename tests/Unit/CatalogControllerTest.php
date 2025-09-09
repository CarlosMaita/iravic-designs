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
        $this->assertEquals(array_keys($genders), range(0, count($genders) - 1));
        
        // Assert JSON encoding produces an array, not an object
        $jsonEncoded = json_encode($genders);
        $this->assertStringStartsWith('[', $jsonEncoded);
        $this->assertStringEndsWith(']', $jsonEncoded);
        
        // Verify it doesn't encode as an object
        $this->assertStringNotContainsString('{', $jsonEncoded);
    }
}