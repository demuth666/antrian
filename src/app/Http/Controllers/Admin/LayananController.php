<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayananRequest;
use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(): View
    {
        return view('admin.layanan.index', [
            'layanans' => Layanan::query()
                ->withCount('lokets')
                ->orderBy('prefix')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.layanan.form', [
            'layanan' => new Layanan(['aktif' => true]),
        ]);
    }

    public function store(LayananRequest $request): RedirectResponse
    {
        Layanan::query()->create($request->validated());

        return redirect()
            ->route('admin.services.index')
            ->with('status', __('admin.services.flash.added'));
    }

    public function edit(Layanan $layanan): View
    {
        return view('admin.layanan.form', [
            'layanan' => $layanan,
        ]);
    }

    public function update(LayananRequest $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($request->validated());

        return redirect()
            ->route('admin.services.index')
            ->with('status', __('admin.services.flash.updated'));
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        if ($layanan->antrians()->exists()) {
            return redirect()
                ->route('admin.services.index')
                ->with('error', __('admin.services.flash.in_use'));
        }

        DB::transaction(function () use ($layanan): void {
            $layanan->lokets()->detach();
            $layanan->delete();
        });

        return redirect()
            ->route('admin.services.index')
            ->with('status', __('admin.services.flash.deleted'));
    }
}
