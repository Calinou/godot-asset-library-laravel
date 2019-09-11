<?php

namespace Tests\Feature\Api\v1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    public function testAssetIndex()
    {
        $response = $this->get('/api/v1/asset');

        $response->assertOk();
    }

    public function testAssetShow()
    {
        $response = $this->get('/api/v1/asset/1');

        $response->assertOk();
    }
}
