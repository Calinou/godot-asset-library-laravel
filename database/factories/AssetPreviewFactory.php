<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AssetPreview;
use Faker\Generator as Faker;

$factory->define(AssetPreview::class, function (Faker $faker) {
    // Hexadecimal color code of the form `ff22ff`
    $colorHex = str_pad(dechex($faker->numberBetween(0, 16777216)), 6, '0', STR_PAD_LEFT);

    return [
        // TODO: Generate video links
        'type_id' => AssetPreview::TYPE_IMAGE,
        'link' => "https://via.placeholder.com/1280x720/$colorHex",
        'thumbnail' => "https://via.placeholder.com/320x180/$colorHex",
        'caption' => $faker->text(50),
    ];
});
