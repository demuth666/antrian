<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BantuanRequest;
use App\Models\Bantuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BantuanController extends Controller
{
    public function index(): View
    {
        return view('admin.bantuans.index', [
            'bantuans' => Bantuan::query()
                ->withCount('antrians')
                ->orderBy('nama')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.bantuans.form', [
            'bantuan' => new Bantuan(['aktif' => true]),
        ]);
    }

    public function store(BantuanRequest $request): RedirectResponse
    {
        Bantuan::query()->create($request->validated());

        return redirect()
            ->route('admin.assistance.index')
            ->with('status', __('admin.assistance.flash.added'));
    }

    public function edit(Bantuan $bantuan): View
    {
        return view('admin.bantuans.form', [
            'bantuan' => $bantuan,
        ]);
    }

    public function update(BantuanRequest $request, Bantuan $bantuan): RedirectResponse
    {
        $bantuan->update($request->validated());

        return redirect()
            ->route('admin.assistance.index')
            ->with('status', __('admin.assistance.flash.updated'));
    }

    public function destroy(Bantuan $bantuan): RedirectResponse
    {
        if ($bantuan->antrians()->exists()) {
            return redirect()
                ->route('admin.assistance.index')
                ->with('error', __('admin.assistance.flash.in_use'));
        }

        DB::transaction(function () use ($bantuan): void {
            $bantuan->antrians()->detach();
            $bantuan->delete();
        });

        return redirect()
            ->route('admin.assistance.index')
            ->with('status', __('admin.assistance.flash.deleted'));
    }
}
