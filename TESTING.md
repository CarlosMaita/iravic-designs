# Testing Guide for E-commerce Iravic

This document explains how to run tests for the E-commerce Iravic application.

## Prerequisites

- PHP 8.0 or higher
- Composer
- SQLite extension for PHP

## Running Tests Locally

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Environment (Optional)

The tests are configured to run with SQLite in-memory database and a test APP_KEY is already set in `phpunit.xml`. You can run tests immediately after installing dependencies.

If you want to set up a local .env file for other purposes:

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Run All Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run tests with detailed output
vendor/bin/phpunit --testdox

# Run specific test suite
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Feature
```

### 4. Run Specific Test Files

```bash
# Run a specific test file
vendor/bin/phpunit tests/Unit/OrderBusinessLogicTest.php

# Run a specific test
vendor/bin/phpunit --filter test_order_can_be_cancelled_only_when_created
```

## Test Structure

### Unit Tests (`tests/Unit/`)

Unit tests verify individual components and business logic without requiring database setup:

- **OrderBusinessLogicTest.php** - Tests order status transitions and business rules
- **ProductModelTest.php** - Tests product model attributes and configuration
- **CustomerModelTest.php** - Tests customer model configuration
- **CatalogControllerTest.php** - Tests catalog controller methods
- **OrderCancellationTest.php** - Tests order cancellation functionality
- **PaymentMultiCurrencyTest.php** - Tests multi-currency payment features
- **NotificationTest.php** - Tests notification model constants, fillable fields, and casts
- **ProductImagePrimaryTest.php** - Tests primary image functionality in ProductImage model

### Feature Tests (`tests/Feature/`)

Feature tests verify end-to-end functionality and HTTP responses:

- **Ecommerce/EcommerceRoutesTest.php** - Tests public ecommerce routes
- **Admin/AdminRoutesTest.php** - Tests admin panel routes
- **Admin/UserManagementTest.php** - Tests user management functionality in admin panel
- **Auth/AuthenticationTest.php** - Tests authentication pages and flows
- **StoreControllerTest.php** - Tests store/warehouse controller and DataTables integration
- **ProductImageDisplayTest.php** - Tests product image display in admin panel, including DataTable columns and primary image functionality
- **ProductSlugGenerationTest.php** - Tests automatic slug generation for products, including uniqueness and special character handling

### Test Categories by Functionality

#### Product Management Tests
- `ProductModelTest.php` - Product model configuration and attributes
- `ProductSlugGenerationTest.php` - Automatic slug generation with special characters and uniqueness
- `ProductImagePrimaryTest.php` - Primary image field functionality
- `ProductImageDisplayTest.php` - Admin UI for product image display and management

#### User and Authentication Tests
- `AuthenticationTest.php` - Login, logout, and authentication flows
- `Admin/UserManagementTest.php` - User listing and management in admin panel

#### Inventory and Store Tests
- `StoreControllerTest.php` - Store/warehouse listing with DataTables integration

#### Order and Payment Tests
- `OrderBusinessLogicTest.php` - Order status transitions and business rules
- `OrderCancellationTest.php` - Order cancellation functionality
- `PaymentMultiCurrencyTest.php` - Multi-currency payment processing

#### Communication Tests
- `NotificationTest.php` - Customer notification system

## Test Configuration

Tests are configured in `phpunit.xml`:

- Uses SQLite in-memory database (`:memory:`)
- Sets `APP_ENV=testing`
- Includes a test `APP_KEY` for encryption (no need to generate one)
- Disables unnecessary services for faster testing
- Clears caches between tests

**Note**: The `phpunit.xml` file includes a pre-configured APP_KEY for testing purposes. This is safe for testing and allows tests to run without requiring a local .env file.

## CI/CD Integration

Tests run automatically on:

- **Pull Requests** to `main` or `develop` branches
- **Pushes** to `main` or `develop` branches

The CI/CD pipeline:

1. Runs all PHPUnit tests
2. If tests pass and branch is `main`, deploys to production
3. If tests fail, deployment is blocked

See `.github/workflows/tests.yml` for the complete CI/CD configuration.

## Writing New Tests

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\YourModel;

class YourModelTest extends TestCase
{
    /** @test */
    public function it_does_something()
    {
        $model = new YourModel(['attribute' => 'value']);
        
        $this->assertEquals('value', $model->attribute);
    }
}
```

### Feature Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class YourFeatureTest extends TestCase
{
    /** @test */
    public function route_is_accessible()
    {
        $response = $this->get('/your-route');
        
        $response->assertStatus(200);
    }
}
```

## Detailed Test Examples

### Testing Notification Model (Unit Test)

The `NotificationTest` verifies that the notification model has correct type constants and configuration:

```php
<?php

namespace Tests\Unit;

use App\Models\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /** @test */
    public function test_notification_has_correct_type_constants()
    {
        // Verify notification type constants exist and are correct
        $this->assertEquals('welcome', Notification::TYPE_WELCOME);
        $this->assertEquals('order_created', Notification::TYPE_ORDER_CREATED);
        $this->assertEquals('payment_confirmed', Notification::TYPE_PAYMENT_CONFIRMED);
    }

    /** @test */
    public function test_notification_fillable_fields_are_correct()
    {
        $notification = new Notification();
        $fillable = $notification->getFillable();

        // Verify all expected fields are fillable
        $this->assertContains('customer_id', $fillable);
        $this->assertContains('type', $fillable);
        $this->assertContains('message', $fillable);
    }
}
```

### Testing Product Slug Generation (Feature Test)

The `ProductSlugGenerationTest` ensures products automatically get URL-friendly slugs:

```php
<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSlugGenerationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
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

        // Assert slug was generated automatically
        $this->assertNotNull($product->slug);
        $this->assertEquals('test-product-name', $product->slug);
    }

    /** @test */
    public function slug_is_unique_when_duplicate_names_exist()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create multiple products with the same name
        $product1 = Product::create([
            'name' => 'Duplicate Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        $product2 = Product::create([
            'name' => 'Duplicate Name',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_regular' => true,
            'price' => 99.99
        ]);

        // Assert slugs are unique with auto-increment
        $this->assertEquals('duplicate-name', $product1->slug);
        $this->assertEquals('duplicate-name-1', $product2->slug);
    }
}
```

### Testing DataTables Integration (Feature Test)

The `StoreControllerTest` verifies DataTables JSON responses for warehouse/store listings:

```php
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
        
        // Seed store types required for testing
        StoreType::create(['id' => 1, 'name' => 'Deposito']);
        StoreType::create(['id' => 2, 'name' => 'Local Comercial']);
    }

    /** @test */
    public function test_depositos_index_returns_json_with_stores_data()
    {
        // Create and authenticate a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // Create test stores
        Store::create(['name' => 'Casa', 'store_type_id' => 1]);
        Store::create(['name' => 'Oficina', 'store_type_id' => 2]);

        // Make AJAX request with proper headers
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->getJson(route('depositos.index'));

        // Assert DataTables JSON structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    }
}
```

### Testing Primary Image Functionality (Unit Test)

The `ProductImagePrimaryTest` validates the primary image field in product images:

```php
<?php

namespace Tests\Unit;

use App\Models\ProductImage;
use Tests\TestCase;

class ProductImagePrimaryTest extends TestCase
{
    /** @test */
    public function is_primary_is_fillable()
    {
        $productImage = new ProductImage();
        $fillable = $productImage->getFillable();

        // Verify is_primary is in fillable attributes
        $this->assertContains('is_primary', $fillable);
    }

    /** @test */
    public function product_image_can_be_created_with_is_primary()
    {
        // Create a product image with is_primary flag
        $productImage = new ProductImage([
            'product_id' => 1,
            'url' => 'test.jpg',
            'is_primary' => true,
        ]);

        $this->assertTrue($productImage->is_primary);
    }
}
```

## Troubleshooting

### Database Migration Issues

Some tests may fail if database migrations use MySQL-specific syntax. The application is configured to use SQLite in-memory database for testing, which avoids most migration issues.

### Missing Extensions

If you get errors about missing PHP extensions:

```bash
# Install SQLite extension
sudo apt-get install php8.0-sqlite3

# Or on macOS
brew install php@8.0
```

### Memory Issues

If tests fail due to memory issues, increase PHP memory limit:

```bash
php -d memory_limit=512M vendor/bin/phpunit
```

### Common Test Failures

#### Factory Not Found

If you get an error about missing factories:
```
Error: Class 'Database\Factories\BrandFactory' not found
```

Solution: Ensure the factory exists in `database/factories/`. Laravel 8+ uses the `database/factories` directory structure.

#### Route Not Found

If a test fails with "Route not found":
```
Error: Route [your.route] not defined
```

Solution: Clear route cache and verify route exists in `routes/` directory:
```bash
php artisan route:clear
php artisan route:list | grep your-route
```

#### DataTables Returns HTML Instead of JSON

If DataTables tests fail because HTML is returned instead of JSON:

Solution: Ensure you're including the required AJAX headers in your test:
```php
$response = $this->withHeaders([
    'X-Requested-With' => 'XMLHttpRequest',
    'Accept' => 'application/json',
])->getJson(route('your.route'));
```

## Common Testing Patterns

### Pattern 1: Testing Model Fillable Attributes

```php
/** @test */
public function model_has_correct_fillable_attributes()
{
    $model = new YourModel();
    $fillable = $model->getFillable();

    $this->assertContains('attribute_name', $fillable);
}
```

### Pattern 2: Testing Model Constants

```php
/** @test */
public function model_has_correct_constants()
{
    $this->assertEquals('expected_value', YourModel::CONSTANT_NAME);
}
```

### Pattern 3: Testing Model Casts

```php
/** @test */
public function model_casts_are_correct()
{
    $model = new YourModel();
    $casts = $model->getCasts();

    $this->assertEquals('boolean', $casts['is_active']);
    $this->assertEquals('datetime', $casts['created_at']);
}
```

### Pattern 4: Testing Route Access with Authentication

```php
/** @test */
public function authenticated_user_can_access_route()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/admin/route');
    
    $response->assertStatus(200);
}
```

### Pattern 5: Testing Automatic Attribute Generation

```php
/** @test */
public function attribute_is_generated_automatically()
{
    $model = Model::create([
        'name' => 'Test Name',
    ]);

    // Assert auto-generated attribute exists
    $this->assertNotNull($model->generated_attribute);
}
```

### Pattern 6: Testing Uniqueness Constraints

```php
/** @test */
public function attribute_is_unique_with_increment()
{
    $model1 = Model::create(['name' => 'Same Name']);
    $model2 = Model::create(['name' => 'Same Name']);

    // Assert different unique values
    $this->assertNotEquals($model1->slug, $model2->slug);
}
```

### Pattern 7: Testing JSON Structure from DataTables

```php
/** @test */
public function endpoint_returns_datatable_json_structure()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->getJson(route('data.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
}
```

### Pattern 8: Testing File Content Assertions

```php
/** @test */
public function file_contains_expected_code()
{
    $filePath = resource_path('path/to/file.php');
    $content = file_get_contents($filePath);

    $this->assertStringContainsString(
        'expected code snippet',
        $content,
        'File should contain the expected code'
    );
}
```

## Test Coverage

To generate test coverage reports (requires Xdebug):

```bash
vendor/bin/phpunit --coverage-html coverage/
```

Then open `coverage/index.html` in your browser.

## Best Practices

1. **Keep tests fast** - Use in-memory database and avoid unnecessary setup
2. **Test business logic** - Focus on testing application logic, not framework features
3. **Write descriptive test names** - Use clear, readable test method names
4. **One assertion per concept** - Test one thing at a time
5. **Keep tests independent** - Tests should not depend on each other
6. **Use factories and seeders** - For consistent test data

## Important Considerations

### RefreshDatabase Trait

Most feature tests use the `RefreshDatabase` trait to ensure a clean database state for each test:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourTest extends TestCase
{
    use RefreshDatabase;
    
    // Your tests here
}
```

**Warning**: This trait migrates the database before each test. If migrations fail due to MySQL-specific syntax with SQLite, some tests may fail. See the Troubleshooting section below.

### DataTables Testing

When testing DataTables endpoints, always include the proper AJAX headers:

```php
$response = $this->withHeaders([
    'X-Requested-With' => 'XMLHttpRequest',
    'Accept' => 'application/json',
])->getJson(route('your.route'));
```

**Note**: Without these headers, the controller may return a view instead of JSON, causing tests to fail.

### Product Testing Requirements

Tests that create products require related models (Brand and Category). Always use factories:

```php
$brand = Brand::factory()->create();
$category = Category::factory()->create();

$product = Product::create([
    'name' => 'Test Product',
    'brand_id' => $brand->id,
    'category_id' => $category->id,
    'is_regular' => true,
    'price' => 99.99
]);
```

### File Content Assertions

Some tests validate file contents (like `ProductImageDisplayTest`). These tests verify that:
- DataTable configurations are correct
- Event handlers exist in JavaScript files
- Vue component structures match expected patterns

**Important**: These tests read actual source files and assert on their contents. If you refactor the code structure, update the corresponding tests.

### Authentication in Tests

Feature tests that require authentication should create and authenticate a user:

```php
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);

$this->actingAs($user);

// Now make authenticated requests
$response = $this->get('/admin/some-route');
```

## Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/9.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Project README](../README.md)
