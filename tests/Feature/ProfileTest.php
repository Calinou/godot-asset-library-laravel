<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\RefreshTestCase;

class ProfileTest extends RefreshTestCase
{
    public function testProfileEditView(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertOk()->assertViewIs('profile.edit');
    }
}
