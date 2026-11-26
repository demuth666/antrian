<?php

namespace Tests\Feature;

use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Pendaftar;
use App\Models\Ulasan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UlasanTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-26 09:00:00', config('app.timezone')));

        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
    }

    public function test_halaman_tiket_menampilkan_survei_kepuasan_setelah_mendaftar(): void
    {
        $antrian = $this->antrian();

        $this->get(route('queue.show', ['antrian' => $antrian]))
            ->assertOk()
            ->assertSeeText('Survei kepuasan')
            ->assertSeeText('Bagaimana pengalaman mendaftar Anda?')
            ->assertSeeText('Sangat puas')
            ->assertSee('data-modal-open="ulasan-'.$antrian->kode.'"', false)
            ->assertSee('action="'.route('queue.review.store', ['antrian' => $antrian]).'"', false)
            ->assertSee('name="rating"', false);
    }

    public function test_pengunjung_dapat_mengirim_ulasan_kepuasan(): void
    {
        $antrian = $this->antrian();

        $this->post(route('queue.review.store', ['antrian' => $antrian]), [
            'rating' => 5,
            'komentar' => '  Prosesnya cepat dan mudah.  ',
        ])
            ->assertRedirect(route('queue.show', ['antrian' => $antrian]))
            ->assertSessionHas('status', 'Terima kasih, penilaian Anda sudah kami terima.');

        $this->assertDatabaseHas('ulasans', [
            'antrian_id' => $antrian->id,
            'rating' => 5,
            'komentar' => 'Prosesnya cepat dan mudah.',
        ]);

        $this->get(route('queue.show', ['antrian' => $antrian]))
            ->assertOk()
            ->assertSeeText('Terima kasih atas penilaian Anda')
            ->assertSeeText('Prosesnya cepat dan mudah.')
            ->assertSeeText('Sangat puas')
            ->assertDontSee('action="'.route('queue.review.store', ['antrian' => $antrian]).'"', false);
    }

    public function test_rating_wajib_dan_dibatasi_satu_sampai_lima(): void
    {
        $antrian = $this->antrian();

        $this->from(route('queue.show', ['antrian' => $antrian]))
            ->post(route('queue.review.store', ['antrian' => $antrian]), [])
            ->assertRedirect(route('queue.show', ['antrian' => $antrian]))
            ->assertSessionHasErrors('rating');

        $this->from(route('queue.show', ['antrian' => $antrian]))
            ->post(route('queue.review.store', ['antrian' => $antrian]), ['rating' => 6])
            ->assertSessionHasErrors('rating');

        $this->assertDatabaseCount('ulasans', 0);
    }

    public function test_ulasan_hanya_dapat_dikirim_sekali_untuk_setiap_tiket(): void
    {
        $antrian = $this->antrian();

        $this->post(route('queue.review.store', ['antrian' => $antrian]), ['rating' => 4])
            ->assertSessionHas('status', 'Terima kasih, penilaian Anda sudah kami terima.');

        $this->post(route('queue.review.store', ['antrian' => $antrian]), ['rating' => 1])
            ->assertSessionHas('status', 'Penilaian untuk pendaftaran ini sudah dikirim.');

        $this->assertDatabaseCount('ulasans', 1);
        $this->assertDatabaseHas('ulasans', [
            'antrian_id' => $antrian->id,
            'rating' => 4,
        ]);
    }

    public function test_komentar_kosong_disimpan_sebagai_null(): void
    {
        $antrian = $this->antrian();

        $this->post(route('queue.review.store', ['antrian' => $antrian]), [
            'rating' => 3,
            'komentar' => '   ',
        ])->assertRedirect();

        $this->assertDatabaseHas('ulasans', [
            'antrian_id' => $antrian->id,
            'komentar' => null,
        ]);
    }

    public function test_dashboard_admin_menampilkan_ringkasan_ulasan(): void
    {
        $admin = User::factory()->admin()->create();
        $pertama = $this->antrian('Siti Aminah');
        $kedua = $this->antrian('Budi Santoso');

        Ulasan::factory()->for($pertama)->create(['rating' => 5, 'komentar' => 'Pelayanan ramah.']);
        Ulasan::factory()->for($kedua)->create(['rating' => 3, 'komentar' => null]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeText('Kepuasan pendaftaran')
            ->assertSeeText('2 ulasan')
            ->assertSee('4.0')
            ->assertSeeText('Pelayanan ramah.')
            ->assertSeeText('Tanpa catatan tambahan.')
            ->assertSeeText('Siti A.');
    }

    private function antrian(string $nama = 'Siti Aminah'): Antrian
    {
        return Antrian::factory()->create([
            'pendaftar_id' => Pendaftar::factory()->create(['nama' => $nama])->id,
            'layanan_id' => $this->layanan->id,
        ]);
    }
}
