<?php

use App\Http\Controllers\Admin\BantuanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\LoketController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AntrianPollController;
use App\Http\Controllers\CekAntrianController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MasukController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Petugas\AntrianController as AntrianPetugasController;
use App\Http\Controllers\Petugas\CariTiketController;
use App\Http\Controllers\UlasanController;
use App\Http\Middleware\BatasiCekAntrian;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/register', [PendaftaranController::class, 'create'])->name('register');
Route::post('/register', [PendaftaranController::class, 'store'])->name('register.store');

Route::get('/queue/{antrian:kode}', [AntrianController::class, 'show'])->name('queue.show');
Route::post('/queue/{antrian:kode}/review', [UlasanController::class, 'store'])->name('queue.review.store');

Route::get('/check-queue', [CekAntrianController::class, 'create'])->name('check-queue');
Route::post('/check-queue', [CekAntrianController::class, 'store'])
    ->middleware(BatasiCekAntrian::class)
    ->name('check-queue.store');

Route::get('/api/queue/now-serving', AntrianPollController::class)
    ->middleware('throttle:120,1')
    ->name('api.queue.now-serving');

Route::get('/language/{locale}', LanguageController::class)->name('language.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [MasukController::class, 'create'])->name('login');
    Route::post('/login', [MasukController::class, 'store'])->name('login.store');
});

Route::post('/logout', [MasukController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('officer')
    ->name('officer.')
    ->middleware(['auth', 'role:petugas'])
    ->group(function () {
        Route::get('/', [AntrianPetugasController::class, 'index'])->name('dashboard');

        Route::post('/queue/call-next', [AntrianPetugasController::class, 'panggilBerikutnya'])
            ->name('queue.call-next');

        Route::post('/queue/{antrian}/start', [AntrianPetugasController::class, 'mulai'])
            ->name('queue.start');
        Route::post('/queue/{antrian}/finish', [AntrianPetugasController::class, 'selesai'])
            ->name('queue.finish');
        Route::post('/queue/{antrian}/mark-absent', [AntrianPetugasController::class, 'tidakHadir'])
            ->name('queue.mark-absent');
        Route::post('/queue/{antrian}/cancel', [AntrianPetugasController::class, 'batal'])
            ->name('queue.cancel');

        Route::get('/find-ticket', [CariTiketController::class, 'create'])
            ->name('find-ticket');
        Route::post('/find-ticket', [CariTiketController::class, 'store'])
            ->name('find-ticket.store');
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::resource('services', LayananController::class)
            ->parameters(['services' => 'layanan'])
            ->except('show');
        Route::resource('counters', LoketController::class)
            ->parameters(['counters' => 'loket'])
            ->except('show');
        Route::resource('assistance', BantuanController::class)
            ->parameters(['assistance' => 'bantuan'])
            ->except('show');
        Route::resource('officers', PetugasController::class)
            ->parameters(['officers' => 'petugas'])
            ->except('show');
    });
