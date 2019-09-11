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

    public function testAssetShow()
    {
        $response = $this->get('/asset/1');

        $response->assertOk();
    }
}
