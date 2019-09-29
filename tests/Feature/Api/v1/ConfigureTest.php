<?php

namespace Tests\Feature\Api\v1;

use Tests\TestCase;

class ConfigureTest extends TestCase
{
    /**
     * The JSON structure the response must follow to pass the test.
     */
    public const configureStructure = [
        'categories' => [
            [
                'id',
                'name',
                'type',
            ],
        ],
    ];

    public function testConfigureIndexValid()
    {
        $response = $this->get('/api/v1/configure');
        $response->assertOk()->assertJsonStructure(self::configureStructure);

        $response = $this->get('/api/v1/configure?type=any');
        $response->assertOk()->assertJsonStructure(self::configureStructure);

        $response = $this->get('/api/v1/configure?type=addon');
        $response->assertOk()->assertJsonStructure(self::configureStructure);

        $response = $this->get('/api/v1/configure?type=project');
        $response->assertOk()->assertJsonStructure(self::configureStructure);
    }

    public function testConfigureIndexInvalid()
    {
        $response = $this->get('/api/v1/configure?type=something');
        $response->assertStatus(422);
    }
}
