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

### 2. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database for testing (optional, tests use in-memory DB)
touch database/database.sqlite
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
- **PolicyTest.php** - Tests policy-related functionality

### Feature Tests (`tests/Feature/`)

Feature tests verify end-to-end functionality and HTTP responses:

- **Ecommerce/EcommerceRoutesTest.php** - Tests public ecommerce routes
- **Admin/AdminRoutesTest.php** - Tests admin panel routes
- **Auth/AuthenticationTest.php** - Tests authentication pages and flows

## Test Configuration

Tests are configured in `phpunit.xml`:

- Uses SQLite in-memory database (`:memory:`)
- Sets `APP_ENV=testing`
- Disables unnecessary services for faster testing
- Clears caches between tests

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

## Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/9.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Project README](../README.md)
