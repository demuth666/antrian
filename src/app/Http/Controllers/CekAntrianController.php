<?php

namespace App\Http\Controllers;

use App\Http\Requests\CekAntrianRequest;
use App\Services\AntrianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CekAntrianController extends Controller
{
    public const PESAN_TIDAK_DITEMUKAN = 'Data tidak ditemukan. Periksa kembali NIK dan nomor HP Anda, lalu coba lagi.';

    public function create(): View
    {
        return view('cek-antrian');
    }

    public function store(CekAntrianRequest $request, AntrianService $service): RedirectResponse
    {
        $antrian = $service->cariAntrianAktif(
            (string) $request->validated('nik'),
            (string) $request->validated('hp'),
        );

        if (! $antrian) {
            return redirect()
                ->route('check-queue')
                ->withErrors(['cek_antrian' => __('queue.check.not_found')]);
        }

        $request->session()->put('antrian_kode', $antrian->kode);

        return redirect()
            ->route('queue.show', ['antrian' => $antrian])
            ->with('status', __('queue.check.found'));
    }
}
