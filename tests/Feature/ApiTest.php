<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function testAssetIndex()
    {
        $response = $this->get('/api/asset');

        $response->assertOk();
    }

    public function testAssetSingle()
    {
        $response = $this->get('/api/asset/1');

        $response->assertOk();
    }
}
