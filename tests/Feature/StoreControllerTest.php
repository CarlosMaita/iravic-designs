<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\StoreType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed store types
        StoreType::create(['id' => 1, 'name' => 'Deposito']);
        StoreType::create(['id' => 2, 'name' => 'Local Comercial']);
        StoreType::create(['id' => 3, 'name' => 'Vehículo de Reparto']);
    }

    /** @test */
    public function test_depositos_index_returns_json_with_stores_data()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create test stores
        $store1 = Store::create(['name' => 'Casa', 'store_type_id' => 1]);
        $store2 = Store::create(['name' => 'Oficina Principal', 'store_type_id' => 2]);
        $store3 = Store::create(['name' => 'Vehículo 1', 'store_type_id' => 3]);

        // Make AJAX request to the index endpoint
        $response = $this->getJson(route('depositos.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert response is valid JSON
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'name',
                    'type',
                    'action'
                ]
            ]
        ]);

        // Verify the response contains the stores
        $data = $response->json('data');
        $this->assertCount(3, $data);

        // Verify that type.name is properly resolved
        $storeNames = array_column($data, 'name');
        $this->assertContains('Casa', $storeNames);
        $this->assertContains('Oficina Principal', $storeNames);
        $this->assertContains('Vehículo 1', $storeNames);

        // Verify type data is present
        foreach ($data as $storeData) {
            $this->assertArrayHasKey('type', $storeData);
            $this->assertNotNull($storeData['type']);
            $this->assertArrayHasKey('name', $storeData['type']);
        }
    }

    /** @test */
    public function test_depositos_index_handles_store_without_type()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a store without a type (edge case)
        Store::create(['name' => 'Store Without Type', 'store_type_id' => null]);

        // Make AJAX request to the index endpoint
        $response = $this->getJson(route('depositos.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify the response contains the store
        $data = $response->json('data');
        $this->assertCount(1, $data);

        // Verify that type is handled gracefully when null
        $this->assertEquals('Store Without Type', $data[0]['name']);
        // The editColumn callback should return empty string for null type
        $this->assertArrayHasKey('type', $data[0]);
    }
}
