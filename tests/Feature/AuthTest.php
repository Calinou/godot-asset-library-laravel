<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\RefreshTestCase;

class AuthTest extends RefreshTestCase
{
    public function testAuthLoginView(): void
    {
        $response = $this->get('/login');
        $response->assertOk()->assertViewIs('auth.login');
    }

    public function testAuthRegisterView(): void
    {
        $response = $this->get('/register');
        $response->assertOk()->assertViewIs('auth.register');
    }

    public function testAuthForgotPasswordView(): void
    {
        $response = $this->get('/password/reset');
        $response->assertOk()->assertViewIs('auth.passwords.email');
    }

    public function testAuthPasswordResetView(): void
    {
        // The password reset view is displayed if any parameter is passed to the route
        // (even if it's invalid)
        $response = $this->get('/password/reset/something');
        $response->assertOk()->assertViewIs('auth.passwords.reset');
    }
}
