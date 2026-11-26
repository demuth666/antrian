<?php

namespace Database\Factories;

use App\Models\Bantuan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Bantuan>
 */
class BantuanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => Str::slug(fake()->unique()->words(2, true), '_'),
            'nama' => fake()->words(2, true),
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
