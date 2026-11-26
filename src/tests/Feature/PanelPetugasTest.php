<?php

namespace Tests\Feature;

use App\Enums\StatusAntrian;
use App\Models\Antrian;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\User;
use App\Services\AntrianService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelPetugasTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layananA;

    private Layanan $layananB;

    private Loket $loketA;

    private Loket $loketB;

    private User $petugasA;

    private User $petugasB;

    private Bantuan $bantuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->layananA = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->layananB = Layanan::factory()->create(['nama' => 'Kartu Keluarga', 'prefix' => 'B']);

        $this->loketA = Loket::factory()->create(['nama' => 'Loket 1']);
        $this->loketB = Loket::factory()->create(['nama' => 'Loket 2']);

        $this->loketA->layanans()->attach($this->layananA);
        $this->loketB->layanans()->attach($this->layananB);

        $this->petugasA = User::factory()->create([
            'name' => 'Petugas Satu',
            'loket_id' => $this->loketA->id,
        ]);
        $this->petugasB = User::factory()->create([
            'name' => 'Petugas Dua',
            'loket_id' => $this->loketB->id,
        ]);

        $this->bantuan = Bantuan::factory()->create([
            'kode' => 'kursi_roda',
            'nama' => 'Kursi roda',
        ]);
    }

    public function test_halaman_masuk_dapat_ditampilkan(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false);
    }

    public function test_tamu_diarahkan_ke_halaman_masuk(): void
    {
        $this->get(route('officer.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_petugas_dapat_masuk_dan_diarahkan_ke_panel(): void
    {
        $this->post(route('login.store'), [
            'email' => $this->petugasA->email,
            'password' => 'password',
        ])->assertRedirect(route('officer.dashboard'));

        $this->assertAuthenticatedAs($this->petugasA);
    }

    public function test_akun_nonaktif_tidak_dapat_masuk(): void
    {
        $nonaktif = User::factory()->nonaktif()->create([
            'loket_id' => $this->loketA->id,
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $nonaktif->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_petugas_hanya_melihat_antrian_layanan_loketnya(): void
    {
        $antrianA = $this->daftar('Budi Santoso', $this->layananA, 1);
        $this->daftar('Siti Aminah', $this->layananB, 2);

        $response = $this->actingAs($this->petugasA)->get(route('officer.dashboard'));

        $response
            ->assertOk()
            ->assertSee('A-001')
            ->assertSee('Budi Santoso')
            ->assertSee('Kursi roda')
            ->assertSee('Lansia')
            ->assertDontSee('B-001')
            ->assertDontSee('Siti Aminah')
            ->assertDontSee($antrianA->pendaftar->nik)
            ->assertDontSee($antrianA->pendaftar->no_hp);
    }

    public function test_petugas_tanpa_loket_mendapat_pesan_jelas(): void
    {
        $tanpaLoket = User::factory()->create(['loket_id' => null]);

        $this->actingAs($tanpaLoket)
            ->get(route('officer.dashboard'))
            ->assertOk()
            ->assertSee('belum ditugaskan ke loket');
    }

    public function test_halaman_pencarian_tiket_dapat_ditampilkan(): void
    {
        $this->actingAs($this->petugasA)
            ->get(route('officer.find-ticket'))
            ->assertOk()
            ->assertSee('Cari tiket warga')
            ->assertSee('name="nik"', false);
    }

    public function test_akun_yang_dinonaktifkan_langsung_dikeluarkan(): void
    {
        $this->petugasA->update(['aktif' => false]);

        $this->actingAs($this->petugasA)
            ->get(route('officer.dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_panggil_berikutnya_mengikuti_urutan_nomor_dan_mencatat_petugas_loket(): void
    {
        $pertama = $this->daftar('Budi Santoso', $this->layananA, 1);
        $kedua = $this->daftar('Siti Aminah', $this->layananA, 2);

        $this->actingAs($this->petugasA)
            ->post(route('officer.queue.call-next'))
            ->assertRedirect(route('officer.dashboard'))
            ->assertSessionHas('status');

        $pertama->refresh();
        $kedua->refresh();

        $this->assertSame(StatusAntrian::Dipanggil, $pertama->status);
        $this->assertSame($this->petugasA->id, $pertama->petugas_id);
        $this->assertSame($this->loketA->id, $pertama->loket_id);
        $this->assertNotNull($pertama->dipanggil_at);
        $this->assertSame(StatusAntrian::Menunggu, $kedua->status);
        $this->assertSame(2, $pertama->logs()->count());

        $this->post(route('officer.queue.call-next'))
            ->assertRedirect(route('officer.dashboard'));

        $this->assertSame(StatusAntrian::Dipanggil, $kedua->fresh()->status);
    }

    public function test_panel_hanya_menampilkan_satu_tombol_panggil_berikutnya(): void
    {
        $this->loketA->layanans()->attach($this->layananB);

        $this->daftar('Budi Santoso', $this->layananA, 1);
        $this->daftar('Siti Aminah', $this->layananB, 2);

        $response = $this->actingAs($this->petugasA)->get(route('officer.dashboard'));

        $response->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), 'Panggil berikutnya'));
    }

    public function test_panggil_berikutnya_menggabungkan_layanan_loket_sesuai_urutan_kedatangan(): void
    {
        $this->loketA->layanans()->attach($this->layananB);

        $pertama = $this->daftar('Budi Santoso', $this->layananB, 1);
        $kedua = $this->daftar('Siti Aminah', $this->layananA, 2);

        $this->actingAs($this->petugasA)
            ->post(route('officer.queue.call-next'))
            ->assertRedirect(route('officer.dashboard'))
            ->assertSessionHas('status');

        $this->assertSame(StatusAntrian::Dipanggil, $pertama->fresh()->status);
        $this->assertSame(StatusAntrian::Menunggu, $kedua->fresh()->status);

        $this->post(route('officer.queue.call-next'))
            ->assertRedirect(route('officer.dashboard'));

        $this->assertSame(StatusAntrian::Dipanggil, $kedua->fresh()->status);
    }

    public function test_petugas_tidak_bisa_memanggil_antrian_layanan_lain(): void
    {
        $antrianB = $this->daftar('Siti Aminah', $this->layananB, 1);

        $this->actingAs($this->petugasA)
            ->post(route('officer.queue.call-next'))
            ->assertRedirect(route('officer.dashboard'))
            ->assertSessionHas('error', 'Tidak ada antrian yang menunggu.');

        $this->actingAs($this->petugasA)
            ->post(route('officer.queue.start', ['antrian' => $antrianB]))
            ->assertForbidden();

        $this->assertSame(StatusAntrian::Menunggu, $antrianB->fresh()->status);
    }

    public function test_petugas_dapat_melanjutkan_status_sampai_selesai(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->layananA, 1);

        $this->actingAs($this->petugasA);
        $this->post(route('officer.queue.call-next'));

        $this->post(route('officer.queue.start', ['antrian' => $antrian]))
            ->assertRedirect(route('officer.dashboard'));
        $this->assertSame(StatusAntrian::Dilayani, $antrian->fresh()->status);
        $this->assertNotNull($antrian->fresh()->mulai_dilayani_at);

        $this->post(route('officer.queue.finish', ['antrian' => $antrian]))
            ->assertRedirect(route('officer.dashboard'));

        $antrian->refresh();

        $this->assertSame(StatusAntrian::Selesai, $antrian->status);
        $this->assertNotNull($antrian->selesai_at);
        $this->assertSame(4, $antrian->logs()->count());
    }

    public function test_petugas_dapat_menandai_tidak_hadir_dan_membatalkan(): void
    {
        $pertama = $this->daftar('Budi Santoso', $this->layananA, 1);
        $kedua = $this->daftar('Siti Aminah', $this->layananA, 2);

        $this->actingAs($this->petugasA);
        $this->post(route('officer.queue.call-next'));
        $this->post(route('officer.queue.call-next'));

        $this->post(route('officer.queue.mark-absent', ['antrian' => $pertama]))
            ->assertRedirect(route('officer.dashboard'));
        $this->post(route('officer.queue.cancel', ['antrian' => $kedua]))
            ->assertRedirect(route('officer.dashboard'));

        $this->assertSame(StatusAntrian::TidakHadir, $pertama->fresh()->status);
        $this->assertSame(StatusAntrian::Batal, $kedua->fresh()->status);
    }

    public function test_transisi_tidak_sah_ditolak_dengan_pesan_indonesia(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->layananA, 1);

        $this->actingAs($this->petugasA)
            ->post(route('officer.queue.finish', ['antrian' => $antrian]))
            ->assertRedirect(route('officer.dashboard'))
            ->assertSessionHas('error', 'Status antrian tidak dapat diubah dari Menunggu ke Selesai.');

        $this->assertSame(StatusAntrian::Menunggu, $antrian->fresh()->status);
    }

    public function test_pencarian_tiket_lewat_nik_menampilkan_tiket_tanpa_data_sensitif(): void
    {
        $antrian = $this->daftar('Budi Santoso', $this->layananA, 1);

        $response = $this->actingAs($this->petugasA)
            ->post(route('officer.find-ticket.store'), ['nik' => $antrian->pendaftar->nik]);

        $response
            ->assertOk()
            ->assertSee('A-001')
            ->assertSee('Budi Santoso')
            ->assertSee('Pembuatan KTP-el')
            ->assertDontSee($antrian->pendaftar->nik)
            ->assertDontSee($antrian->pendaftar->no_hp);
    }

    public function test_pencarian_tiket_tidak_ditemukan_memberi_pesan_generik(): void
    {
        $this->actingAs($this->petugasA)
            ->post(route('officer.find-ticket.store'), ['nik' => $this->nikKe(99)])
            ->assertOk()
            ->assertSee('Tidak ada antrian hari ini untuk NIK tersebut');
    }

    public function test_petugas_tidak_dapat_membuka_panel_admin(): void
    {
        $this->actingAs($this->petugasA)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    private function daftar(
        string $nama,
        Layanan $layanan,
        int $nomor,
        array $data = [],
    ): Antrian {
        $hasil = app(AntrianService::class)->daftar(array_merge([
            'kategori' => 'lansia',
            'nama' => $nama,
            'nik' => $this->nikKe($nomor),
            'hp' => '081234567890',
            'tanggal_lahir' => '1950-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 1',
            'layanan_id' => $layanan->id,
            'bantuan' => [$this->bantuan->id],
        ], $data));

        return $hasil['antrian'];
    }

    private function nikKe(int $nomor): string
    {
        return '327301010170'.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
