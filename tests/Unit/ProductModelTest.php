<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

/**
 * Test Product model attributes and relationships definitions.
 * These tests verify product model configuration without requiring database.
 */
class ProductModelTest extends TestCase
{
    /**
     * Test that fillable attributes are properly defined.
     *
     * @test
     */
    public function fillable_attributes_are_defined()
    {
        $product = new Product();
        $fillable = $product->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('code', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('brand_id', $fillable);
        $this->assertContains('category_id', $fillable);
        $this->assertContains('color_id', $fillable);
        $this->assertContains('size_id', $fillable);
        $this->assertContains('gender', $fillable);
        $this->assertContains('is_regular', $fillable);
        $this->assertContains('is_featured', $fillable);
        $this->assertContains('slug', $fillable);
    }

    /**
     * Test that product uses soft deletes.
     *
     * @test
     */
    public function product_uses_soft_deletes()
    {
        $product = new Product();
        $traits = class_uses($product);

        $this->assertContains('Illuminate\Database\Eloquent\SoftDeletes', $traits);
    }

    /**
     * Test that appends are defined for product.
     *
     * @test
     */
    public function appends_are_defined()
    {
        $product = new Product();
        $appends = $product->getAppends();

        $this->assertContains('name_full', $appends);
        $this->assertContains('real_code', $appends);
        $this->assertContains('regular_price', $appends);
        $this->assertContains('stock_total', $appends);
        $this->assertContains('is_on_offer', $appends);
    }

    /**
     * Test product table name is correct.
     *
     * @test
     */
    public function product_table_name_is_correct()
    {
        $product = new Product();
        $this->assertEquals('products', $product->getTable());
    }

    /**
     * Test product can be instantiated.
     *
     * @test
     */
    public function product_can_be_instantiated()
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test product can be created with attributes.
     *
     * @test
     */
    public function product_can_be_created_with_attributes()
    {
        $product = new Product([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'code' => 'TEST-001',
            'price' => 99.99,
        ]);

        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals('Test Description', $product->description);
        $this->assertEquals('TEST-001', $product->code);
        $this->assertEquals(99.99, $product->price);
    }
}
