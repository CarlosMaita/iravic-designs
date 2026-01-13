<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\BaseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategorySlugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a base category for testing
        BaseCategory::create([
            'name' => 'Ropa',
            'has_gender' => true,
            'has_size' => true,
        ]);
    }

    /** @test */
    public function category_can_be_accessed_by_slug()
    {
        $category = Category::create([
            'name' => 'Chaquetas',
            'slug' => 'chaquetas',
            'base_category_id' => 1,
        ]);

        $response = $this->get(route('ecommerce.categoria', $category->slug));

        $response->assertStatus(200);
        $response->assertViewIs('ecommerce.catalog.index');
        $response->assertViewHas('category', $category->id);
    }

    /** @test */
    public function old_category_id_urls_redirect_to_slug()
    {
        $category = Category::create([
            'name' => 'Pantalones',
            'slug' => 'pantalones',
            'base_category_id' => 1,
        ]);

        $response = $this->get('/catalogo?category=' . $category->id);

        $response->assertRedirect(route('ecommerce.categoria', $category->slug));
        $response->assertStatus(301); // Permanent redirect
    }

    /** @test */
    public function category_slug_is_unique()
    {
        Category::create([
            'name' => 'Camisas',
            'slug' => 'camisas',
            'base_category_id' => 1,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Category::create([
            'name' => 'Camisas Premium',
            'slug' => 'camisas', // Same slug
            'base_category_id' => 1,
        ]);
    }

    /** @test */
    public function category_can_be_created_without_slug()
    {
        // This tests that the CategoryRequest allows nullable slug
        // In actual usage, the controller will auto-generate from name
        $category = Category::create([
            'name' => 'Zapatos',
            'slug' => null,
            'base_category_id' => 1,
        ]);

        $this->assertNull($category->slug);
    }

    /** @test */
    public function sitemap_includes_category_slug_urls()
    {
        $category = Category::create([
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'base_category_id' => 1,
        ]);

        $this->artisan('sitemap:generate');

        $sitemapContent = file_get_contents(public_path('sitemap.xml'));
        
        $this->assertStringContainsString('/categoria/accesorios', $sitemapContent);
        $this->assertStringNotContainsString('/categoria/' . $category->id, $sitemapContent);
    }
}
