<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\Asset;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetsTest extends TestCase
{
    // https://stackoverflow.com/questions/42350138/how-to-seed-database-migrations-for-laravel-tests
    use RefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }

    public const ASSET_DATA = [
        'title' => 'My Own Asset',
        'blurb' => 'One-line description of the asset',
        'description' => 'A long description…',
        'tags' => 'platformer, 2d, pixel-art, gdnative',
        'category' => Asset::CATEGORY_2D_TOOLS,
        'license' => 'MIT',
        'versions[0][version_string]' => '1.0.0',
        'versions[0][godot_version]' => '3.2',
        'browse_url' => 'https://github.com/user/asset',
    ];

    public function refreshDatabase(): void
    {
        $this->baseRefreshDatabase();
        $this->seed();
    }

    public function testAssetIndex(): void
    {
        $response = $this->get('/');
        $response->assertOk()->assertViewIs('asset.index');
    }

    public function testAssetIndexPaginationValid(): void
    {
        $response = $this->get('/?page=2');
        $response->assertOk()->assertViewIs('asset.index');
    }

    public function testAssetIndexPaginationInvalid(): void
    {
        $response = $this->get('/?page=-5');
        $response->assertRedirect();
    }

    public function testAssetIndexMaxResultsValid(): void
    {
        $response = $this->get('/?max_results=15');
        $response->assertOk()->assertViewIs('asset.index');
    }

    public function testAssetIndexMaxResultsInvalid(): void
    {
        $response = $this->get('/?max_results=0');
        $response->assertRedirect();
    }

    public function testAssetSearchCategoryValid(): void
    {
        $response = $this->get('/?category=0');
        $response->assertOk()->assertViewIs('asset.index');
    }

    public function testAssetSearchCategoryInvalid(): void
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
    public function testAssetIndexRedirect(): void
    {
        $response = $this->get('/asset');
        $response->assertRedirect('/');
    }

    public function testAssetShow(): void
    {
        $response = $this->get('/asset/1');
        $response->assertOk()->assertViewIs('asset.show');
    }

    public function testAssetCreateNotLoggedIn(): void
    {
        $response = $this->get('/asset/submit');
        $response->assertRedirect('/email/verify');
    }

    public function testAssetCreateLoggedIn(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/asset/submit');
        $response->assertOk()->assertViewIs('asset.create')->assertViewHas('editing', false);
    }

    public function testAssetSubmitNotLoggedIn(): void
    {
        $response = $this->post('/asset', self::ASSET_DATA);
        $response->assertRedirect('/email/verify');
    }

    public function testAssetSubmitLoggedIn(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->post('/asset', self::ASSET_DATA);
        $response->assertRedirect('/');
    }

    public function testAssetEditNotLoggedIn(): void
    {
        $response = $this->get('/asset/1/edit');
        $response->assertForbidden();
    }

    public function testAssetEditLoggedIn(): void
    {
        $user = factory(User::class)->create();
        $user->is_admin = true;
        $response = $this->actingAs($user)->get('/asset/1/edit');
        $response->assertOk()->assertViewIs('asset.create')->assertViewHas('editing', true);
    }
}
