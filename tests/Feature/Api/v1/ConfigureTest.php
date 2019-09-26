<?php

namespace Tests\Feature\Api\v1;

use Tests\TestCase;

class ConfigureTest extends TestCase
{
    public function testConfigureIndexValid()
    {
        $response = $this->get('/api/v1/configure');
        $response->assertOk()->assertJson(['categories' => []]);

        $response = $this->get('/api/v1/configure?type=any');
        $response->assertOk()->assertJson(['categories' => []]);

        $response = $this->get('/api/v1/configure?type=addon');
        $response->assertOk()->assertJson(['categories' => []]);

        $response = $this->get('/api/v1/configure?type=project');
        $response->assertOk()->assertJson(['categories' => []]);
    }

    public function testConfigureIndexInvalid()
    {
        $response = $this->get('/api/v1/configure?type=something');
        $response->assertStatus(422);
    }
}
