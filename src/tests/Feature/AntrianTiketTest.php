<?php

namespace Tests\Feature;

use App\Enums\StatusAntrian;
use App\Models\Antrian;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Models\Loket;
use App\Services\AntrianService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AntrianTiketTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layanan;

    private Loket $loket;

    private Bantuan $bantuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->loket = Loket::factory()->create(['nama' => 'Loket 1']);
        Loket::factory()->create(['nama' => 'Loket 2']);
        $this->bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);
    }

    public function test_halaman_tiket_menampilkan_nomor_nama_samar_qr_dan_noindex(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->nikKe(1));

        $response = $this->get(route('queue.show', ['antrian' => $antrian]));

        $response
            ->assertOk()
            ->assertSee('A-001')
            ->assertSee('Budi S.')
            ->assertDontSee('Budi Santoso')
            ->assertSee('<meta name="robots" content="noindex">', false)
            ->assertSee('<svg', false)
            ->assertSee('Menunggu giliran')
            ->assertSee('data-terminal="0"', false)
            ->assertSee('data-unduh-tiket', false)
            ->assertSee('Unduh tiket')
            ->assertDontSee('window.print()', false);
    }

    public function test_halaman_tiket_tidak_bisa_diakses_lewat_id_berurutan(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->nikKe(1));

        $this->get('/queue/'.$antrian->id)->assertNotFound();
    }

    public function test_endpoint_polling_mengembalikan_status_sisa_dan_loket_tanpa_data_pribadi(): void
    {
        $this->daftar('Budi Santoso', $this->nikKe(1));
        $this->daftar('Siti Aminah', $this->nikKe(2));
        $ketiga = $this->daftar('Andi Wijaya', $this->nikKe(3));

        $response = $this->getJson(route('api.queue.now-serving', ['kode' => $ketiga->kode]));

        $response
            ->assertOk()
            ->assertJsonPath('tiket.status', StatusAntrian::Menunggu->value)
            ->assertJsonPath('tiket.status_label', 'Menunggu')
            ->assertJsonPath('tiket.sisa', 2)
            ->assertJsonPath('tiket.terminal', false)
            ->assertJsonPath('loket.0.nama', 'Loket 1')
            ->assertJsonPath('loket.1.nama', 'Loket 2')
            ->assertJsonCount(2, 'loket');

        $isi = $response->getContent();

        $this->assertStringNotContainsString('Andi Wijaya', $isi);
        $this->assertStringNotContainsString($ketiga->pendaftar->nik, $isi);
        $this->assertStringNotContainsString($ketiga->pendaftar->no_hp, $isi);
        $this->assertStringNotContainsString('Jl. Merdeka', $isi);

        $response->assertJsonMissingPath('tiket.nama');
        $response->assertJsonMissingPath('tiket.nik');
        $response->assertJsonMissingPath('tiket.no_hp');
    }

    public function test_polling_menampilkan_nomor_loket_dan_status_terminal(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->nikKe(1));
        $service = app(AntrianService::class);

        $service->ubahStatus($antrian, StatusAntrian::Dipanggil, null, $this->loket);

        $this->getJson(route('api.queue.now-serving', ['kode' => $antrian->kode]))
            ->assertOk()
            ->assertJsonPath('tiket.status', 'dipanggil')
            ->assertJsonPath('tiket.loket', 'Loket 1')
            ->assertJsonPath('tiket.pesan', 'Nomor Anda dipanggil. Silakan menuju Loket 1.')
            ->assertJsonPath('loket.0.nomor', $antrian->nomor)
            ->assertJsonPath('loket.0.nomor_tampil', 'A-001');

        $service->ubahStatus($antrian, StatusAntrian::Dilayani);
        $service->ubahStatus($antrian, StatusAntrian::Selesai);

        $this->getJson(route('api.queue.now-serving', ['kode' => $antrian->kode]))
            ->assertOk()
            ->assertJsonPath('tiket.status', 'selesai')
            ->assertJsonPath('tiket.terminal', true)
            ->assertJsonPath('tiket.sisa', 0);

        $this->get(route('queue.show', ['antrian' => $antrian]))
            ->assertOk()
            ->assertSee('Layanan selesai')
            ->assertSee('data-terminal="1"', false);
    }

    public function test_endpoint_tanpa_kode_tetap_mengembalikan_daftar_loket(): void
    {
        $this->getJson(route('api.queue.now-serving'))
            ->assertOk()
            ->assertJsonPath('tiket', null)
            ->assertJsonCount(2, 'loket');
    }

    private function daftar(string $nama, string $nik): Antrian
    {
        $this->post(route('register.store'), [
            'kategori' => 'lansia',
            'nama' => $nama,
            'nik' => $nik,
            'hp' => '081234567890',
            'tanggal_lahir' => '1950-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 1',
            'layanan_id' => $this->layanan->id,
            'bantuan' => [$this->bantuan->id],
        ])->assertRedirect();

        return Antrian::query()->latest('id')->firstOrFail();
    }

    private function nikKe(int $nomor): string
    {
        return '327301010170'.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
