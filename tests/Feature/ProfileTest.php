<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function testProfileEditView(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertOk()->assertViewIs('profile.edit');
    }
}
