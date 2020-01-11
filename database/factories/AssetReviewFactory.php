<?php

declare(strict_types=1);

/* @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AssetReview;
use App\User;
use Faker\Generator as Faker;

$factory->define(AssetReview::class, function (Faker $faker) {
    $creationDate = $faker->dateTimeThisYear();

    return [
        'is_positive' => $faker->boolean(75),
        'comment' => $faker->text(250),
        'author_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'created_at' => $creationDate,
        'updated_at' => $creationDate,
    ];
});
