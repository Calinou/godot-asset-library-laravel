<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function testAdminIndexNotLoggedIn(): void
    {
        $response = $this->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInUser(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInAdmin(): void
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $response = $this->actingAs($user)->get('/admin');
        $response->assertOk()->assertViewIs('admin.index');
    }
}
