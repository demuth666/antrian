<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\MasukRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MasukController extends Controller
{
    private const BATAS_PERCOBAAN = 5;

    private const JEDA_PERCOBAAN = 60;

    public function create(): View
    {
        return view('masuk');
    }

    public function store(MasukRequest $request): RedirectResponse
    {
        $email = Str::lower((string) $request->validated('email'));
        $kunciPembatas = 'masuk:'.hash('sha256', $email.'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($kunciPembatas, self::BATAS_PERCOBAAN)) {
            throw ValidationException::withMessages([
                'email' => __('auth.rate_limited', [
                    'seconds' => RateLimiter::availableIn($kunciPembatas),
                ]),
            ]);
        }

        RateLimiter::hit($kunciPembatas, self::JEDA_PERCOBAAN);

        $berhasil = Auth::attempt(
            [
                'email' => $email,
                'password' => (string) $request->validated('password'),
                'aktif' => true,
            ],
            $request->boolean('remember'),
        );

        if (! $berhasil) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($kunciPembatas);
        $request->session()->regenerate();

        return redirect()->intended($this->tujuan($request->user()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', __('auth.flash.logged_out'));
    }

    private function tujuan(?User $pengguna): string
    {
        return $pengguna?->role === UserRole::Admin
            ? route('admin.dashboard')
            : route('officer.dashboard');
    }
}
