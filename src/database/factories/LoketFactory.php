<?php

namespace Database\Factories;

use App\Models\Loket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loket>
 */
class LoketFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'Loket '.fake()->unique()->numberBetween(1, 20),
            'aktif' => true,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'aktif' => false,
        ]);
    }
}
