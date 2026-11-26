<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Loket;
use Illuminate\Database\Seeder;

class LoketSeeder extends Seeder
{
    public function run(): void
    {
        $layananIds = Layanan::query()->pluck('id');

        foreach (['Loket prioritas 1', 'Loket prioritas 2'] as $nama) {
            $loket = Loket::updateOrCreate(
                ['nama' => $nama],
                ['aktif' => true],
            );

            $loket->layanans()->sync($layananIds);
        }
    }
}
