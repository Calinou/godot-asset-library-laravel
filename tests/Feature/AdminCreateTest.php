<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Tests\RefreshTestCase;

class AdminCreateTest extends RefreshTestCase
{
    /**
     * Test the administrator creation command.
     * This also checks whether the newly created user is actually
     * marked as an administrator after being created.
     */
    public function testAdminCreate()
    {
        $exitCode = Artisan::call('admin:create admin@example.com admin Administrator');
        $user = User::where(['email' => 'admin@example.com'])->first();

        $this->assertEquals(0, $exitCode);
        $this->assertTrue($user->is_admin);
    }
}
