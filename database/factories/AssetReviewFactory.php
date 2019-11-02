<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AssetReview;
use Faker\Generator as Faker;

$factory->define(AssetReview::class, function (Faker $faker) {
    $creationDate = $faker->dateTimeThisYear();

    return [
        'is_positive' => $faker->boolean(75),
        'comment' => $faker->text(250),
        // The highest ID should be lower than the number of users created in DatabaseSeeder
        'author_id' => $faker->numberBetween(1, 10),
        'created_at' => $creationDate,
        'updated_at' => $creationDate,
    ];
});
