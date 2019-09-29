<?php

namespace Tests\Feature;

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
        $response = $this->get('/');
        $response->assertOk()->assertViewIs('index');
    }

    public function testAssetIndexPaginationValid()
    {
        $response = $this->get('/?page=2');
        $response->assertOk()->assertViewIs('index');
    }

    public function testAssetIndexPaginationInvalid()
    {
        $response = $this->get('/?page=-5');
        $response->assertRedirect();
    }

    public function testAssetSearchCategoryValid()
    {
        $response = $this->get('/?category=0');
        $response->assertOk()->assertViewIs('index');
    }

    public function testAssetSearchCategoryInvalid()
    {
        $response = $this->get('/?category=-1');
        $response->assertRedirect();

        $response = $this->get('/?category=1234');
        $response->assertRedirect();
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
        $response->assertOk()->assertViewIs('asset');
    }
}
