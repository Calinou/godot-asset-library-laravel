<?php

declare(strict_types=1);

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Use the same name for username and email address to make email addresses
        // easily guessable. This makes it easier to log in as a given user
        // for development purposes.
        $firstName = $this->faker->firstName;
        $username = $firstName.$this->faker->numberBetween(0, 9);

        return [
            'username' => $username,
            'full_name' => $firstName.' '.$this->faker->lastName,
            'email' => Str::lower("$username@example.com"),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
