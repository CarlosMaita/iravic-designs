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
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // Create test stores
        Store::create(['name' => 'Casa', 'store_type_id' => 1]);
        Store::create(['name' => 'Oficina Principal', 'store_type_id' => 2]);
        Store::create(['name' => 'Vehículo 1', 'store_type_id' => 3]);

        // Make AJAX request to the index endpoint with explicit headers
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->getJson(route('depositos.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert response is valid JSON with DataTables structure
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);

        // Verify the response contains the stores
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(3, count($data), 'Should have at least 3 stores');

        // Verify that store names are present
        $storeNames = array_column($data, 'name');
        $this->assertContains('Casa', $storeNames);
        $this->assertContains('Oficina Principal', $storeNames);
        $this->assertContains('Vehículo 1', $storeNames);
    }
}
