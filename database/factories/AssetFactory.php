<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */
use Faker\Generator as Faker;

$factory->define(App\Asset::class, function (Faker $faker) {
    return [
        'title' => $faker->text(22),
        'author' => $faker->email,
        'category' => $faker->numberBetween(0, 5),
        'cost' => $faker->randomElement([
            'MIT',
            'GPL-3.0-or-later',
            'MPL-2.0',
            'CC-BY-SA-4.0',
        ]),
        'godot_version' => $faker->regexify('3\.[0-2]'),
        'description' => $faker->text(500),
        'browse_url' => 'https://github.com/user/asset',
        'download_url' => 'https://github.com/user/asset/archive/master.zip',
    ];
});
