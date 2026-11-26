<?php

namespace Database\Factories;

use App\Models\Antrian;
use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ulasan>
 */
class UlasanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'antrian_id' => Antrian::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'komentar' => fake()->optional()->sentence(),
        ];
    }
}
