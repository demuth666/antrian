<?php

namespace App\Services;

use App\Enums\KategoriAntrian;
use App\Enums\StatusAntrian;
use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\Pendaftar;
use App\Models\User;
use App\Support\Identitas;
use DomainException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AntrianService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{antrian: Antrian, dibuat: bool}
     */
    public function daftar(array $data): array
    {
        $nik = Identitas::normalisasiNik((string) $data['nik']);

        if (strlen($nik) !== 16) {
            throw ValidationException::withMessages([
                'nik' => __('validation.digits', [
                    'attribute' => __('validation.attributes.nik'),
                    'digits' => 16,
                ]),
            ]);
        }

        $noHp = Identitas::normalisasiHp((string) $data['hp']);
        $nikHash = Identitas::hash($nik);
        $hpHash = Identitas::hash($noHp);
        $tanggal = now()->toDateString();

        return DB::transaction(function () use ($data, $nik, $nikHash, $hpHash, $noHp, $tanggal): array {
            $pendaftar = Pendaftar::query()
                ->where('nik_hash', $nikHash)
                ->lockForUpdate()
                ->first();

            $atributPendaftar = [
                'nama' => trim((string) $data['nama']),
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $noHp,
                'hp_hash' => $hpHash,
                'alamat' => trim((string) $data['alamat']),
                'jenis_disabilitas' => $data['kategori'] === KategoriAntrian::Disabilitas->value
                    ? ($data['jenis_disabilitas'] ?? null)
                    : null,
            ];

            if ($pendaftar) {
                $pendaftar->update($atributPendaftar);

                $antrianAktif = Antrian::query()
                    ->where('pendaftar_id', $pendaftar->id)
                    ->whereBetween('tanggal', $this->rentangTanggal($tanggal))
                    ->whereIn('status', $this->nilaiStatusAktif())
                    ->latest('id')
                    ->first();

                if ($antrianAktif) {
                    return [
                        'antrian' => $antrianAktif->load('layanan'),
                        'dibuat' => false,
                    ];
                }
            } else {
                $pendaftar = Pendaftar::query()->create([
                    ...$atributPendaftar,
                    'nik' => $nik,
                    'nik_hash' => $nikHash,
                ]);
            }

            $layanan = Layanan::query()
                ->whereKey($data['layanan_id'])
                ->where('aktif', true)
                ->firstOrFail();

            $antrianTerakhir = Antrian::query()
                ->where('layanan_id', $layanan->id)
                ->whereBetween('tanggal', $this->rentangTanggal($tanggal))
                ->orderByDesc('nomor')
                ->lockForUpdate()
                ->first();

            $antrian = Antrian::query()->create([
                'pendaftar_id' => $pendaftar->id,
                'layanan_id' => $layanan->id,
                'tanggal' => $tanggal,
                'nomor' => ($antrianTerakhir?->nomor ?? 0) + 1,
                'kode' => (string) Str::ulid(),
                'kategori' => $data['kategori'],
                'status' => StatusAntrian::Menunggu,
                'nama_pendamping' => $data['nama_pendamping'] ?? null,
            ]);

            $bantuan = array_map('intval', $data['bantuan'] ?? []);
            $antrian->bantuans()->sync($bantuan);

            $antrian->logs()->create([
                'status_dari' => null,
                'status_ke' => StatusAntrian::Menunggu->value,
                'catatan' => __('queue.registration_log'),
                'created_at' => now(),
            ]);

            return [
                'antrian' => $antrian->load('layanan'),
                'dibuat' => true,
            ];
        });
    }

    public function cariAntrianAktif(string $nik, string $noHp): ?Antrian
    {
        $pendaftar = Pendaftar::query()
            ->where('nik_hash', Identitas::hashNik($nik))
            ->where('hp_hash', Identitas::hashHp($noHp))
            ->first();

        if (! $pendaftar) {
            return null;
        }

        return Antrian::query()
            ->with(['layanan', 'loket'])
            ->where('pendaftar_id', $pendaftar->id)
            ->whereBetween('tanggal', $this->rentangTanggal(now()->toDateString()))
            ->whereIn('status', $this->nilaiStatusAktif())
            ->latest('id')
            ->first();
    }

    /**
     * Cari semua antrian hari ini berdasarkan NIK untuk keperluan petugas.
     *
     * @return Collection<int, Antrian>
     */
    public function cariAntrianHariIni(string $nik): Collection
    {
        $pendaftar = Pendaftar::query()
            ->where('nik_hash', Identitas::hashNik($nik))
            ->first();

        if (! $pendaftar) {
            return new Collection;
        }

        return Antrian::query()
            ->with(['layanan', 'loket'])
            ->where('pendaftar_id', $pendaftar->id)
            ->whereBetween('tanggal', $this->rentangTanggal(now()->toDateString()))
            ->orderByDesc('id')
            ->get();
    }

    public function panggilBerikutnya(User $petugas): ?Antrian
    {
        $loket = $petugas->loket;

        if (! $loket) {
            throw new DomainException(__('officer.flash.no_counter_services'));
        }

        $layananIds = $loket->layanans()->pluck('layanans.id');

        if ($layananIds->isEmpty()) {
            return null;
        }

        return DB::transaction(function () use ($layananIds, $petugas, $loket): ?Antrian {
            $antrian = Antrian::query()
                ->whereIn('layanan_id', $layananIds)
                ->whereBetween('tanggal', $this->rentangTanggal(now()->toDateString()))
                ->where('status', StatusAntrian::Menunggu)
                ->orderBy('created_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (! $antrian) {
                return null;
            }

            return $this->ubahStatus($antrian, StatusAntrian::Dipanggil, $petugas, $loket);
        });
    }

    public function ubahStatus(
        Antrian $antrian,
        StatusAntrian $tujuan,
        ?User $petugas = null,
        ?Loket $loket = null,
        ?string $catatan = null,
    ): Antrian {
        return DB::transaction(function () use ($antrian, $tujuan, $petugas, $loket, $catatan): Antrian {
            $antrian->refresh();
            $dari = $antrian->status;

            if (! $dari->bolehTransisiKe($tujuan)) {
                throw new DomainException(__('queue.transition_denied', [
                    'from' => $dari->label(),
                    'to' => $tujuan->label(),
                ]));
            }

            $perubahan = ['status' => $tujuan] + match ($tujuan) {
                StatusAntrian::Dipanggil => ['dipanggil_at' => now()],
                StatusAntrian::Dilayani => ['mulai_dilayani_at' => now()],
                StatusAntrian::Selesai => ['selesai_at' => now()],
                default => [],
            };

            if ($petugas) {
                $perubahan['petugas_id'] = $petugas->id;
            }

            if ($loket) {
                $perubahan['loket_id'] = $loket->id;
            }

            $antrian->update($perubahan);

            $antrian->logs()->create([
                'user_id' => $petugas?->id,
                'status_dari' => $dari->value,
                'status_ke' => $tujuan->value,
                'catatan' => $catatan ?? __('queue.status_log', ['status' => $tujuan->label()]),
                'created_at' => now(),
            ]);

            return $antrian->fresh(['layanan', 'loket']);
        });
    }

    public function hitungSisa(Antrian $antrian): int
    {
        if ($antrian->status !== StatusAntrian::Menunggu || ! $antrian->tanggal->isToday()) {
            return 0;
        }

        return Antrian::query()
            ->where('layanan_id', $antrian->layanan_id)
            ->whereBetween('tanggal', $this->rentangTanggal($antrian->tanggal->toDateString()))
            ->where('status', StatusAntrian::Menunggu)
            ->where('nomor', '<', $antrian->nomor)
            ->count();
    }

    public function teksSisa(Antrian $antrian): string
    {
        if ($antrian->status !== StatusAntrian::Menunggu) {
            return '';
        }

        $sisa = $this->hitungSisa($antrian);

        return $sisa > 0
            ? trans_choice('queue.remaining', $sisa, ['count' => $sisa])
            : __('queue.next_in_line');
    }

    /**
     * @return array<int, array{id: int, nama: string, nomor: int|null, nomor_tampil: string}>
     */
    public function statusLoketHariIni(): array
    {
        $dilayani = Antrian::query()
            ->with('layanan')
            ->whereBetween('tanggal', $this->rentangTanggal(now()->toDateString()))
            ->whereNotNull('loket_id')
            ->whereIn('status', [StatusAntrian::Dipanggil, StatusAntrian::Dilayani])
            ->orderByDesc('updated_at')
            ->get()
            ->unique('loket_id')
            ->keyBy('loket_id');

        return Loket::query()
            ->where('aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function (Loket $loket) use ($dilayani): array {
                $antrian = $dilayani->get($loket->id);

                return [
                    'id' => $loket->id,
                    'nama' => $loket->nama,
                    'nomor' => $antrian?->nomor,
                    'nomor_tampil' => $antrian?->nomor_tampil ?? '—',
                ];
            })
            ->all();
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function rentangTanggal(string $tanggal): array
    {
        return [$tanggal.' 00:00:00', $tanggal.' 23:59:59'];
    }

    /**
     * @return array<int, string>
     */
    private function nilaiStatusAktif(): array
    {
        return array_map(
            fn (StatusAntrian $status): string => $status->value,
            StatusAntrian::aktif(),
        );
    }
}
