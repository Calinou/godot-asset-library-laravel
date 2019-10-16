<?php

use App\User;
use App\Asset;
use App\AssetPreview;
use App\AssetVersion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create users and associated assets
        factory(User::class, 20)->create()->each(function (User $user) {
            $user->assets()->saveMany(factory(Asset::class, 3)->make());
        });

        // Add previews and versions to each asset
        Asset::all()->each(function (Asset $asset) {
            $asset->previews()->saveMany(factory(AssetPreview::class, rand(1, 4))->make());
            $asset->versions()->saveMany(factory(AssetVersion::class, rand(1, 5))->make());
        });
    }
}
