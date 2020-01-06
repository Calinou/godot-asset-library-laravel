<?php

declare(strict_types=1);

use App\Asset;
use App\AssetPreview;
use App\AssetReview;
use App\AssetVersion;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users and associated assets
        factory(User::class, 10)->create()->each(function (User $user) {
            $user->assets()->saveMany(factory(Asset::class, 3)->make());
        });

        // Add previews and versions to each asset
        Asset::all()->each(function (Asset $asset) {
            $asset->previews()->saveMany(factory(AssetPreview::class, rand(0, 4))->make());
            $asset->versions()->saveMany(factory(AssetVersion::class, rand(1, 5))->make());
            $asset->reviews()->saveMany(factory(AssetReview::class, rand(0, 5))->make());
        });
    }
}
