<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Asset;
use App\User;
use Tests\RefreshTestCase;

class AssetsTest extends RefreshTestCase
{
    public const ASSET_DATA = [
        'title' => 'My Own Asset',
        'blurb' => 'One-line description of the asset',
        'description' => 'A long description…',
        'tags' => 'platformer, 2d, pixel-art, gdnative',
        'category_id' => Asset::CATEGORY_2D_TOOLS,
        'license' => 'MIT',
        'cost' => 'MIT',
        'versions' => [
            0 => [
                'version_string' => '1.0.0',
                'godot_version' => '3.x.x',
            ],
        ],
        'browse_url' => 'https://github.com/Calinou/godot-asset-library-laravel',
    ];

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
        $response->assertForbidden();
    }

    public function testAssetCreateLoggedIn(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/asset/submit');
        $response->assertOk()->assertViewIs('asset.create')->assertViewHas('editing', false);
    }

    public function testAssetSubmitNotLoggedIn(): void
    {
        $response = $this->post('/asset', self::ASSET_DATA);
        $response->assertForbidden();
    }

    public function testAssetSubmitLoggedIn(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseMissing('assets', ['author_id' => $user->id]);
        $response = $this->actingAs($user)->post('/asset', self::ASSET_DATA);
        $response->assertRedirect();
        $response->assertSessionHas('statusType', 'success');
        $this->assertDatabaseHas('assets', ['author_id' => $user->id]);
    }

    public function testAssetEditNotLoggedIn(): void
    {
        $response = $this->get('/asset/1/edit');
        $response->assertForbidden();
    }

    public function testAssetEditLoggedIn(): void
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $response = $this->actingAs($user)->get('/asset/1/edit');
        $response->assertOk()->assertViewIs('asset.create')->assertViewHas('editing', true);
    }
}
