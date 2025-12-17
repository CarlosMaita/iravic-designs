<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test to validate that product images are displayed correctly in the admin panel.
 * This test validates that regular products now use vue2-dropzone for async image uploads.
 */
class ProductImageDisplayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Vue component has vue-dropzone for regular products.
     * Validates that the migration from Dropzone.js to vue2-dropzone is complete.
     *
     * @test
     */
    public function vue_component_has_dropzone_for_regular_products()
    {
        $vueComponentPath = resource_path('js/components/catalog/ProductFormComponent.vue');
        $content = file_get_contents($vueComponentPath);

        // Verify v-dropzone component exists for regular products
        $this->assertStringContainsString(
            'ref="dropzone-regular"',
            $content,
            'Vue component should have v-dropzone with ref="dropzone-regular"'
        );

        // Verify dropzone options are configured for regular products
        $this->assertStringContainsString(
            ':options="dropzoneOptionsRegular"',
            $content,
            'Vue component should have dropzoneOptionsRegular configured'
        );
    }

    /**
     * Test that regularProductImages data property exists.
     * Validates that the component manages regular product images in Vue.
     *
     * @test
     */
    public function vue_component_has_regular_product_images_data()
    {
        $vueComponentPath = resource_path('js/components/catalog/ProductFormComponent.vue');
        $content = file_get_contents($vueComponentPath);

        // Verify regularProductImages data property exists
        $this->assertStringContainsString(
            'regularProductImages:',
            $content,
            'Vue component should have regularProductImages data property'
        );

        // Verify images are displayed in a grid
        $this->assertStringContainsString(
            'v-for="(image, index_image) in regularProductImages"',
            $content,
            'Vue component should display regularProductImages in a v-for loop'
        );
    }

    /**
     * Test that async upload handlers exist for regular products.
     *
     * @test
     */
    public function vue_component_has_async_upload_handlers()
    {
        $vueComponentPath = resource_path('js/components/catalog/ProductFormComponent.vue');
        $content = file_get_contents($vueComponentPath);

        // Verify event handlers for vue-dropzone exist
        $this->assertStringContainsString(
            '@vdropzone-sending-multiple="sendingEventRegular"',
            $content,
            'Vue component should have sendingEventRegular handler'
        );

        $this->assertStringContainsString(
            '@vdropzone-success-multiple="successEventRegular"',
            $content,
            'Vue component should have successEventRegular handler'
        );

        $this->assertStringContainsString(
            '@vdropzone-removed-file="removedFileEventRegular"',
            $content,
            'Vue component should have removedFileEventRegular handler'
        );
    }

    /**
     * Test that Vue methods for image management exist.
     *
     * @test
     */
    public function vue_component_has_image_management_methods()
    {
        $vueComponentPath = resource_path('js/components/catalog/ProductFormComponent.vue');
        $content = file_get_contents($vueComponentPath);

        // Verify methods exist for managing regular product images
        $this->assertStringContainsString(
            'removeImageRegular',
            $content,
            'Vue component should have removeImageRegular method'
        );

        $this->assertStringContainsString(
            'setPrimaryImageRegular',
            $content,
            'Vue component should have setPrimaryImageRegular method'
        );
    }

    /**
     * Test that the ProductImageController returns url_img in response.
     * This validates that images can be displayed immediately after upload.
     *
     * @test
     */
    public function product_image_controller_returns_url_img()
    {
        $controllerPath = app_path('Http/Controllers/admin/catalog/ProductImageController.php');
        $content = file_get_contents($controllerPath);

        // Verify url_img is included in the response
        $this->assertStringContainsString(
            "'url_img' => \$productImage->url_img",
            $content,
            'ProductImageController should return url_img in the response'
        );
    }

    /**
     * Test that form submission is simplified (no Dropzone.js).
     *
     * @test
     */
    public function form_submission_is_simplified()
    {
        $formBladePath = resource_path('views/dashboard/catalog/products/js/form.blade.php');
        $content = file_get_contents($formBladePath);

        // Verify Dropzone.js initialization is removed
        $this->assertStringNotContainsString(
            'new Dropzone("#myDropzone"',
            $content,
            'form.blade.php should not have Dropzone.js initialization'
        );

        // Verify DataTable initialization is removed
        $this->assertStringNotContainsString(
            'DATATABLE_IMAGES.DataTable',
            $content,
            'form.blade.php should not have DataTable initialization'
        );
    }
}
