<?php

namespace Database\Factories;

use App\Enums\JenisKelamin;
use App\Models\Pendaftar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pendaftar>
 */
class PendaftarFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nik = fake()->unique()->numerify('################');
        $noHp = '628'.fake()->numerify('##########');

        return [
            'nik' => $nik,
            'nik_hash' => hash_hmac('sha256', $nik, (string) config('app.key')),
            'hp_hash' => hash_hmac('sha256', $noHp, (string) config('app.key')),
            'nama' => fake()->name(),
            'tanggal_lahir' => fake()->dateTimeBetween('-85 years', '-17 years')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement([JenisKelamin::LakiLaki, JenisKelamin::Perempuan]),
            'no_hp' => $noHp,
            'alamat' => fake()->address(),
            'jenis_disabilitas' => null,
        ];
    }

    public function disabilitas(string $jenis = 'fisik'): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_disabilitas' => $jenis,
        ]);
    }
}
