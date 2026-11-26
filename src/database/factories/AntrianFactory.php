<?php

namespace Database\Factories;

use App\Enums\KategoriAntrian;
use App\Enums\StatusAntrian;
use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Pendaftar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Antrian>
 */
class AntrianFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pendaftar_id' => Pendaftar::factory(),
            'layanan_id' => Layanan::factory(),
            'loket_id' => null,
            'petugas_id' => null,
            'tanggal' => now()->toDateString(),
            'nomor' => fake()->unique()->numberBetween(1, 9999),
            'kode' => (string) Str::ulid(),
            'kategori' => fake()->randomElement(KategoriAntrian::cases()),
            'status' => StatusAntrian::Menunggu,
            'nama_pendamping' => null,
        ];
    }
}
