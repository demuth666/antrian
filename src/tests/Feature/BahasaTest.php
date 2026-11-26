<?php

namespace Tests\Feature;

use App\Models\Antrian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BahasaTest extends TestCase
{
    use RefreshDatabase;

    public function test_bahasa_indonesia_menjadi_bawaan(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Layanan yang')
            ->assertSee('Daftar sekarang')
            ->assertSee(route('language.switch', ['locale' => 'en']), false);
    }

    public function test_pengguna_dapat_beralih_ke_bahasa_inggris(): void
    {
        $this->from(route('home'))
            ->get(route('language.switch', ['locale' => 'en']))
            ->assertRedirect(route('home'))
            ->assertSessionHas('locale', 'en');
    }

    public function test_halaman_tampil_dalam_bahasa_inggris_setelah_beralih(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Priority Service')
            ->assertSee('Register now')
            ->assertSee('Check my queue');

        $this->withSession(['locale' => 'en'])
            ->get(route('register'))
            ->assertOk()
            ->assertSee('Register for priority service')
            ->assertSee('Full name');
    }

    public function test_label_status_polling_mengikuti_bahasa_yang_dipilih(): void
    {
        $antrian = Antrian::factory()->create();

        $this->withSession(['locale' => 'en'])
            ->getJson(route('api.queue.now-serving', ['kode' => $antrian->kode]))
            ->assertOk()
            ->assertJsonPath('tiket.status_label', 'Waiting');
    }

    public function test_pesan_validasi_mengikuti_bahasa_yang_dipilih(): void
    {
        $this->withSession(['locale' => 'en'])
            ->from(route('register'))
            ->post(route('register.store'), [])
            ->assertSessionHasErrors('nama');

        $this->assertSame(
            'The name field is required.',
            session('errors')->first('nama'),
        );
    }

    public function test_bahasa_yang_tidak_didukung_ditolak(): void
    {
        $this->get(route('language.switch', ['locale' => 'fr']))
            ->assertNotFound();
    }
}
