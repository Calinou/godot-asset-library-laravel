<?php

declare(strict_types=1);

/* @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    // Use the same name for username and email address to make email addresses
    // easily guessable. This makes it easier to log in as a given user
    // for development purposes.
    $firstName = $faker->firstName;
    $username = $firstName.$faker->numberBetween(0, 9);

    return [
        'username' => $username,
        'full_name' => "$firstName $faker->lastName",
        'email' => Str::lower("$username@example.com"),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
