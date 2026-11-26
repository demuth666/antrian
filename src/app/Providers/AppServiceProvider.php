<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('layani-layanan', function (User $pengguna, Layanan $layanan): bool {
            if (! $pengguna->aktif) {
                return false;
            }

            if ($pengguna->role === UserRole::Admin) {
                return true;
            }

            return $pengguna->loket()
                ->whereHas('layanans', fn ($query) => $query->whereKey($layanan->id))
                ->exists();
        });
    }
}
