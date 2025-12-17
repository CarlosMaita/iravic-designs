<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test product slug generation functionality
 */
class ProductSlugGenerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that slug is generated automatically when creating a product
     *
     * @test
     */
    public function slug_is_generated_automatically_on_product_creation()
    {
        // Create necessary related models
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create a product without providing a slug
        $product = Product::create([
            'name' => 'Test Product Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Assert slug was generated
        $this->assertNotNull($product->slug);
        $this->assertEquals('test-product-name', $product->slug);
    }

    /**
     * Test that slug is unique and incremented when duplicate product names exist
     *
     * @test
     */
    public function slug_is_unique_when_duplicate_names_exist()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create first product
        $product1 = Product::create([
            'name' => 'Duplicate Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Create second product with same name
        $product2 = Product::create([
            'name' => 'Duplicate Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Create third product with same name
        $product3 = Product::create([
            'name' => 'Duplicate Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Assert slugs are unique
        $this->assertEquals('duplicate-name', $product1->slug);
        $this->assertEquals('duplicate-name-1', $product2->slug);
        $this->assertEquals('duplicate-name-2', $product3->slug);
        
        // Assert all slugs are different
        $this->assertNotEquals($product1->slug, $product2->slug);
        $this->assertNotEquals($product2->slug, $product3->slug);
        $this->assertNotEquals($product1->slug, $product3->slug);
    }

    /**
     * Test that existing slug is not overwritten when updating a product
     *
     * @test
     */
    public function existing_slug_is_not_overwritten_on_update()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create a product
        $product = Product::create([
            'name' => 'Original Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        $originalSlug = $product->slug;

        // Update the product name
        $product->update([
            'name' => 'Updated Name'
        ]);

        // Slug should remain the same
        $this->assertEquals($originalSlug, $product->slug);
    }

    /**
     * Test that slug is generated from name with special characters
     *
     * @test
     */
    public function slug_is_generated_from_name_with_special_characters()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create a product with special characters in name
        $product = Product::create([
            'name' => 'Chaqueta Rosada & Azul (Nueva Colección)',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Assert slug is properly formatted
        $this->assertNotNull($product->slug);
        // Slug should be URL-friendly (no special characters)
        $this->assertMatchesRegularExpression('/^[a-z0-9\-]+$/', $product->slug);
    }

    /**
     * Test that slug can be manually set when creating a product
     *
     * @test
     */
    public function custom_slug_is_preserved_when_provided()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create a product with custom slug
        $product = Product::create([
            'name' => 'Product Name',
            'slug' => 'custom-slug-here',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Assert custom slug is used
        $this->assertEquals('custom-slug-here', $product->slug);
    }
}
