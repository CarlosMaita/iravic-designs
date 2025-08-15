# E-commerce Iravic Laravel Application

E-commerce Iravic is a Laravel 8 web application built with CoreUI admin template, Vue.js frontend, and comprehensive e-commerce functionality including inventory management, customer management, and order processing.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Prerequisites and Environment Setup
- Requires PHP 8.0+ (tested with PHP 8.3.6)
- Requires Node.js 20+ and npm (tested with Node.js 20.19.4, npm 10.8.2)
- Requires Composer (tested with Composer 2.8.10)
- Database: MySQL (recommended), SQLite, or PostgreSQL support

### Initial Setup and Dependencies
Run these commands in order for a fresh installation:

1. **Install PHP Dependencies:**
   ```bash
   composer install --no-dev --prefer-dist --no-interaction
   ```
   - Takes ~3.5 minutes. NEVER CANCEL. Set timeout to 10+ minutes.
   - May prompt for GitHub token if rate limited - follow the provided URL instructions.

2. **Install Node.js Dependencies:**
   ```bash
   npm install --ignore-scripts
   ```
   - Takes ~9 minutes. NEVER CANCEL. Set timeout to 15+ minutes.
   - Uses `--ignore-scripts` to avoid node-sass compatibility issues with Node.js 20+.
   - Expect many deprecation warnings - this is normal for this older project.

3. **Environment Configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

### Database Setup

**For SQLite (recommended for development):**
```bash
# Update .env file database configuration:
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/project/database/database.sqlite

touch database/database.sqlite
php artisan migrate:refresh --seed
```

**For MySQL:**
```bash
# Configure .env with your MySQL credentials:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=your_password

php artisan migrate:refresh --seed
```

**IMPORTANT DATABASE LIMITATIONS:**
- Some migrations may fail with SQLite due to ENUM and MODIFY COLUMN syntax incompatibility.
- If migrations fail, the application will still run with basic functionality.
- Full database setup works best with MySQL.

### Frontend Asset Compilation

**CRITICAL ISSUE - Asset Building:**
```bash
# Asset building currently FAILS due to node-sass compatibility with Node.js 20+
npm run dev  # Will fail with "Node Sass does not yet support your current environment"
```

**Workarounds for Asset Building:**
1. **Use pre-compiled assets** (if available in public/ directory)
2. **Alternative approach** (requires manual intervention):
   ```bash
   npm uninstall node-sass
   npm install sass
   # May still fail due to sass-loader dependency chain
   ```

**DO NOT attempt to build assets with Node.js 20+ until node-sass dependencies are updated.**

### Running the Application
```bash
php artisan serve --host=127.0.0.1 --port=8000
```
- Application starts immediately
- Access at: http://localhost:8000
- Default admin credentials: admin@admin.com / password

### Testing

**PHPUnit Tests:**
```bash
composer install  # Include dev dependencies
vendor/bin/phpunit
```
- Laravel 8 uses older test structure
- Some tests may fail if database migrations are incomplete

## Validation Scenarios

After making changes, always validate:

1. **Database connectivity**: Check that `php artisan migrate:status` works
2. **Application startup**: Verify `php artisan serve` starts without errors
3. **Basic routing**: Test that routes respond (may return 500 if database incomplete)
4. **Admin authentication**: If database is properly seeded, test login at /login

## Common Issues and Limitations

### Asset Building Issues
- **Problem**: node-sass incompatible with Node.js 20+
- **Impact**: Cannot build CSS/JS assets with `npm run dev`
- **Workaround**: Use pre-compiled assets or manually replace node-sass dependencies
- **Status**: Known issue requiring project dependency updates

### Database Migration Issues
- **Problem**: Some migrations use MySQL-specific syntax (ENUM, MODIFY COLUMN)
- **Impact**: SQLite migrations may fail
- **Workaround**: Use MySQL for full compatibility, or skip failing migrations
- **Status**: Application runs with partial database schema

### GitHub Token Requirements
- **Problem**: Composer may request GitHub token for rate limiting
- **Solution**: Follow provided URL to create token, or use `--no-interaction` flag
- **Status**: Normal for high-volume package installation

## Build Times and Timeouts

- **Composer install**: 3-4 minutes (set timeout: 10+ minutes)
- **npm install**: 8-10 minutes (set timeout: 15+ minutes)
- **Database migrations**: <1 minute (set timeout: 5+ minutes)
- **Asset building**: Currently fails (node-sass issue)
- **PHPUnit tests**: Variable depending on database state

## Architecture Overview

### Key Directories
- `app/Http/Controllers/` - Laravel controllers (admin/ and API endpoints)
- `resources/views/` - Blade templates
- `resources/js/` - Vue.js components and JavaScript
- `resources/sass/` - SCSS stylesheets
- `database/migrations/` - Database schema definitions
- `database/seeders/` - Database seed data
- `public/` - Web-accessible files and compiled assets

### Technology Stack
- **Backend**: Laravel 8, PHP 8.0+
- **Frontend**: Vue.js 2.6, CoreUI Bootstrap admin template
- **Database**: MySQL/SQLite/PostgreSQL with Eloquent ORM
- **Build Tools**: Laravel Mix (Webpack), npm
- **Authentication**: Laravel built-in with role-based permissions

### Key Features
- E-commerce product catalog and inventory management
- Customer management and order processing
- Admin panel with CoreUI interface
- Role-based access control
- Payment processing integration
- Reporting and analytics

## Critical Reminders

- **NEVER CANCEL** long-running npm install or composer install commands
- **ALWAYS** use appropriate timeouts (10+ minutes for dependencies)
- **DO NOT** attempt to build assets with current dependency versions
- **TEST** database connectivity before assuming application issues
- **USE** MySQL for full feature compatibility
- **EXPECT** deprecation warnings during npm install (normal for older project)

This application requires dependency updates to work fully with modern Node.js versions. For production use, consider updating to Laravel 9+ and modernizing the frontend build toolchain.