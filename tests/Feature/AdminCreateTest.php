<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the administrator creation command.
     */
    public function testAdminCreate()
    {
        $exitCode = Artisan::call('admin:create admin@example.com admin Administrator');

        $this->assertEquals(0, $exitCode);
    }
}
