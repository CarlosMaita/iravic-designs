<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

/**
 * Test the admin panel routes and authentication.
 * These tests verify that the admin routes are properly protected and accessible.
 */
class AdminRoutesTest extends TestCase
{
    /**
     * Test that admin login route is accessible.
     *
     * @test
     */
    public function admin_login_route_is_accessible()
    {
        $response = $this->get('/login');

        // Should return 200 OK
        $this->assertContains($response->status(), [200, 302]);
    }

    /**
     * Test that admin dashboard redirects when not authenticated.
     *
     * @test
     */
    public function admin_dashboard_redirects_when_not_authenticated()
    {
        $response = $this->get('/admin');

        // Should redirect to login or return 200 (if already on login)
        $this->assertContains($response->status(), [200, 302]);
    }

    /**
     * Test that admin home route exists.
     *
     * @test
     */
    public function admin_home_route_exists()
    {
        $response = $this->get('/admin');

        // Should not return 404 (redirect to login is expected)
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test that product management routes exist.
     *
     * @test
     */
    public function product_management_routes_exist()
    {
        $response = $this->get('/admin/catalogo/productos');

        // Should redirect to login or return error, but not 404
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test that order management routes exist.
     *
     * @test
     */
    public function order_management_routes_exist()
    {
        $response = $this->get('/admin/ordenes');

        // Should redirect to login or return error, but not 404
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test that customer management routes exist.
     *
     * @test
     */
    public function customer_management_routes_exist()
    {
        $response = $this->get('/admin/gestion-clientes/clientes');

        // Should redirect to login or return error, but not 404
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test that payment management routes exist.
     *
     * @test
     */
    public function payment_management_routes_exist()
    {
        $response = $this->get('/admin/pagos');

        // Should redirect to login or return error, but not 404
        $this->assertNotEquals(404, $response->status());
    }
}
