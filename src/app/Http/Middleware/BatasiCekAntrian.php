<?php

namespace App\Http\Middleware;

use App\Support\Identitas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class BatasiCekAntrian
{
    public const BATAS_PER_IP = 5;

    public const JEDA_PER_IP = 60;

    public const BATAS_PER_NIK = 10;

    public const JEDA_PER_NIK = 600;

    public function handle(Request $request, Closure $next): Response
    {
        $ipKey = 'cek-antrian:ip:'.$request->ip();
        $inputNik = $request->input('nik');
        $nik = is_string($inputNik) ? Identitas::normalisasiNik($inputNik) : '';
        $nikKey = strlen($nik) === 16 ? 'cek-antrian:nik:'.Identitas::hash($nik) : null;

        $diblokirIp = RateLimiter::tooManyAttempts($ipKey, self::BATAS_PER_IP);
        $diblokirNik = $nikKey !== null && RateLimiter::tooManyAttempts($nikKey, self::BATAS_PER_NIK);

        if ($diblokirIp || $diblokirNik) {
            $detik = max(
                $diblokirIp ? RateLimiter::availableIn($ipKey) : 0,
                $diblokirNik && $nikKey !== null ? RateLimiter::availableIn($nikKey) : 0,
                1,
            );

            return redirect()
                ->route('check-queue')
                ->withErrors([
                    'cek_antrian' => __('queue.rate_limit.message', [
                        'time' => $this->formatJeda($detik),
                    ]),
                ]);
        }

        RateLimiter::hit($ipKey, self::JEDA_PER_IP);

        if ($nikKey !== null) {
            RateLimiter::hit($nikKey, self::JEDA_PER_NIK);
        }

        return $next($request);
    }

    private function formatJeda(int $detik): string
    {
        if ($detik >= 60) {
            $menit = (int) ceil($detik / 60);

            return trans_choice('queue.rate_limit.minutes', $menit, ['count' => $menit]);
        }

        return trans_choice('queue.rate_limit.seconds', $detik, ['count' => $detik]);
    }
}
