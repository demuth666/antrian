<?php

namespace Tests\Feature;

use App\Models\Antrian;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Models\Pendaftar;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layanan;

    private Layanan $layananLain;

    private Bantuan $bantuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->layananLain = Layanan::factory()->create(['nama' => 'Kartu Keluarga', 'prefix' => 'B']);
        $this->bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);
    }

    public function test_nomor_antrian_berurutan_per_layanan_per_hari_dan_reset_keesokan_hari(): void
    {
        $this->post(route('register.store'), $this->data(['nik' => $this->nikKe(1)]))->assertRedirect();
        $this->post(route('register.store'), $this->data(['nik' => $this->nikKe(2)]))->assertRedirect();
        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(3),
            'layanan_id' => $this->layananLain->id,
        ]))->assertRedirect();

        $this->assertSame([1, 2], $this->nomorHariIni($this->layanan->id));
        $this->assertSame([1], $this->nomorHariIni($this->layananLain->id));
        $this->assertSame('A-001', Antrian::query()->where('layanan_id', $this->layanan->id)->first()->nomor_tampil);
        $this->assertSame('B-001', Antrian::query()->where('layanan_id', $this->layananLain->id)->first()->nomor_tampil);

        $this->travelTo(now()->addDay());

        $this->post(route('register.store'), $this->data(['nik' => $this->nikKe(4)]))->assertRedirect();

        $this->assertSame([1], $this->nomorHariIni($this->layanan->id));
        $this->assertSame(4, Antrian::query()->count());
    }

    public function test_pendaftaran_kedua_dengan_nik_sama_tidak_membuat_nomor_baru(): void
    {
        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(1),
            'nama' => 'Siti Aminah',
        ]))->assertRedirect();

        $antrian = Antrian::query()->firstOrFail();

        $response = $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(1),
            'nama' => 'Siti Aminah',
            'layanan_id' => $this->layananLain->id,
            'hp' => '081298765432',
        ]));

        $response
            ->assertRedirect(route('queue.show', ['antrian' => $antrian]))
            ->assertSessionHas('status', 'Anda sudah terdaftar hari ini. Berikut tiket Anda.');

        $this->assertSame(1, Antrian::query()->count());
        $this->assertSame(1, Pendaftar::query()->count());
        $this->assertSame('6281298765432', $antrian->pendaftar->fresh()->no_hp);
    }

    public function test_validasi_nik_dan_jenis_disabilitas(): void
    {
        $this->post(route('register.store'), $this->data(['nik' => '123']))
            ->assertSessionHasErrors('nik');

        $this->post(route('register.store'), $this->data(['nik' => '32730101017000AB']))
            ->assertSessionHasErrors('nik');

        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(5),
            'hp' => '0812ABC45678',
        ]))->assertSessionHasErrors('hp');

        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(2),
            'kategori' => 'disabilitas',
            'jenis_disabilitas' => null,
        ]))->assertSessionHasErrors('jenis_disabilitas');

        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(3),
            'kategori' => 'lansia',
            'jenis_disabilitas' => 'fisik',
        ]))->assertSessionDoesntHaveErrors();

        $this->assertNull(Pendaftar::query()->firstOrFail()->jenis_disabilitas);
        $this->assertSame(1, Antrian::query()->count());
    }

    public function test_layanan_tidak_aktif_ditolak(): void
    {
        $nonaktif = Layanan::factory()->nonaktif()->create();

        $this->post(route('register.store'), $this->data([
            'nik' => $this->nikKe(1),
            'layanan_id' => $nonaktif->id,
        ]))->assertSessionHasErrors('layanan_id');

        $this->assertSame(0, Antrian::query()->count());
    }

    public function test_data_sensitif_disimpan_terenkripsi_dan_dinormalisasi(): void
    {
        $this->post(route('register.store'), $this->data([
            'nik' => '3273 0101 0170 0001',
            'hp' => '0812-3456-7890',
        ]))->assertRedirect();

        $baris = DB::table('pendaftars')->firstOrFail();
        $pendaftar = Pendaftar::query()->firstOrFail();

        $this->assertNotSame('3273010101700001', $baris->nik);
        $this->assertNotSame('6281234567890', $baris->no_hp);
        $this->assertSame('3273010101700001', $pendaftar->nik);
        $this->assertSame('6281234567890', $pendaftar->no_hp);
        $this->assertSame(
            hash_hmac('sha256', '3273010101700001', (string) config('app.key')),
            $baris->nik_hash,
        );
        $this->assertSame(
            hash_hmac('sha256', '6281234567890', (string) config('app.key')),
            $baris->hp_hash,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function data(array $data = []): array
    {
        return array_merge([
            'kategori' => 'lansia',
            'nama' => 'Siti Aminah',
            'nik' => $this->nikKe(1),
            'hp' => '081234567890',
            'tanggal_lahir' => '1950-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 1',
            'layanan_id' => $this->layanan->id,
            'bantuan' => [$this->bantuan->id],
            'nama_pendamping' => 'Budi',
        ], $data);
    }

    private function nikKe(int $nomor): string
    {
        return '327301010170'.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return array<int, int>
     */
    private function nomorHariIni(int $layananId): array
    {
        return Antrian::query()
            ->where('layanan_id', $layananId)
            ->whereDate('tanggal', now()->toDateString())
            ->orderBy('nomor')
            ->pluck('nomor')
            ->all();
    }
}
