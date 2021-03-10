<?php

declare(strict_types=1);

namespace Tests\Feature\Api\v1;

use Tests\RefreshTestCase;

class ConfigureTest extends RefreshTestCase
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

    public function testConfigureIndexValid(): void
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

    public function testConfigureIndexInvalid(): void
    {
        $response = $this->get('/api/v1/configure?type=something');
        $response->assertStatus(422);
    }
}
