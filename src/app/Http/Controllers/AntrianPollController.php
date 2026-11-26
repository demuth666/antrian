<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Services\AntrianService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AntrianPollController extends Controller
{
    public function __invoke(Request $request, AntrianService $service): JsonResponse
    {
        $kode = $request->query('kode');
        $antrian = null;

        if (is_string($kode) && Str::isUlid($kode)) {
            $antrian = Antrian::query()
                ->with(['layanan', 'loket'])
                ->where('kode', $kode)
                ->first();
        }

        return response()
            ->json([
                'loket' => $service->statusLoketHariIni(),
                'tiket' => $antrian ? [
                    'status' => $antrian->status->value,
                    'status_label' => $antrian->status->label(),
                    'pesan' => $antrian->status->pesan($antrian->loket?->nama),
                    'terminal' => $antrian->status->terminal(),
                    'aktif' => $antrian->aktifHariIni(),
                    'nomor_tampil' => $antrian->nomor_tampil,
                    'loket' => $antrian->loket?->nama,
                    'sisa' => $service->hitungSisa($antrian),
                    'sisa_teks' => $service->teksSisa($antrian),
                ] : null,
                'diperbarui' => now()->toIso8601String(),
            ])
            ->header('Cache-Control', 'no-store');
    }
}
