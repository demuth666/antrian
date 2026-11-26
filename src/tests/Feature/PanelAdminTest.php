<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Antrian;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PanelAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Layanan $layanan;

    private Loket $loket;

    private Bantuan $bantuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-24 09:00:00', config('app.timezone')));

        $this->admin = User::factory()->admin()->create(['name' => 'Admin Layanan']);
        $this->layanan = Layanan::factory()->create(['nama' => 'Pembuatan KTP-el', 'prefix' => 'A']);
        $this->loket = Loket::factory()->create(['nama' => 'Loket 1']);
        $this->bantuan = Bantuan::factory()->create(['kode' => 'kursi_roda', 'nama' => 'Kursi roda']);
    }

    public function test_admin_dapat_masuk_dan_diarahkan_ke_dashboard(): void
    {
        $this->post(route('login.store'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_dapat_membuka_semua_halaman_pengelolaan(): void
    {
        $petugas = User::factory()->create(['loket_id' => $this->loket->id]);

        $this->actingAs($this->admin);

        $this->get(route('admin.services.index'))->assertOk();
        $this->get(route('admin.services.create'))->assertOk()->assertSee('Tambah layanan');
        $this->get(route('admin.services.edit', ['layanan' => $this->layanan]))->assertOk()->assertSee('Ubah layanan');

        $this->get(route('admin.counters.index'))->assertOk();
        $this->get(route('admin.counters.create'))->assertOk()->assertSee('Tambah loket');
        $this->get(route('admin.counters.edit', ['loket' => $this->loket]))->assertOk()->assertSee('Ubah loket');

        $this->get(route('admin.assistance.index'))->assertOk();
        $this->get(route('admin.assistance.create'))->assertOk()->assertSee('Tambah bantuan');
        $this->get(route('admin.assistance.edit', ['bantuan' => $this->bantuan]))->assertOk()->assertSee('Ubah bantuan');

        $this->get(route('admin.officers.index'))->assertOk();
        $this->get(route('admin.officers.create'))->assertOk()->assertSee('Tambah akun petugas');
        $this->get(route('admin.officers.edit', ['petugas' => $petugas]))->assertOk()->assertSee('Ubah akun petugas');
    }

    public function test_admin_dapat_mengelola_layanan(): void
    {
        $this->actingAs($this->admin);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Ringkasan');

        $this->post(route('admin.services.store'), [
            'nama' => 'Layanan Baru',
            'prefix' => 'z',
            'aktif' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('layanans', [
            'nama' => 'Layanan Baru',
            'prefix' => 'Z',
            'aktif' => 1,
        ]);

        $layanan = Layanan::query()->where('prefix', 'Z')->firstOrFail();

        $this->put(route('admin.services.update', ['layanan' => $layanan]), [
            'nama' => 'Layanan Diubah',
            'prefix' => 'Z',
            'aktif' => '0',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('layanans', [
            'id' => $layanan->id,
            'nama' => 'Layanan Diubah',
            'aktif' => 0,
        ]);

        $this->delete(route('admin.services.destroy', ['layanan' => $layanan]))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('layanans', ['id' => $layanan->id]);
    }

    public function test_notifikasi_simpan_tampil_di_kanan_atas_dan_hapus_memakai_modal(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.services.store'), [
            'nama' => 'Layanan Notifikasi',
            'prefix' => 'N',
            'aktif' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $this->get(route('admin.services.index'))
            ->assertOk()
            ->assertSee('class="toast-stack"', false)
            ->assertSee('class="toast ok"', false)
            ->assertSee('data-toast-close', false)
            ->assertSee('Data ditambahkan')
            ->assertSee('Layanan berhasil ditambahkan.')
            ->assertDontSee('id="notifikasi"', false)
            ->assertSee('id="konfirmasi"', false)
            ->assertSee('data-confirm-title="Hapus layanan ini?"', false)
            ->assertSee('data-confirm-button="Hapus layanan"', false)
            ->assertSee('data-confirm-accept', false);
    }

    public function test_layanan_yang_sudah_dipakai_tidak_dapat_dihapus(): void
    {
        Antrian::factory()->create(['layanan_id' => $this->layanan->id]);

        $this->actingAs($this->admin)
            ->delete(route('admin.services.destroy', ['layanan' => $this->layanan]))
            ->assertRedirect(route('admin.services.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('layanans', ['id' => $this->layanan->id]);
    }

    public function test_admin_dapat_mengelola_loket_dan_layanannya(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.counters.store'), [
            'nama' => 'Loket Baru',
            'aktif' => '1',
            'layanan' => [$this->layanan->id],
        ])->assertRedirect(route('admin.counters.index'));

        $loket = Loket::query()->where('nama', 'Loket Baru')->firstOrFail();

        $this->assertDatabaseHas('loket_layanan', [
            'loket_id' => $loket->id,
            'layanan_id' => $this->layanan->id,
        ]);

        $this->put(route('admin.counters.update', ['loket' => $loket]), [
            'nama' => 'Loket Diubah',
            'aktif' => '0',
            'layanan' => [],
        ])->assertRedirect(route('admin.counters.index'));

        $this->assertDatabaseHas('lokets', [
            'id' => $loket->id,
            'nama' => 'Loket Diubah',
            'aktif' => 0,
        ]);
        $this->assertDatabaseMissing('loket_layanan', ['loket_id' => $loket->id]);

        $this->delete(route('admin.counters.destroy', ['loket' => $loket]))
            ->assertRedirect(route('admin.counters.index'));

        $this->assertDatabaseMissing('lokets', ['id' => $loket->id]);
    }

    public function test_admin_dapat_mengelola_bantuan(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.assistance.store'), [
            'kode' => 'Kursi Roda Elektrik',
            'nama' => 'Kursi roda elektrik',
            'aktif' => '1',
        ])->assertRedirect(route('admin.assistance.index'));

        $this->assertDatabaseHas('bantuans', [
            'kode' => 'kursi_roda_elektrik',
            'nama' => 'Kursi roda elektrik',
        ]);

        $bantuan = Bantuan::query()->where('kode', 'kursi_roda_elektrik')->firstOrFail();

        $this->put(route('admin.assistance.update', ['bantuan' => $bantuan]), [
            'kode' => 'kursi_listrik',
            'nama' => 'Kursi listrik',
            'aktif' => '0',
        ])->assertRedirect(route('admin.assistance.index'));

        $this->assertDatabaseHas('bantuans', [
            'id' => $bantuan->id,
            'kode' => 'kursi_listrik',
            'aktif' => 0,
        ]);

        $this->delete(route('admin.assistance.destroy', ['bantuan' => $bantuan]))
            ->assertRedirect(route('admin.assistance.index'));

        $this->assertDatabaseMissing('bantuans', ['id' => $bantuan->id]);
    }

    public function test_admin_dapat_mengelola_akun_petugas_dan_kata_sandinya(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.officers.store'), [
            'name' => 'Petugas Baru',
            'email' => 'petugas.baru@example.test',
            'loket_id' => $this->loket->id,
            'aktif' => '1',
            'password' => 'Rahasia123',
        ])->assertRedirect(route('admin.officers.index'));

        $petugas = User::query()->where('email', 'petugas.baru@example.test')->firstOrFail();

        $this->assertSame(UserRole::Petugas, $petugas->role);
        $this->assertSame($this->loket->id, $petugas->loket_id);
        $this->assertTrue($petugas->aktif);
        $this->assertTrue(Hash::check('Rahasia123', $petugas->password));

        $this->put(route('admin.officers.update', ['petugas' => $petugas]), [
            'name' => 'Petugas Diubah',
            'email' => 'petugas.baru@example.test',
            'loket_id' => '',
            'aktif' => '0',
            'password' => '',
        ])->assertRedirect(route('admin.officers.index'));

        $petugas->refresh();

        $this->assertSame('Petugas Diubah', $petugas->name);
        $this->assertNull($petugas->loket_id);
        $this->assertFalse($petugas->aktif);
        $this->assertTrue(Hash::check('Rahasia123', $petugas->password));

        $this->put(route('admin.officers.update', ['petugas' => $petugas]), [
            'name' => 'Petugas Diubah',
            'email' => 'petugas.baru@example.test',
            'loket_id' => $this->loket->id,
            'aktif' => '1',
            'password' => 'SandiBaru123',
        ])->assertRedirect(route('admin.officers.index'));

        $this->assertTrue(Hash::check('SandiBaru123', $petugas->fresh()->password));

        $this->delete(route('admin.officers.destroy', ['petugas' => $petugas]))
            ->assertRedirect(route('admin.officers.index'));

        $this->assertDatabaseMissing('users', ['id' => $petugas->id]);
    }

    public function test_admin_tidak_dapat_membuka_panel_petugas(): void
    {
        $this->actingAs($this->admin)
            ->get(route('officer.dashboard'))
            ->assertForbidden();
    }

    public function test_petugas_biasa_tidak_dapat_mengakses_panel_admin(): void
    {
        $petugas = User::factory()->create(['loket_id' => $this->loket->id]);

        $this->actingAs($petugas)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
