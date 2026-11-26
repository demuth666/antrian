<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Bantuan;
use App\Models\Layanan;
use App\Models\Loket;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'jumlahLayanan' => Layanan::query()->count(),
            'jumlahLayananAktif' => Layanan::query()->where('aktif', true)->count(),
            'jumlahLoket' => Loket::query()->count(),
            'jumlahLoketAktif' => Loket::query()->where('aktif', true)->count(),
            'jumlahBantuan' => Bantuan::query()->count(),
            'jumlahPetugas' => User::query()->where('role', UserRole::Petugas)->count(),
            'jumlahPetugasAktif' => User::query()
                ->where('role', UserRole::Petugas)
                ->where('aktif', true)
                ->count(),
            'jumlahUlasan' => Ulasan::query()->count(),
            'rataRataUlasan' => round((float) Ulasan::query()->avg('rating'), 1),
            'ulasanTerbaru' => Ulasan::query()
                ->with(['antrian.layanan', 'antrian.pendaftar'])
                ->latest('id')
                ->limit(3)
                ->get(),
        ]);
    }
}
