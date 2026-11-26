<?php

namespace App\Http\Controllers;

use Akira\QrCode\Facades\QrCode;
use App\Models\Antrian;
use App\Services\AntrianService;
use Illuminate\View\View;

class AntrianController extends Controller
{
    public function show(Antrian $antrian, AntrianService $service): View
    {
        $antrian->load(['pendaftar', 'layanan', 'loket', 'ulasan']);

        $qrSvg = (string) QrCode::format('svg')
            ->size(280)
            ->margin(1)
            ->errorCorrection('M')
            ->generateRaw(route('queue.show', ['antrian' => $antrian]));

        $qrSvg = preg_replace('/^<\?xml.*?\?>\s*/s', '', (string) $qrSvg) ?? (string) $qrSvg;

        return view('antrian.show', [
            'antrian' => $antrian,
            'qrSvg' => $qrSvg,
            'lokets' => $service->statusLoketHariIni(),
            'sisaTeks' => $service->teksSisa($antrian),
            'statusPesan' => $antrian->status->pesan($antrian->loket?->nama),
        ]);
    }
}
