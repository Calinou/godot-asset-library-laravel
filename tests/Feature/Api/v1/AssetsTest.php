<?php

declare(strict_types=1);

namespace Tests\Feature\Api\v1;

use Tests\TestCase;

class AssetsTest extends TestCase
{
    public function testAssetIndex(): void
    {
        $response = $this->get('/api/v1/asset');
        $response->assertOk()->assertJsonStructure([
            'page',
            'page_length',
            'pages',
            'total_items',
            'result' => [
                [
                    'asset_id',
                    'title',
                    'blurb',
                    'author',
                    'author_id',
                    'category',
                    'category_id',
                    'cost',
                    'godot_version',
                    'description',
                    'tags',
                    'browse_url',
                    'download_url',
                    'icon_url',
                    'modify_date',
                    'support_level',
                    'score',
                ],
            ],
        ]);
    }

    public function testAssetIndexPaginationValid(): void
    {
        $response = $this->get('/api/v1/asset?page=2');
        $response->assertOk();
    }

    public function testAssetIndexPaginationInvalid(): void
    {
        $response = $this->get('/api/v1/asset?page=-5');
        $response->assertStatus(422);
    }

    public function testAssetIndexMaxResultsValid(): void
    {
        $response = $this->get('/api/v1/asset?max_results=15');
        $response->assertJsonCount(15, 'result');
    }

    public function testAssetIndexMaxResultsInvalid(): void
    {
        $response = $this->get('/api/v1/asset?max_results=0');
        $response->assertStatus(422);
    }

    public function testAssetSearchCategoryValid(): void
    {
        $response = $this->get('/api/v1/asset/?category=0');
        $response->assertOk();
    }

    public function testAssetSearchCategoryInvalid(): void
    {
        $response = $this->get('/api/v1/asset/?category=-1');
        $response->assertStatus(422);

        $response = $this->get('/api/v1/asset/?category=1234');
        $response->assertStatus(422);
    }

    public function testAssetShow(): void
    {
        $response = $this->get('/api/v1/asset/1');
        $response->assertOk()->assertJsonStructure([
            'asset_id',
            'title',
            'blurb',
            'author',
            'author_id',
            'category',
            'category_id',
            'cost',
            'godot_version',
            'description',
            'tags',
            'browse_url',
            'download_url',
            'icon_url',
            'modify_date',
            'support_level',
            'score',
        ]);
    }
}
