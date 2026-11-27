<?php

namespace Tests\Feature;

use App\Models\Bantuan;
use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_beranda_dapat_ditampilkan(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('SIAP RENTAN MPP SUMEDANG')
            ->assertSeeText('Prioritas Pelayanan, Kesetaraan untuk Semua.');
    }

    public function test_halaman_formulir_dapat_ditampilkan(): void
    {
        $layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);

        $this->get(route('register'))
            ->assertOk()
            ->assertSeeText('Daftar layanan prioritas')
            ->assertSeeText('Formulir pendaftaran')
            ->assertSeeText('Penyandang disabilitas')
            ->assertSeeText($layanan->nama)
            ->assertSeeText($bantuan->nama);
    }

    public function test_pendaftaran_valid_diarahkan_ke_halaman_tiket(): void
    {
        $layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);

        $response = $this->post(route('register.store'), $this->dataPendaftaran([
            'layanan_id' => $layanan->id,
            'bantuan' => [$bantuan->id],
        ]));

        $response->assertRedirect();

        $this->get($response->headers->get('Location'))
            ->assertOk()
            ->assertSeeText('A-001')
            ->assertSeeText('Siti A.')
            ->assertSeeText('Pembuatan KTP-el')
            ->assertSee('data-modal-auto-open', false);
    }

    public function test_pendaftaran_tidak_valid_mengembalikan_kesalahan(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'kategori',
                'nama',
                'nik',
                'hp',
                'tanggal_lahir',
                'jenis_kelamin',
                'alamat',
                'layanan_id',
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function dataPendaftaran(array $data = []): array
    {
        return array_merge([
            'kategori' => 'disabilitas',
            'jenis_disabilitas' => 'fisik',
            'nama' => 'Siti Aminah',
            'nik' => '3273010101700001',
            'hp' => '081234567890',
            'tanggal_lahir' => '1970-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 1',
            'bantuan' => [],
            'nama_pendamping' => 'Budi',
        ], $data);
    }
}
