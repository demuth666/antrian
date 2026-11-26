<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Petugas\CariTiketRequest;
use App\Services\AntrianService;
use Illuminate\View\View;

class CariTiketController extends Controller
{
    public function create(): View
    {
        return view('petugas.cari-tiket', ['hasil' => null]);
    }

    public function store(CariTiketRequest $request, AntrianService $service): View
    {
        return view('petugas.cari-tiket', [
            'hasil' => $service->cariAntrianHariIni((string) $request->validated('nik')),
        ]);
    }
}
