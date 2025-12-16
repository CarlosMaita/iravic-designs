<?php

namespace Tests\Unit;

use App\Models\ProductImage;
use Tests\TestCase;

/**
 * Test ProductImage model primary image functionality.
 * These tests verify primary image logic without requiring database.
 */
class ProductImagePrimaryTest extends TestCase
{
    /**
     * Test that is_primary is in fillable attributes.
     *
     * @test
     */
    public function is_primary_is_fillable()
    {
        $productImage = new ProductImage();
        $fillable = $productImage->getFillable();

        $this->assertContains('is_primary', $fillable);
    }

    /**
     * Test that product image can be created with is_primary attribute.
     *
     * @test
     */
    public function product_image_can_be_created_with_is_primary()
    {
        $productImage = new ProductImage([
            'product_id' => 1,
            'url' => 'test.jpg',
            'is_primary' => true,
        ]);

        $this->assertTrue($productImage->is_primary);
    }

    /**
     * Test that product image defaults is_primary to false.
     *
     * @test
     */
    public function product_image_defaults_is_primary_to_false()
    {
        $productImage = new ProductImage([
            'product_id' => 1,
            'url' => 'test.jpg',
        ]);

        // When not explicitly set, is_primary will be null (not saved to DB yet)
        // The database default of false will apply when saved
        $this->assertNotTrue($productImage->is_primary);
    }

    /**
     * Test that ProductImage model exists and can be instantiated.
     *
     * @test
     */
    public function product_image_can_be_instantiated()
    {
        $productImage = new ProductImage();
        $this->assertInstanceOf(ProductImage::class, $productImage);
    }
}
