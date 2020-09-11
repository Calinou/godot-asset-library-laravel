<?php

declare(strict_types=1);

namespace Database\Factories;

use App\AssetVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetVersionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetVersion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'version_string' => $this->faker->regexify('[0-2]\.[0-9]\.[0-2]'),
            'godot_version' => $this->faker->randomElement(array_keys(AssetVersion::GODOT_VERSIONS)),
        ];
    }
}
