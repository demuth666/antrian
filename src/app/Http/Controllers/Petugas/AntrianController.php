<?php

namespace App\Http\Controllers\Petugas;

use App\Enums\StatusAntrian;
use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Services\AntrianService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AntrianController extends Controller
{
    public function index(Request $request): View
    {
        $petugas = $request->user()->loadMissing('loket');

        $layanans = $petugas->loket
            ? $petugas->loket->layanans()->orderBy('nama')->get()
            : collect();

        $antrians = $layanans->isEmpty()
            ? collect()
            : Antrian::query()
                ->with(['pendaftar', 'bantuans', 'layanan'])
                ->whereIn('layanan_id', $layanans->pluck('id'))
                ->whereDate('tanggal', now()->toDateString())
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

        $sedangDilayani = $antrians
            ->filter(fn (Antrian $antrian) => in_array(
                $antrian->status,
                [StatusAntrian::Dipanggil, StatusAntrian::Dilayani],
                true,
            ))
            ->sortBy(fn (Antrian $antrian): string => sprintf(
                '%d-%013d',
                $antrian->status === StatusAntrian::Dilayani ? 0 : 1,
                $antrian->dipanggil_at?->getTimestamp() ?? 0,
            ))
            ->values();

        $menunggu = $antrians
            ->filter(fn (Antrian $antrian) => $antrian->status === StatusAntrian::Menunggu)
            ->sortBy('created_at')
            ->values();

        $ditutup = $antrians
            ->filter(fn (Antrian $antrian) => $antrian->status->terminal())
            ->sortByDesc(fn (Antrian $antrian): int => ($antrian->selesai_at ?? $antrian->updated_at)?->getTimestamp() ?? 0)
            ->values();

        return view('petugas.dashboard', [
            'petugas' => $petugas,
            'layanans' => $layanans,
            'antrians' => $antrians,
            'sedangDilayani' => $sedangDilayani,
            'menunggu' => $menunggu,
            'ditutup' => $ditutup,
            'jumlahMenunggu' => $menunggu->count(),
        ]);
    }

    public function panggilBerikutnya(
        Request $request,
        AntrianService $service,
    ): RedirectResponse {
        $petugas = $request->user();

        try {
            $antrian = $service->panggilBerikutnya($petugas);
        } catch (DomainException $exception) {
            return redirect()
                ->route('officer.dashboard')
                ->with('error', $exception->getMessage());
        }

        if (! $antrian) {
            return redirect()
                ->route('officer.dashboard')
                ->with('error', __('officer.flash.no_queue'));
        }

        return redirect()
            ->route('officer.dashboard')
            ->with('status', __('officer.flash.called', [
                'number' => $antrian->nomor_tampil,
                'counter' => $petugas->loket->nama,
            ]));
    }

    public function mulai(
        Antrian $antrian,
        Request $request,
        AntrianService $service,
    ): RedirectResponse {
        return $this->ubahStatus(
            $antrian,
            StatusAntrian::Dilayani,
            $request,
            $service,
            'officer.flash.started',
        );
    }

    public function selesai(
        Antrian $antrian,
        Request $request,
        AntrianService $service,
    ): RedirectResponse {
        return $this->ubahStatus(
            $antrian,
            StatusAntrian::Selesai,
            $request,
            $service,
            'officer.flash.finished',
        );
    }

    public function tidakHadir(
        Antrian $antrian,
        Request $request,
        AntrianService $service,
    ): RedirectResponse {
        return $this->ubahStatus(
            $antrian,
            StatusAntrian::TidakHadir,
            $request,
            $service,
            'officer.flash.absent',
        );
    }

    public function batal(
        Antrian $antrian,
        Request $request,
        AntrianService $service,
    ): RedirectResponse {
        return $this->ubahStatus(
            $antrian,
            StatusAntrian::Batal,
            $request,
            $service,
            'officer.flash.cancelled',
        );
    }

    private function ubahStatus(
        Antrian $antrian,
        StatusAntrian $tujuan,
        Request $request,
        AntrianService $service,
        string $pesan,
    ): RedirectResponse {
        $antrian->loadMissing(['layanan', 'loket']);

        abort_unless($antrian->tanggal->isToday(), 404);

        Gate::authorize('layani-layanan', $antrian->layanan);

        $petugas = $request->user();
        $loket = $petugas->loket;

        if (! $loket) {
            return redirect()
                ->route('officer.dashboard')
                ->with('error', __('officer.flash.no_counter'));
        }

        try {
            $terbaru = $service->ubahStatus($antrian, $tujuan, $petugas, $loket);
        } catch (DomainException $exception) {
            return redirect()
                ->route('officer.dashboard')
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('officer.dashboard')
            ->with('status', $terbaru->nomor_tampil.' '.__($pesan));
    }
}
