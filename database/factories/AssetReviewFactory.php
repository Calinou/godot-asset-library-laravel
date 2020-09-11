<?php

declare(strict_types=1);

namespace Database\Factories;

use App\AssetReview;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetReview::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $creationDate = $this->faker->dateTimeThisYear();

        return [
            'is_positive' => $this->faker->boolean(75),
            'comment' => $this->faker->text(250),
            'author_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'created_at' => $creationDate,
            'updated_at' => $creationDate,
        ];
    }
}
