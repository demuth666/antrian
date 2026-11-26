<?php

namespace Tests\Feature;

use App\Enums\StatusAntrian;
use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\User;
use App\Services\AntrianService;
use Carbon\Carbon;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransisiStatusAntrianTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layanan;

    private AntrianService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->service = app(AntrianService::class);
    }

    public function test_transisi_yang_diizinkan_mengisi_waktu_dan_mencatat_log(): void
    {
        $antrian = $this->buatAntrian();
        $petugas = User::factory()->create();
        $loket = Loket::factory()->create(['nama' => 'Loket 1']);

        $this->assertSame(1, $antrian->logs()->count());

        $this->service->ubahStatus($antrian, StatusAntrian::Dipanggil, $petugas, $loket);
        $antrian->refresh();

        $this->assertSame(StatusAntrian::Dipanggil, $antrian->status);
        $this->assertNotNull($antrian->dipanggil_at);
        $this->assertSame($petugas->id, $antrian->petugas_id);
        $this->assertSame($loket->id, $antrian->loket_id);
        $this->assertSame(2, $antrian->logs()->count());

        $log = $antrian->logs()->latest('id')->firstOrFail();
        $this->assertSame(StatusAntrian::Menunggu, $log->status_dari);
        $this->assertSame(StatusAntrian::Dipanggil, $log->status_ke);
        $this->assertSame($petugas->id, $log->user_id);

        $this->service->ubahStatus($antrian, StatusAntrian::Dilayani);
        $this->assertNotNull($antrian->fresh()->mulai_dilayani_at);

        $this->service->ubahStatus($antrian, StatusAntrian::Selesai);
        $antrian->refresh();

        $this->assertSame(StatusAntrian::Selesai, $antrian->status);
        $this->assertNotNull($antrian->selesai_at);
        $this->assertSame(4, $antrian->logs()->count());
    }

    public function test_transisi_tidak_diizinkan_ditolak_dan_tidak_mencatat_log(): void
    {
        $antrian = $this->buatAntrian();

        try {
            $this->service->ubahStatus($antrian, StatusAntrian::Selesai);
            $this->fail('Transisi menunggu ke selesai seharusnya ditolak.');
        } catch (DomainException $exception) {
            $this->assertSame(
                'Status antrian tidak dapat diubah dari Menunggu ke Selesai.',
                $exception->getMessage(),
            );
        }

        $this->assertSame(StatusAntrian::Menunggu, $antrian->fresh()->status);
        $this->assertSame(1, $antrian->logs()->count());
    }

    public function test_tidak_hadir_dan_batal_sesuai_aturan(): void
    {
        $tidakHadir = $this->buatAntrian($this->nikKe(1));
        $this->service->ubahStatus($tidakHadir, StatusAntrian::Dipanggil);
        $this->service->ubahStatus($tidakHadir, StatusAntrian::TidakHadir);
        $this->assertSame(StatusAntrian::TidakHadir, $tidakHadir->fresh()->status);

        $batal = $this->buatAntrian($this->nikKe(2));
        $this->service->ubahStatus($batal, StatusAntrian::Batal);
        $this->assertSame(StatusAntrian::Batal, $batal->fresh()->status);

        $this->expectException(DomainException::class);
        $this->service->ubahStatus($tidakHadir->fresh(), StatusAntrian::Dilayani);
    }

    private function buatAntrian(string $nik = '3273010101700001'): Antrian
    {
        $hasil = $this->service->daftar([
            'kategori' => 'lansia',
            'nama' => 'Siti Aminah',
            'nik' => $nik,
            'hp' => '081234567890',
            'tanggal_lahir' => '1950-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 1',
            'layanan_id' => $this->layanan->id,
            'bantuan' => [],
        ]);

        return $hasil['antrian'];
    }

    private function nikKe(int $nomor): string
    {
        return '327301010170'.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
