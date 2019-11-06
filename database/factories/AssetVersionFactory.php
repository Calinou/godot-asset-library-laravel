<?php

declare(strict_types=1);

/* @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AssetVersion;
use Faker\Generator as Faker;

$factory->define(AssetVersion::class, function (Faker $faker) {
    return [
        'version_string' => $faker->regexify('[0-2]\.[0-9]\.[0-2]'),
        'godot_version' => $faker->regexify('3\.[0-2]'),
    ];
});
