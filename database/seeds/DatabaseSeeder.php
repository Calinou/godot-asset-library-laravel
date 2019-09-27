<?php

use App\User;
use App\Asset;
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
        factory(User::class, 20)->create()->each(function (User $user) {
            $user->assets()->saveMany(factory(Asset::class, 3)->make());
        });
    }
}
