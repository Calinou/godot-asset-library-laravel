<?php

declare(strict_types=1);

namespace Database\Factories;

use App\AssetPreview;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetPreviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetPreview::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Hexadecimal color code of the form `ff22ff`
        $colorHex = str_pad(dechex($this->faker->numberBetween(0, 16777216)), 6, '0', STR_PAD_LEFT);

        return [
            // TODO: Generate video links
            'type_id' => AssetPreview::TYPE_IMAGE,
            'link' => "https://via.placeholder.com/1280x720/$colorHex.png",
            'thumbnail' => "https://via.placeholder.com/320x180/$colorHex.png",
            'caption' => $this->faker->text(50),
        ];
    }
}
