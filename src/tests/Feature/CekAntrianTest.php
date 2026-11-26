<?php

namespace Tests\Feature;

use App\Enums\StatusAntrian;
use App\Http\Controllers\CekAntrianController;
use App\Http\Middleware\BatasiCekAntrian;
use App\Models\Antrian;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Services\AntrianService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekAntrianTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layanan;

    private Bantuan $bantuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);
    }

    public function test_halaman_cek_antrian_dapat_ditampilkan(): void
    {
        $this->get(route('check-queue'))
            ->assertOk()
            ->assertSee('Temukan')
            ->assertSee('name="nik"', false)
            ->assertSee('name="hp"', false)
            ->assertSee('Cek antrian saya');
    }

    public function test_cek_antrian_berhasil_dengan_nik_dan_hp_yang_cocok(): void
    {
        $antrian = $this->daftar('Siti Aminah', $this->nikKe(1), '081234567890');

        $this->post(route('check-queue.store'), [
            'nik' => '3273 0101 0170 0001',
            'hp' => '0812-3456-7890',
        ])
            ->assertRedirect(route('queue.show', ['antrian' => $antrian]))
            ->assertSessionHas('status', 'Tiket Anda ditemukan. Simpan halaman ini agar tiket tidak hilang.');

        $this->assertSame($antrian->kode, session('antrian_kode'));
    }

    public function test_cek_antrian_gagal_dengan_pesan_umum_tanpa_membedakan_nik_dan_hp(): void
    {
        $this->daftar('Siti Aminah', $this->nikKe(1), '081234567890');
        $this->daftar('Budi Santoso', $this->nikKe(2), '081298765432');

        $responsHpSalah = $this->post(route('check-queue.store'), [
            'nik' => $this->nikKe(1),
            'hp' => '081298765432',
        ]);

        $responsHpSalah
            ->assertRedirect(route('check-queue'))
            ->assertSessionHasErrors('cek_antrian');

        $pesanHpSalah = session('errors')->first('cek_antrian');

        $responsNikSalah = $this->post(route('check-queue.store'), [
            'nik' => $this->nikKe(9),
            'hp' => '081234567890',
        ]);

        $responsNikSalah
            ->assertRedirect(route('check-queue'))
            ->assertSessionHasErrors('cek_antrian');

        $this->assertSame($pesanHpSalah, session('errors')->first('cek_antrian'));
        $this->assertSame(CekAntrianController::PESAN_TIDAK_DITEMUKAN, $pesanHpSalah);
    }

    public function test_cek_antrian_tidak_menemukan_tiket_yang_sudah_selesai(): void
    {
        $antrian = $this->daftar('Siti Aminah', $this->nikKe(1), '081234567890');

        app(AntrianService::class)->ubahStatus($antrian, StatusAntrian::Batal);

        $this->post(route('check-queue.store'), [
            'nik' => $this->nikKe(1),
            'hp' => '081234567890',
        ])
            ->assertRedirect(route('check-queue'))
            ->assertSessionHasErrors('cek_antrian');
    }

    public function test_validasi_nik_dan_nomor_hp(): void
    {
        $this->post(route('check-queue.store'), [
            'nik' => '123',
            'hp' => '0812ABC45678',
        ])->assertSessionHasErrors(['nik', 'hp']);
    }

    public function test_cek_antrian_dibatasi_lima_percobaan_per_menit_per_ip(): void
    {
        for ($i = 1; $i <= BatasiCekAntrian::BATAS_PER_IP; $i++) {
            $this->post(route('check-queue.store'), [
                'nik' => $this->nikKe(100 + $i),
                'hp' => '081234567890',
            ])->assertRedirect(route('check-queue'));
        }

        $this->post(route('check-queue.store'), [
            'nik' => $this->nikKe(200),
            'hp' => '081234567890',
        ])
            ->assertRedirect(route('check-queue'))
            ->assertSessionHasErrors('cek_antrian');

        $antrian = $this->daftar('Siti Aminah', $this->nikKe(201), '081234567890');

        $this->travel(61)->seconds();

        $this->post(route('check-queue.store'), [
            'nik' => $this->nikKe(201),
            'hp' => '081234567890',
        ])->assertRedirect(route('queue.show', ['antrian' => $antrian]));
    }

    public function test_cek_antrian_dibatasi_per_nik_meski_ip_berbeda(): void
    {
        $nik = $this->nikKe(1);

        for ($i = 0; $i < BatasiCekAntrian::BATAS_PER_NIK; $i++) {
            $this->call('POST', route('check-queue.store'), [
                'nik' => $nik,
                'hp' => '081234567890',
            ], [], [], [
                'REMOTE_ADDR' => '10.0.0.'.($i + 1),
            ])->assertRedirect(route('check-queue'));
        }

        $this->call('POST', route('check-queue.store'), [
            'nik' => $nik,
            'hp' => '081234567890',
        ], [], [], [
            'REMOTE_ADDR' => '10.0.0.99',
        ])
            ->assertRedirect(route('check-queue'))
            ->assertSessionHasErrors('cek_antrian');
    }

    public function test_banner_tiket_aktif_dan_kode_tiket_tersedia_di_halaman_publik(): void
    {
        $antrian = $this->daftar('Siti Aminah', $this->nikKe(1), '081234567890');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Cek antrian saya')
            ->assertSee('data-antrian-banner', false)
            ->assertSee('Anda punya antrian aktif');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Cek antrian saya')
            ->assertSee('data-antrian-banner', false);

        $this->get(route('queue.show', ['antrian' => $antrian]))
            ->assertOk()
            ->assertSee('data-kode="'.$antrian->kode.'"', false);

        $this->getJson(route('api.queue.now-serving', ['kode' => $antrian->kode]))
            ->assertOk()
            ->assertJsonPath('tiket.aktif', true)
            ->assertJsonPath('tiket.nomor_tampil', 'A-001');

        app(AntrianService::class)->ubahStatus($antrian, StatusAntrian::Batal);

        $this->getJson(route('api.queue.now-serving', ['kode' => $antrian->kode]))
            ->assertOk()
            ->assertJsonPath('tiket.aktif', false);
    }

    private function daftar(string $nama, string $nik, string $hp): Antrian
    {
        $this->post(route('register.store'), [
            'kategori' => 'lansia',
            'nama' => $nama,
            'nik' => $nik,
            'hp' => $hp,
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
