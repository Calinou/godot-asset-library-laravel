<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    // https://stackoverflow.com/questions/42350138/how-to-seed-database-migrations-for-laravel-tests
    use RefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }

    public function refreshDatabase(): void
    {
        $this->baseRefreshDatabase();
        $this->seed();
    }

    public function testAdminIndexNotLoggedIn(): void
    {
        $response = $this->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInUser(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInAdmin(): void
    {
        $user = factory(User::class)->create();
        $user->is_admin = true;
        $response = $this->actingAs($user)->get('/admin');
        $response->assertOk()->assertViewIs('admin.index');
    }
}
