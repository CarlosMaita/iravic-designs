<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Test the inventory upload functionality.
 * These tests verify that the inventory upload processes Excel files correctly.
 */
class InventoryUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Set up test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);
    }

    /**
     * Test that the inventory upload skips the header row.
     *
     * @test
     */
    public function inventory_upload_skips_header_row()
    {
        // Create a store type
        $storeType = StoreType::create(['name' => 'Physical']);
        
        // Create test stores
        $store1 = Store::create(['name' => 'Store 1', 'store_type_id' => $storeType->id]);
        $store2 = Store::create(['name' => 'Store 2', 'store_type_id' => $storeType->id]);
        
        // Create a regular product
        $product = Product::create([
            'name' => 'Test Product',
            'code' => 'TEST001',
            'is_regular' => 1,
            'price' => 100,
            'gender' => 'unisex'
        ]);

        // Attach stores with initial stock of 0
        $product->stores()->attach($store1->id, ['stock' => 0]);
        $product->stores()->attach($store2->id, ['stock' => 0]);

        // Create a test Excel file with header row
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Regular products
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Regulares');
        
        // Header row (should be skipped)
        $sheet->fromArray([['Id', 'brand_id', 'category_id', 'name', 'code', 'gender', 'price', 'stock_Store 1', 'stock_Store 2']], NULL, 'A1');
        
        // Data row
        $sheet->fromArray([[$product->id, '', '', 'Test Product', 'TEST001', 'unisex', 150, 10, 20]], NULL, 'A2');
        
        // Sheet 2: Non-regular products (empty but with header)
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('No regulares');
        $sheet2->fromArray([['Id', 'brand_id', 'category_id', 'name', 'code', 'gender', 'color_id', 'text_color', 'product_id', 'size_id', 'price', 'stock_Store 1', 'stock_Store 2']], NULL, 'A1');
        
        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'inventory_test');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Create uploaded file
        $uploadedFile = new UploadedFile($tempFile, 'inventory.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Make authenticated request to upload endpoint
        $response = $this->actingAs($this->user)->postJson(route('catalog.inventory.upload'), [
            'file' => $uploadedFile
        ]);

        // Assert successful response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Se han actualizado los inventarios'
            ]);

        // Verify the product was updated correctly
        $product->refresh();
        $this->assertEquals(150, $product->price, 'Product price should be updated to 150');
        
        // Verify stock was updated correctly (not 0)
        $store1Stock = $product->stores()->find($store1->id)->pivot->stock;
        $store2Stock = $product->stores()->find($store2->id)->pivot->stock;
        
        $this->assertEquals(10, $store1Stock, 'Store 1 stock should be 10');
        $this->assertEquals(20, $store2Stock, 'Store 2 stock should be 20');

        // Clean up
        unlink($tempFile);
    }

    /**
     * Test that the inventory upload handles numeric ID validation.
     *
     * @test
     */
    public function inventory_upload_filters_non_numeric_ids()
    {
        // Create a store type
        $storeType = StoreType::create(['name' => 'Physical']);
        
        // Create test stores
        $store1 = Store::create(['name' => 'Store 1', 'store_type_id' => $storeType->id]);
        
        // Create a regular product
        $product = Product::create([
            'name' => 'Test Product',
            'code' => 'TEST001',
            'is_regular' => 1,
            'price' => 100,
            'gender' => 'unisex'
        ]);

        // Attach store with initial stock of 0
        $product->stores()->attach($store1->id, ['stock' => 0]);

        // Create a test Excel file with mixed data
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Regular products
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Regulares');
        
        // Header row with "Id" string (should be skipped)
        $sheet->fromArray([['Id', 'brand_id', 'category_id', 'name', 'code', 'gender', 'price', 'stock_Store 1']], NULL, 'A1');
        
        // Data row with valid numeric ID
        $sheet->fromArray([[$product->id, '', '', 'Test Product', 'TEST001', 'unisex', 200, 15]], NULL, 'A2');
        
        // Data row with non-numeric ID (should be skipped)
        $sheet->fromArray([['invalid', '', '', 'Invalid Product', 'INV001', 'unisex', 300, 25]], NULL, 'A3');
        
        // Sheet 2: Non-regular products (empty but with header)
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('No regulares');
        $sheet2->fromArray([['Id', 'brand_id', 'category_id', 'name', 'code', 'gender', 'color_id', 'text_color', 'product_id', 'size_id', 'price', 'stock_Store 1']], NULL, 'A1');
        
        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'inventory_test');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Create uploaded file
        $uploadedFile = new UploadedFile($tempFile, 'inventory.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Make authenticated request to upload endpoint
        $response = $this->actingAs($this->user)->postJson(route('catalog.inventory.upload'), [
            'file' => $uploadedFile
        ]);

        // Assert successful response
        $response->assertStatus(200);

        // Verify the product was updated correctly (header and invalid ID should be skipped)
        $product->refresh();
        $this->assertEquals(200, $product->price, 'Product price should be updated to 200');
        
        // Verify stock was updated correctly
        $store1Stock = $product->stores()->find($store1->id)->pivot->stock;
        $this->assertEquals(15, $store1Stock, 'Store 1 stock should be 15, not 0 or 25');

        // Clean up
        unlink($tempFile);
    }
}
