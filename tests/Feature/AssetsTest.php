<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    public function testAssetIndex()
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    /**
     * Tests the redirect for the old asset library homepage URL.
     * This ensures that existing links posted to the asset library don't break.
     */
    public function testAssetIndexRedirect()
    {
        $response = $this->get('/asset');

        $response->assertRedirect('/');
    }

    public function testAssetShow()
    {
        $response = $this->get('/asset/1');

        $response->assertOk();
    }
}
