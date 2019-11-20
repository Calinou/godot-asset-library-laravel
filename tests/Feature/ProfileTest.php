<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
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

    public function testProfileEditView(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertOk()->assertViewIs('profile.edit');
    }
}
