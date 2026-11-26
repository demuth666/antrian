<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoketRequest;
use App\Models\Layanan;
use App\Models\Loket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoketController extends Controller
{
    public function index(): View
    {
        return view('admin.lokets.index', [
            'lokets' => Loket::query()
                ->withCount(['layanans', 'petugas'])
                ->orderBy('nama')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.lokets.form', [
            'loket' => new Loket(['aktif' => true]),
            'layananOptions' => $this->opsiLayanan(),
            'layananDipilih' => [],
        ]);
    }

    public function store(LoketRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $layanan = $data['layanan'] ?? [];
        unset($data['layanan']);

        $loket = DB::transaction(function () use ($data, $layanan): Loket {
            $loket = Loket::query()->create($data);
            $loket->layanans()->sync($layanan);

            return $loket;
        });

        return redirect()
            ->route('admin.counters.index')
            ->with('status', __('admin.counters.flash.added', ['name' => $loket->nama]));
    }

    public function edit(Loket $loket): View
    {
        return view('admin.lokets.form', [
            'loket' => $loket,
            'layananOptions' => $this->opsiLayanan(),
            'layananDipilih' => $loket->layanans()->pluck('layanans.id')->all(),
        ]);
    }

    public function update(LoketRequest $request, Loket $loket): RedirectResponse
    {
        $data = $request->validated();
        $layanan = $data['layanan'] ?? [];
        unset($data['layanan']);

        DB::transaction(function () use ($loket, $data, $layanan): void {
            $loket->update($data);
            $loket->layanans()->sync($layanan);
        });

        return redirect()
            ->route('admin.counters.index')
            ->with('status', __('admin.counters.flash.updated', ['name' => $loket->nama]));
    }

    public function destroy(Loket $loket): RedirectResponse
    {
        if ($loket->antrians()->exists()) {
            return redirect()
                ->route('admin.counters.index')
                ->with('error', __('admin.counters.flash.in_use'));
        }

        if ($loket->petugas()->exists()) {
            return redirect()
                ->route('admin.counters.index')
                ->with('error', __('admin.counters.flash.used_by_officer'));
        }

        DB::transaction(function () use ($loket): void {
            $loket->layanans()->detach();
            $loket->delete();
        });

        return redirect()
            ->route('admin.counters.index')
            ->with('status', __('admin.counters.flash.deleted'));
    }

    /**
     * @return array<int, string>
     */
    private function opsiLayanan(): array
    {
        return Layanan::query()
            ->orderBy('nama')
            ->get()
            ->mapWithKeys(fn (Layanan $layanan): array => [
                $layanan->id => $layanan->aktif
                    ? $layanan->nama
                    : $layanan->nama.' ('.__('common.inactive').')',
            ])
            ->all();
    }
}
