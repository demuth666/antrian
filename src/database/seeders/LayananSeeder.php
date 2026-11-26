<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            ['nama' => 'Pembuatan KTP-el', 'prefix' => 'A'],
            ['nama' => 'Kartu Keluarga', 'prefix' => 'B'],
            ['nama' => 'Akta kelahiran', 'prefix' => 'C'],
            ['nama' => 'Perubahan data', 'prefix' => 'D'],
            ['nama' => 'Lainnya', 'prefix' => 'E'],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(
                ['prefix' => $layanan['prefix']],
                ['nama' => $layanan['nama'], 'aktif' => true],
            );
        }
    }
}
