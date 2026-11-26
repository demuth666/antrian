<?php

namespace Database\Seeders;

use App\Models\Bantuan;
use Illuminate\Database\Seeder;

class BantuanSeeder extends Seeder
{
    public function run(): void
    {
        $bantuans = [
            ['kode' => 'kursi_roda', 'nama' => 'Kursi roda'],
            ['kode' => 'pendamping', 'nama' => 'Petugas pendamping'],
            ['kode' => 'bahasa_isyarat', 'nama' => 'Juru bahasa isyarat'],
            ['kode' => 'pembaca', 'nama' => 'Dibacakan petugas'],
            ['kode' => 'loket_bawah', 'nama' => 'Loket rendah'],
            ['kode' => 'ruang_tenang', 'nama' => 'Ruang tenang'],
        ];

        foreach ($bantuans as $bantuan) {
            Bantuan::updateOrCreate(
                ['kode' => $bantuan['kode']],
                ['nama' => $bantuan['nama'], 'aktif' => true],
            );
        }
    }
}
