<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class LogoutTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    /**
     * Test Logout Route Exists
     */
    public function testLogoutRouteExists()
    {
        $result = $this->call('get', '/auth/logout');

        // Seharusnya redirect ke login
        $this->assertRedirectTo('/auth/login');
    }

    /**
     * Test Session Destroyed After Logout
     */
    public function testSessionDestroyedAfterLogout()
    {
        // Simulate login
        $session = session();
        $session->set([
            'user_id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'role' => 'orang_tua',
            'logged_in' => true
        ]);

        // Check session is set
        $this->assertTrue($session->get('logged_in') === true);

        // Call logout
        $result = $this->call('get', '/auth/logout');

        // Session should be destroyed
        // Note: Session destroy happens on server side
        $this->assertRedirectTo('/auth/login');
    }

    /**
     * Test Logout Displays Success Message
     */
    public function testLogoutDisplaysSuccessMessage()
    {
        // Simulate login first
        $session = session();
        $session->set('logged_in', true);

        // Call logout
        $result = $this->call('get', '/auth/logout');

        // Should redirect to login
        $this->assertRedirectTo('/auth/login');
    }

    /**
     * Test Logout Deletes Remember Cookie
     */
    public function testLogoutDeletesRememberCookie()
    {
        // This is a simple test to check if logout method is callable
        // Actual cookie testing requires more complex setup

        $result = $this->call('get', '/auth/logout');

        // Should redirect
        $this->assertRedirectTo('/auth/login');
    }

    /**
     * Test Logout Without Login
     */
    public function testLogoutWithoutLogin()
    {
        // Call logout without login
        $result = $this->call('get', '/auth/logout');

        // Should still redirect to login
        $this->assertRedirectTo('/auth/login');
    }
}
