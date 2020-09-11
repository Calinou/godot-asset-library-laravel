<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Asset;
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
        // TODO: Figure out how to have multiple authors.
        Asset::factory()
            ->times(30)
            ->for(User::factory(), 'author')
            ->hasPreviews(3)
            ->hasVersions(3)
            ->hasReviews(3)
            ->create();
    }
}
