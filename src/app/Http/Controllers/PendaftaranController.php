<?php

namespace App\Http\Controllers;

use App\Enums\JenisKelamin;
use App\Enums\KategoriAntrian;
use App\Http\Requests\StorePendaftaranRequest;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Services\AntrianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function create(): View
    {
        return view('pendaftaran.create', [
            'kategoriOptions' => $this->opsiDariEnum(KategoriAntrian::cases()),
            'jenisDisabilitasOptions' => $this->opsiJenisDisabilitas(),
            'jenisKelaminOptions' => $this->opsiDariEnum(JenisKelamin::cases()),
            'layananOptions' => Layanan::query()
                ->where('aktif', true)
                ->orderBy('nama')
                ->pluck('nama', 'id')
                ->all(),
            'bantuanOptions' => Bantuan::query()
                ->where('aktif', true)
                ->orderBy('id')
                ->pluck('nama', 'id')
                ->all(),
        ]);
    }

    public function store(StorePendaftaranRequest $request, AntrianService $service): RedirectResponse
    {
        $hasil = $service->daftar($request->validated());

        $request->session()->put('antrian_kode', $hasil['antrian']->kode);

        $redirect = redirect()->route('queue.show', ['antrian' => $hasil['antrian']]);

        return $hasil['dibuat']
            ? $redirect->with('status', __('registration.flash.created'))->with('buka_ulasan', true)
            : $redirect->with('status', __('registration.flash.existing'));
    }

    /**
     * @param  array<int, KategoriAntrian|JenisKelamin>  $cases
     * @return array<string, string>
     */
    private function opsiDariEnum(array $cases): array
    {
        $opsi = [];

        foreach ($cases as $case) {
            $opsi[$case->value] = $case->label();
        }

        return $opsi;
    }

    /**
     * @return array<string, string>
     */
    private function opsiJenisDisabilitas(): array
    {
        $opsi = [];

        foreach (array_keys(config('layanan.jenis_disabilitas')) as $kode) {
            $opsi[$kode] = __('enums.disability_types.'.$kode);
        }

        return $opsi;
    }
}
