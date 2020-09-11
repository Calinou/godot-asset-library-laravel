<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Hexadecimal color code of the form `ff22ff`
        $colorHex = str_pad(dechex($this->faker->numberBetween(0, 16777216)), 6, '0', STR_PAD_LEFT);

        return [
            'title' => rtrim($this->faker->text(28), '.'),
            'blurb' => rtrim($this->faker->text(60), '.'),
            'category_id' => $this->faker->numberBetween(0, Asset::CATEGORY_MAX - 1),
            'cost' => $this->faker->randomElement([
                'MIT',
                'GPL-3.0-or-later',
                'MPL-2.0',
                'CC-BY-SA-4.0',
            ]),
            'support_level_id' => $this->faker->numberBetween(0, Asset::SUPPORT_LEVEL_MAX - 1),
            'description' => $this->faker->text(500),
            'tags' => $this->faker->words($this->faker->numberBetween(0, 8)),
            'browse_url' => 'https://github.com/user/asset',
            // 50% chance of having a changelog and donation link set (as they're optional)
            'changelog_url' => $this->faker->boolean() ? 'https://github.com/user/asset/blob/master/CHANGELOG.md' : null,
            'donate_url' => $this->faker->boolean() ? 'https://patreon.com/user' : null,
            'icon_url' => "https://via.placeholder.com/128x128/$colorHex.png",
        ];
    }
}
