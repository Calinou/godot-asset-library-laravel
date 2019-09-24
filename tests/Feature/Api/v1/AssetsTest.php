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

    public function refreshDatabase()
    {
        $this->baseRefreshDatabase();
        $this->seed();
    }

    public function testAssetIndex()
    {
        $response = $this->get('/api/v1/asset');
        $response->assertOk();
    }

    public function testAssetIndexPaginationValid()
    {
        $response = $this->get('/api/v1/asset?page=2');
        $response->assertOk();
    }

    public function testAssetIndexPaginationInvalid()
    {
        $response = $this->get('/api/v1/asset?page=-5');
        $response->assertStatus(422);
    }

    public function testAssetIndexMaxResultsValid()
    {
        $response = $this->get('/api/v1/asset?max_results=15');
        $response->assertJsonCount(15, 'result');
    }

    public function testAssetIndexMaxResultsInvalid()
    {
        $response = $this->get('/api/v1/asset?max_results=0');
        $response->assertStatus(422);
    }

    public function testAssetSearchCategoryValid()
    {
        $response = $this->get('/api/v1/asset/?category_id=0');
        $response->assertOk();
    }

    public function testAssetSearchCategoryInvalid()
    {
        $response = $this->get('/api/v1/asset/?category_id=-1');
        $response->assertStatus(422);

        $response = $this->get('/api/v1/asset/?category_id=1234');
        $response->assertStatus(422);
    }

    public function testAssetShow()
    {
        $response = $this->get('/api/v1/asset/1');
        $response->assertOk();
    }
}
