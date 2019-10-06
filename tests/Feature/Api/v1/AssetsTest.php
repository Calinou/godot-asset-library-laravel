<?php

namespace Tests\Feature\Api\v1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetsTest extends TestCase
{
    // https://stackoverflow.com/questions/42350138/how-to-seed-database-migrations-for-laravel-tests
    use RefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }

    public function refreshDatabase(): void
    {
        $this->baseRefreshDatabase();
        $this->seed();
    }

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
                    'author',
                    'author_id',
                    'category',
                    'category_id',
                    'cost',
                    'godot_version',
                    'description',
                    'browse_url',
                    'download_url',
                    'icon_url',
                    'modify_date',
                    'support_level',
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
            'author',
            'author_id',
            'category',
            'category_id',
            'cost',
            'godot_version',
            'description',
            'browse_url',
            'download_url',
            'icon_url',
            'modify_date',
            'support_level',
        ]);
    }
}
