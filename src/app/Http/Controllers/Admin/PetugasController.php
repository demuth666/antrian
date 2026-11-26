<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PetugasRequest;
use App\Models\Loket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function index(): View
    {
        return view('admin.petugas.index', [
            'petugas' => User::query()
                ->where('role', UserRole::Petugas)
                ->with('loket')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.petugas.form', [
            'petugas' => new User(['role' => UserRole::Petugas, 'aktif' => true]),
            'loketOptions' => $this->opsiLoket(),
        ]);
    }

    public function store(PetugasRequest $request): RedirectResponse
    {
        User::query()->create([
            ...$request->validated(),
            'role' => UserRole::Petugas,
        ]);

        return redirect()
            ->route('admin.officers.index')
            ->with('status', __('admin.officers.flash.added'));
    }

    public function edit(User $petugas): View
    {
        $this->pastikanPetugas($petugas);

        return view('admin.petugas.form', [
            'petugas' => $petugas,
            'loketOptions' => $this->opsiLoket(),
        ]);
    }

    public function update(PetugasRequest $request, User $petugas): RedirectResponse
    {
        $this->pastikanPetugas($petugas);

        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $petugas->update($data);

        return redirect()
            ->route('admin.officers.index')
            ->with('status', __('admin.officers.flash.updated'));
    }

    public function destroy(User $petugas): RedirectResponse
    {
        $this->pastikanPetugas($petugas);

        $petugas->delete();

        return redirect()
            ->route('admin.officers.index')
            ->with('status', __('admin.officers.flash.deleted'));
    }

    private function pastikanPetugas(User $petugas): void
    {
        abort_unless($petugas->role === UserRole::Petugas, 404);
    }

    /**
     * @return array<int, string>
     */
    private function opsiLoket(): array
    {
        return Loket::query()
            ->orderBy('nama')
            ->get()
            ->mapWithKeys(fn (Loket $loket): array => [
                $loket->id => $loket->aktif
                    ? $loket->nama
                    : $loket->nama.' ('.__('common.inactive').')',
            ])
            ->all();
    }
}
